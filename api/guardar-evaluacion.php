<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Evaluacion/EmpresaRepository.php';
require_once __DIR__ . '/../src/Evaluacion/EvaluacionRepository.php';
require_once __DIR__ . '/../includes/cuestionario-data.php'; // define $controles

$entrada = json_decode(file_get_contents('php://input'), true);

if (!$entrada || empty($entrada['organizacion']) || empty($entrada['evaluador'])
    || empty($entrada['fecha']) || empty($entrada['respuestas'])) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Datos incompletos.']);
    exit;
}

$respuestasCrudas = $entrada['respuestas']; // { "1": "si", "2": "no", "3": "na", ... }
$dimensiones = ['C', 'I', 'D'];
$obtenido = ['C' => 0, 'I' => 0, 'D' => 0];
$maximo   = ['C' => 0, 'I' => 0, 'D' => 0];

$respuestasParaGuardar = [];
$resultadosControl = [];

function nivelMadurezPHP(float $pct): int
{
    if ($pct <= 0)   return 0;
    if ($pct <= 20)  return 1;
    if ($pct <= 45)  return 2;
    if ($pct <= 70)  return 3;
    if ($pct <= 90)  return 4;
    return 5;
}

foreach ($controles as $c) {
    $aplicables = 0;
    $cumplidas = 0;

    foreach ($c['preguntas'] as $p) {
        $valor = $respuestasCrudas[$p['id']] ?? null;
        if (!in_array($valor, ['si', 'no', 'na'], true)) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => "Falta o es inválida la respuesta {$p['id']}."]);
            exit;
        }
        $respuestasParaGuardar[] = ['pregunta_id' => $p['id'], 'respuesta' => $valor];

        if ($valor === 'na') continue;
        $aplicables++;
        if ($valor === 'si') $cumplidas++;
    }

    $pctControl = $aplicables > 0 ? ($cumplidas / $aplicables) * 100 : 0;
    $madurez = nivelMadurezPHP($pctControl);

    $resultadosControl[] = [
        'id' => $c['id'],
        'pctControl' => round($pctControl, 2),
        'madurez' => $madurez,
    ];

    foreach ($dimensiones as $dim) {
        $pesoDim = ['C' => $c['peso_c'], 'I' => $c['peso_i'], 'D' => $c['peso_d']][$dim];
        $factorPeso = $c['peso'] * $pesoDim;
        $obtenido[$dim] += ($madurez / 5) * $factorPeso;
        $maximo[$dim]   += $factorPeso;
    }
}

$pct = [];
foreach ($dimensiones as $dim) {
    $pct[$dim] = $maximo[$dim] > 0 ? round(($obtenido[$dim] / $maximo[$dim]) * 100, 2) : 0;
}
$pctGlobal = round(($pct['C'] + $pct['I'] + $pct['D']) / 3, 2);

try {
    $pdo = db();
    $empresaRepo    = new EmpresaRepository($pdo);
    $evaluacionRepo = new EvaluacionRepository($pdo);

    $empresaId = $empresaRepo->obtenerOCrear(trim($entrada['organizacion']));

    $evaluacionId = $evaluacionRepo->guardar([
        'empresa_id'        => $empresaId,
        'evaluador'         => trim($entrada['evaluador']),
        'area_evaluada'     => $entrada['area_evaluada'] ?? null,
        'dba'               => $entrada['dba'] ?? null,
        'fecha'             => $entrada['fecha'],
        'pct'               => $pct,
        'pct_global'        => $pctGlobal,
        'respuestas'        => $respuestasParaGuardar,
        'resultadosControl' => $resultadosControl,
    ]);

    echo json_encode([
        'ok' => true,
        'evaluacion_id' => $evaluacionId,
        'empresa_id' => $empresaId,
        'pct' => $pct,
        'pct_global' => $pctGlobal,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error al guardar la evaluación.', 'detalle' => $e->getMessage()]);
}