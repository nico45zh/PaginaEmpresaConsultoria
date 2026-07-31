<?php
$pageTitle = "Evaluación de riesgos";
include "includes/header.php";
include "includes/navbar.php";
include "includes/cuestionario-data.php";

// Orden de despliegue solicitado: Confidencialidad, Integridad, Disponibilidad
$orden_grupos = ['I', 'D','C' ];

// Agrupar preguntas por dimensión para el render
$preguntas_por_grupo = ['C' => [], 'I' => [], 'D' => []];
foreach ($preguntas as $p) {
    $preguntas_por_grupo[$p['grupo']][] = $p;
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
                Cuestionario basado en buenas prácticas de <strong>ISO/IEC 27002</strong>, <strong>ISO/IEC 27005</strong>
                y <strong>COBIT</strong>. Responde cada control según su nivel de cumplimiento actual; al finalizar
                obtendrás un panel con el índice de riesgo por dimensión de la triada
                <strong>Confidencialidad · Integridad · Disponibilidad</strong> y recomendaciones de mejora.
            </p>
        </div>
    </section>

    <!-- CUESTIONARIO -->
    <section class="section section-alt">
        <div class="container">
            <form id="form-evaluacion">

                <?php foreach ($orden_grupos as $g): ?>
                    <div class="eval-group">
                        <h2 class="eval-group-title">
                            <span class="eval-group-tag tag-<?php echo strtolower($g); ?>"><?php echo $g; ?></span>
                            <?php echo $grupos[$g]; ?>
                        </h2>

                        <div class="eval-questions">
                            <?php foreach ($preguntas_por_grupo[$g] as $p): ?>
                                <div class="eval-question" data-pregunta-id="<?php echo $p['id']; ?>">
                                    <p class="eval-question-text">
                                        <span class="eval-question-num"><?php echo $p['id']; ?>.</span>
                                        <?php echo htmlspecialchars($p['texto']); ?>
                                    </p>
                                    <div class="eval-options" role="radiogroup" aria-label="Pregunta <?php echo $p['id']; ?>">
                                        <?php foreach ($opciones_respuesta as $valor => $etiqueta): ?>
                                            <label class="eval-option">
                                                <input type="radio" name="p<?php echo $p['id']; ?>" value="<?php echo $valor; ?>" required>
                                                <span><?php echo $etiqueta; ?></span>
                                            </label>
                                        <?php endforeach; ?>
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
                    <small>Índice global de seguridad</small>
                </div>
                <div class="eval-global-meta">
                    <span id="eval-global-badge" class="eval-badge">—</span>
                    <p id="eval-global-text" class="eval-global-text"></p>
                </div>
            </div>

            <div class="row g-4 mt-2" id="eval-dimension-cards">
                <!-- Tarjetas C / I / D generadas por JS -->
            </div>

            <div class="row g-4 mt-4">
                <div class="col-md-7">
                    <div class="eval-chart-card">
                        <h6>Cumplimiento por dimensión (C · I · D)</h6>
                        <canvas id="chart-barras" height="220"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="eval-chart-card">
                        <h6>Nivel general de cumplimiento</h6>
                        <canvas id="chart-circular" height="220"></canvas>
                    </div>
                </div>
            </div>

            <div class="eval-recos-card mt-4">
                <h6><i class="fa-solid fa-lightbulb me-2"></i>Recomendaciones de mejora</h6>
                <ul id="eval-recos-list" class="eval-recos-list"></ul>
            </div>

            <div class="eval-weak-card mt-4">
                <h6><i class="fa-solid fa-triangle-exclamation me-2"></i>Controles más débiles detectados</h6>
                <ul id="eval-weak-list" class="eval-weak-list"></ul>
            </div>
        </div>
    </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script id="eval-data" type="application/json"><?php echo json_encode([
    'preguntas' => $preguntas,
    'recomendaciones' => $recomendaciones,
    'grupos' => $grupos,
]); ?></script>
<script src="assets/js/evaluacion.js"></script>

<?php include "includes/footer.php"; ?>
