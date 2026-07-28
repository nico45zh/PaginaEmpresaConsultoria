<?php
/**
 * Cuestionario del Módulo de Evaluación de Riesgos.
 *
 * Cada pregunta define su categoría de despliegue (grupo visual, según el
 * orden original ISO: integridad / disponibilidad / confidencialidad) y su
 * matriz de ponderación real sobre la triada CIA (0 = sin influencia,
 * 1 = baja, 2 = media, 3 = alta), tal como pide la metodología.
 *
 * Este arreglo es la única fuente de verdad: se usa para pintar el
 * formulario en PHP y también se serializa a JSON para que
 * assets/js/evaluacion.js calcule los resultados sin duplicar datos.
 */

$grupos = [
    'I' => 'Integridad',
    'D' => 'Disponibilidad',
	'C' => 'Confidencialidad'
];

$preguntas = [
    // ===================== INTEGRIDAD =====================
    ['id' => 1,  'grupo' => 'I', 'texto' => '¿Existe control de cambios formal antes de modificar tablas/índices de la BD?', 'w' => ['C' => 1, 'I' => 3, 'D' => 1]],
    ['id' => 2,  'grupo' => 'I', 'texto' => '¿La BD tiene integridad referencial (llaves foráneas, constraints, triggers)?', 'w' => ['C' => 0, 'I' => 3, 'D' => 1]],
    ['id' => 3,  'grupo' => 'I', 'texto' => '¿Se registra en bitácora quién modifica los datos?', 'w' => ['C' => 2, 'I' => 3, 'D' => 0]],
    ['id' => 4,  'grupo' => 'I', 'texto' => '¿Se validan los respaldos con checksums o firmas?', 'w' => ['C' => 0, 'I' => 3, 'D' => 2]],
    ['id' => 5,  'grupo' => 'I', 'texto' => '¿Hay inventario de qué tablas tienen información crítica?', 'w' => ['C' => 2, 'I' => 2, 'D' => 1]],
    ['id' => 6,  'grupo' => 'I', 'texto' => '¿Los privilegios de escritura están restringidos a perfiles autorizados?', 'w' => ['C' => 2, 'I' => 3, 'D' => 0]],
    ['id' => 7,  'grupo' => 'I', 'texto' => '¿Se exige autenticación individual (no usuarios compartidos)?', 'w' => ['C' => 3, 'I' => 2, 'D' => 0]],
    ['id' => 8,  'grupo' => 'I', 'texto' => '¿Hay segregación entre quien programa un cambio y quien lo aprueba?', 'w' => ['C' => 1, 'I' => 3, 'D' => 0]],
    ['id' => 9,  'grupo' => 'I', 'texto' => '¿El motor garantiza transacciones ACID?', 'w' => ['C' => 0, 'I' => 3, 'D' => 1]],
    ['id' => 10, 'grupo' => 'I', 'texto' => '¿Se revisa periódicamente la consistencia referencial?', 'w' => ['C' => 0, 'I' => 3, 'D' => 1]],

    // ===================== DISPONIBILIDAD =====================
    ['id' => 11, 'grupo' => 'D', 'texto' => '¿Hay backups programados con pruebas de restauración?', 'w' => ['C' => 0, 'I' => 1, 'D' => 3]],
    ['id' => 12, 'grupo' => 'D', 'texto' => '¿Existe redundancia/replicación/alta disponibilidad?', 'w' => ['C' => 0, 'I' => 1, 'D' => 3]],
    ['id' => 13, 'grupo' => 'D', 'texto' => '¿Se monitorea capacidad (disco, conexiones, rendimiento)?', 'w' => ['C' => 0, 'I' => 0, 'D' => 3]],
    ['id' => 14, 'grupo' => 'D', 'texto' => '¿Hay un plan de recuperación ante desastres (DRP) para la BD?', 'w' => ['C' => 0, 'I' => 1, 'D' => 3]],
    ['id' => 15, 'grupo' => 'D', 'texto' => '¿Se hace mantenimiento preventivo (reindexar, actualizar estadísticas)?', 'w' => ['C' => 0, 'I' => 2, 'D' => 2]],
    ['id' => 16, 'grupo' => 'D', 'texto' => '¿Hay alertas automáticas ante caídas del servicio?', 'w' => ['C' => 0, 'I' => 0, 'D' => 3]],
    ['id' => 17, 'grupo' => 'D', 'texto' => '¿El servidor está segmentado de redes no confiables?', 'w' => ['C' => 3, 'I' => 0, 'D' => 2]],
    ['id' => 18, 'grupo' => 'D', 'texto' => '¿Hay un SLA de tiempo de actividad definido?', 'w' => ['C' => 0, 'I' => 0, 'D' => 3]],

    // ===================== CONFIDENCIALIDAD =====================
    ['id' => 19, 'grupo' => 'C', 'texto' => '¿El acceso es por roles con privilegio mínimo (RBAC)?', 'w' => ['C' => 3, 'I' => 2, 'D' => 1]],
    ['id' => 20, 'grupo' => 'C', 'texto' => '¿Los datos sensibles se cifran en reposo y tránsito?', 'w' => ['C' => 3, 'I' => 1, 'D' => 0]],
    ['id' => 21, 'grupo' => 'C', 'texto' => '¿Los ambientes de prueba usan datos enmascarados?', 'w' => ['C' => 3, 'I' => 0, 'D' => 0]],
    ['id' => 22, 'grupo' => 'C', 'texto' => '¿Hay clasificación de la información (pública/interna/confidencial)?', 'w' => ['C' => 3, 'I' => 1, 'D' => 0]],
    ['id' => 23, 'grupo' => 'C', 'texto' => '¿Están segregadas las funciones de DBA/dev/usuario final?', 'w' => ['C' => 2, 'I' => 2, 'D' => 0]],
    ['id' => 24, 'grupo' => 'C', 'texto' => '¿Se exige autenticación multifactor para datos sensibles?', 'w' => ['C' => 3, 'I' => 0, 'D' => 0]],
    ['id' => 25, 'grupo' => 'C', 'texto' => '¿Los terceros con acceso firman acuerdo de confidencialidad?', 'w' => ['C' => 2, 'I' => 0, 'D' => 0]],
    ['id' => 26, 'grupo' => 'C', 'texto' => '¿Hay controles anti fuga de datos (DLP) en exportaciones masivas?', 'w' => ['C' => 3, 'I' => 1, 'D' => 0]],
];

$opciones_respuesta = [
    0 => 'No cumple',
    1 => 'Cumple parcialmente',
    2 => 'Cumple completamente',
];

$recomendaciones = [
    'D' => 'Se recomienda fortalecer mecanismos de respaldo, recuperación ante desastres, monitoreo y continuidad operativa.',
    'C' => 'Se recomienda fortalecer controles de acceso, autenticación, cifrado y protección de información sensible.',
    'I' => 'Se recomienda mejorar controles de cambios, auditoría, validación de datos y segregación de funciones.',
];
