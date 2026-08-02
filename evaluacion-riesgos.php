<?php
$pageTitle = "Evaluación de riesgos";
include "includes/header.php";
include "includes/navbar.php";
include "includes/cuestionario-data.php";

// Orden de despliegue por dominio
$dominios_orden = ['Organizacional', 'Tecnológico'];

$controles_por_dominio = ['Organizacional' => [], 'Tecnológico' => []];
foreach ($controles as $c) {
    $controles_por_dominio[$c['dominio']][] = $c;
}
?>

<link rel="stylesheet" href="assets/css/evaluacion.css">

<main class="flex-grow-1">

    <!-- INTRO -->
    <section class="section eval-hero">
        <div class="container">
            <span class="section-eyebrow"><i class="fa-solid fa-clipboard-check me-2"></i>Módulo de evaluación</span>
            <h1 class="section-title eval-title">Evaluación de riesgos de tu base de datos</h1>
            <p class="section-lead">
                Cuestionario basado en <strong>18 controles reales de ISO/IEC 27002:2022</strong> y prácticas de
                <strong>COBIT</strong>, agrupados por dominio. Responde cada pregunta con Sí, No o No aplica; el nivel
                de madurez de cada control se calcula automáticamente a partir de tus respuestas, y al finalizar
                obtendrás el índice de exposición al riesgo por dimensión de la triada
                <strong>Confidencialidad · Integridad · Disponibilidad</strong>.
            </p>
        </div>
    </section>

    <!-- CUESTIONARIO -->
    <section class="section section-alt">
        <div class="container">
            <form id="form-evaluacion">

                <?php foreach ($dominios_orden as $dom): ?>
                    <div class="eval-group">
                        <h2 class="eval-group-title">
                            <span class="eval-group-tag tag-dom"><?php echo strtoupper(substr($dom, 0, 3)); ?></span>
                            <?php echo $dom; ?>
                        </h2>

                        <div class="eval-controls">
                            <?php foreach ($controles_por_dominio[$dom] as $c): ?>
                                <div class="eval-control">
                                    <div class="eval-control-head">
                                        <span class="eval-control-code"><?php echo $c['codigo']; ?></span>
                                        <h3 class="eval-control-name"><?php echo htmlspecialchars($c['nombre']); ?></h3>
                                    </div>
                                    <p class="eval-control-objetivo"><?php echo htmlspecialchars($c['objetivo']); ?></p>

                                    <div class="eval-preguntas">
                                        <?php foreach ($c['preguntas'] as $p): ?>
                                            <div class="eval-question" data-pregunta-id="<?php echo $p['id']; ?>">
                                                <p class="eval-question-text"><?php echo htmlspecialchars($p['texto']); ?></p>
                                                <div class="eval-options" role="radiogroup" aria-label="Pregunta <?php echo $p['id']; ?>">
                                                    <label class="eval-option">
                                                        <input type="radio" name="p<?php echo $p['id']; ?>_resp" value="Si" class="resp-radio" data-control="<?php echo $c['id']; ?>" required>
                                                        <span>Sí</span>
                                                    </label>
                                                    <label class="eval-option">
                                                        <input type="radio" name="p<?php echo $p['id']; ?>_resp" value="No" class="resp-radio" data-control="<?php echo $c['id']; ?>" required>
                                                        <span>No</span>
                                                    </label>
                                                    <label class="eval-option">
                                                        <input type="radio" name="p<?php echo $p['id']; ?>_resp" value="NA" class="resp-radio" data-control="<?php echo $c['id']; ?>" required>
                                                        <span>No aplica</span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <div class="eval-control-madurez" data-madurez-display-for="<?php echo $c['id']; ?>">
                                        <i class="fa-solid fa-gauge-high me-2"></i>
                                        <span>Madurez calculada:</span>
                                        <strong class="eval-control-madurez-value">Pendiente</strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="eval-submit-row">
                    <button type="submit" class="btn btn-cta btn-lg">
                        Calcular resultados
                        <i class="fa-solid fa-chart-line ms-2"></i>
                    </button>
                    <p class="eval-submit-hint">Debes responder las 26 preguntas para ver el panel de resultados.</p>
                </div>
            </form>
        </div>
    </section>

    <!-- DASHBOARD DE RESULTADOS (oculto hasta calcular) -->
    <section id="eval-dashboard" class="section eval-dashboard" hidden>
        <div class="container">
            <span class="section-eyebrow">Resultados</span>
            <h2 class="section-title">Panel de evaluación</h2>

            <div class="eval-global-card" id="eval-global-card">
                <div class="eval-global-score">
                    <span id="eval-global-pct">0%</span>
                    <small>Índice global de exposición al riesgo</small>
                </div>
                <div class="eval-global-meta">
                    <span id="eval-global-badge" class="eval-badge">—</span>
                    <p id="eval-global-text" class="eval-global-text"></p>
                </div>
            </div>

            <div class="row g-4 mt-2" id="eval-dimension-cards"></div>

            <div class="row g-4 mt-4">
                <div class="col-md-7">
                    <div class="eval-chart-card">
                        <h6>Exposición al riesgo por dimensión (C · I · D)</h6>
                        <canvas id="chart-barras" height="220"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="eval-chart-card">
                        <h6>Nivel general de exposición</h6>
                        <canvas id="chart-circular" height="220"></canvas>
                    </div>
                </div>
            </div>

            <div class="eval-recos-card mt-4">
                <h6><i class="fa-solid fa-lightbulb me-2"></i>Recomendaciones de mejora</h6>
                <ul id="eval-recos-list" class="eval-recos-list"></ul>
            </div>

            <div class="eval-weak-card mt-4">
                <h6><i class="fa-solid fa-triangle-exclamation me-2"></i>Controles con menor nivel de madurez</h6>
                <ul id="eval-weak-list" class="eval-weak-list"></ul>
            </div>
        </div>
    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script id="eval-data" type="application/json"><?php echo json_encode([
    'controles' => $controles,
    'recomendaciones' => $recomendaciones,
    'grupos' => $grupos,
    'rangos_madurez' => $rangos_madurez,
]); ?></script>
<script src="assets/js/evaluacion.js"></script>

<?php include "includes/footer.php"; ?>