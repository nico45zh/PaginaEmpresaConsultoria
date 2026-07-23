<?php $pageTitle = "Inicio"; include "includes/header.php"; ?>
<?php include "includes/navbar.php"; ?>

<main class="flex-grow-1">

    <!-- HERO -->
    <section class="hero">
        <div class="container hero-grid">

            <div class="hero-copy">
                <span class="eyebrow"><i class="fa-solid fa-shield-halved me-2"></i>Consultoría en seguridad de la información</span>
                <h1 class="hero-title">
                    La IA no reemplaza al<br>
                    administrador de riesgos.<br>
                    <span class="hero-title-accent">Lo hace más preciso.</span>
                </h1>
                <p class="hero-lead">
                    Ayudamos a las organizaciones a identificar, evaluar y tratar sus riesgos de
                    seguridad de la información, apoyando el cumplimiento de las normas
                    <strong>ISO/IEC 27001</strong>, <strong>27002</strong> y <strong>27007</strong>, con
                    Inteligencia Artificial como herramienta de apoyo y al administrador siempre al mando de la decisión.
                </p>
                <div class="hero-actions">
                    <a href="#contacto" class="btn btn-cta btn-lg">Solicitar diagnóstico</a>
                    <a href="#metodologia" class="btn btn-ghost btn-lg">Ver metodología</a>
                </div>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="matrix-card">
                    <div class="matrix-card-head">
                        <span>Matriz de riesgo</span>
                        <span class="matrix-card-head-sub">probabilidad × impacto</span>
                    </div>
                    <div class="risk-matrix">
                        <div class="cell low"></div><div class="cell low"></div><div class="cell mid"></div><div class="cell high"></div><div class="cell high"></div>
                        <div class="cell low"></div><div class="cell mid"></div><div class="cell mid"></div><div class="cell high"></div><div class="cell high"></div>
                        <div class="cell low"></div><div class="cell mid"></div><div class="cell high"></div><div class="cell high"></div><div class="cell crit"></div>
                        <div class="cell mid"></div><div class="cell mid"></div><div class="cell high"></div><div class="cell crit"></div><div class="cell crit"></div>
                        <div class="cell mid"></div><div class="cell high"></div><div class="cell crit"></div><div class="cell crit"></div><div class="cell crit"></div>
                    </div>
                    <div class="matrix-card-legend">
                        <span><i class="dot low"></i>Bajo</span>
                        <span><i class="dot mid"></i>Medio</span>
                        <span><i class="dot high"></i>Alto</span>
                        <span><i class="dot crit"></i>Crítico</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- SERVICIOS -->
    <section id="servicios" class="section">
        <div class="container">
            <span class="section-eyebrow">Servicios</span>
            <h2 class="section-title">Donde el criterio humano dirige y la IA acelera</h2>

            <div class="row g-4 mt-2">

                <div class="col-md-4">
                    <div class="service-card">
                        <i class="fa-solid fa-magnifying-glass-chart"></i>
                        <h5>Identificación y evaluación de riesgos</h5>
                        <p>Levantamiento de activos, amenazas y vulnerabilidades, con modelos de IA que priorizan
                        los riesgos de mayor probabilidad e impacto para agilizar el análisis del equipo.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="service-card">
                        <i class="fa-solid fa-list-check"></i>
                        <h5>Implementación de SGSI (ISO/IEC 27001)</h5>
                        <p>Diseño e implementación del Sistema de Gestión de Seguridad de la Información,
                        aplicando el ciclo PHVA y los controles de la ISO/IEC 27002.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="service-card">
                        <i class="fa-solid fa-clipboard-check"></i>
                        <h5>Auditoría interna (ISO/IEC 27007)</h5>
                        <p>Planificación y ejecución de auditorías del SGSI, con apoyo de IA en la revisión de
                        evidencia documental y detección de no conformidades.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- METODOLOGIA -->
    <section id="metodologia" class="section section-alt">
        <div class="container">
            <span class="section-eyebrow">Metodología</span>
            <h2 class="section-title">Ciclo de administración de riesgos</h2>

            <div class="cycle">
                <div class="cycle-step">
                    <span class="cycle-tag">01</span>
                    <h6>Identificación</h6>
                    <p>Reconocimiento de activos, amenazas y vulnerabilidades de información.</p>
                </div>
                <div class="cycle-step">
                    <span class="cycle-tag">02</span>
                    <h6>Análisis y evaluación</h6>
                    <p>Estimación de probabilidad e impacto para priorizar los riesgos críticos.</p>
                </div>
                <div class="cycle-step">
                    <span class="cycle-tag">03</span>
                    <h6>Tratamiento</h6>
                    <p>Mitigación, transferencia, aceptación o evitación, según cada caso.</p>
                </div>
                <div class="cycle-step">
                    <span class="cycle-tag">04</span>
                    <h6>Monitoreo y revisión</h6>
                    <p>Verificación de controles y ajuste continuo frente a nuevos riesgos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- NORMAS -->
    <section id="normas" class="section">
        <div class="container">
            <span class="section-eyebrow">Marco normativo</span>
            <h2 class="section-title">Normas ISO/IEC que guían nuestro trabajo</h2>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="norm-card">
                        <span class="norm-code">ISO/IEC 27001</span>
                        <p>Requisitos para establecer, implementar, mantener y mejorar un SGSI.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="norm-card">
                        <span class="norm-code">ISO/IEC 27002</span>
                        <p>Directrices y catálogo de controles de seguridad de la información.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="norm-card">
                        <span class="norm-code">ISO/IEC 27007</span>
                        <p>Lineamientos para la auditoría de sistemas de gestión de seguridad de la información.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- EQUIPO -->
    <section id="equipo" class="section section-alt">
        <div class="container">
            <span class="section-eyebrow">Equipo consultor</span>
            <h2 class="section-title">Quiénes están detrás de RiskGuard</h2>

            <div class="row g-4 mt-2">
                <div class="col-6 col-md-3">
                    <div class="team-card">
                        <div class="team-avatar">JS</div>
                        <h6>Julissa Solano</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="team-card">
                        <div class="team-avatar">IN</div>
                        <h6>Isaac Naranjo</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="team-card">
                        <div class="team-avatar">NZ</div>
                        <h6>Nicolás Zárate Hernández</h6>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="team-card">
                        <div class="team-avatar">HH</div>
                        <h6>Hermann Hidalgo Araya</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="section">
        <div class="container">
            <div class="contact-box">
                <div>
                    <span class="section-eyebrow">Contacto</span>
                    <h2 class="section-title">Conversemos sobre los riesgos de tu organización</h2>
                    <p class="section-lead">Escríbenos y te contactamos para agendar un primer diagnóstico.</p>
                </div>

                <form class="contact-form" method="post" action="#">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Nombre" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Correo electrónico" required>
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control" placeholder="Organización">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" rows="4" placeholder="Cuéntanos brevemente tu necesidad"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-cta btn-lg w-100 w-md-auto">Enviar mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

</main>

<?php include "includes/footer.php"; ?>
