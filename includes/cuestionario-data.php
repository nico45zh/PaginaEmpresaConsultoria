<?php
/**
 * Cuestionario del Módulo de Evaluación de Riesgos — estructura jerárquica.
 *
 * Dominio (ISO/IEC 27002:2022) > Control (código, peso, relación C/I/D) > Pregunta (Sí/No/N-A)
 *
 * El nivel de madurez YA NO lo elige el auditor: se calcula a partir del
 * % de preguntas respondidas "Sí" dentro de cada control (ver assets/js/evaluacion.js).
 */

$controles = [
    ['id' => 1, 'codigo' => '5.15', 'nombre' => 'Control de acceso', 'dominio' => 'Organizacional',
     'objetivo' => 'Asegurar que el acceso a la base de datos se otorgue únicamente a perfiles autorizados y con el privilegio mínimo necesario.',
     'peso' => 3, 'w' => ['C' => 3, 'I' => 3, 'D' => 1],
     'preguntas' => [
        ['id' => 19, 'texto' => '¿El acceso es por roles con privilegio mínimo (RBAC)?'],
        ['id' => 6,  'texto' => '¿Los privilegios de escritura están restringidos a perfiles autorizados?'],
     ]],

    ['id' => 2, 'codigo' => '8.5', 'nombre' => 'Autenticación segura', 'dominio' => 'Tecnológico',
     'objetivo' => 'Garantizar que solo usuarios verificados individualmente puedan acceder a la base de datos.',
     'peso' => 3, 'w' => ['C' => 3, 'I' => 2, 'D' => 0],
     'preguntas' => [
        ['id' => 7,  'texto' => '¿Se exige autenticación individual (no usuarios compartidos)?'],
        ['id' => 24, 'texto' => '¿Se exige autenticación multifactor para datos sensibles?'],
     ]],

    ['id' => 3, 'codigo' => '5.3', 'nombre' => 'Segregación de funciones', 'dominio' => 'Organizacional',
     'objetivo' => 'Evitar que una misma persona concentre permisos que permitan cometer o encubrir errores/fraudes sin supervisión.',
     'peso' => 2, 'w' => ['C' => 2, 'I' => 3, 'D' => 0],
     'preguntas' => [
        ['id' => 8,  'texto' => '¿Hay segregación entre quien programa un cambio y quien lo aprueba?'],
        ['id' => 23, 'texto' => '¿Están segregadas las funciones de DBA/dev/usuario final?'],
     ]],

    ['id' => 4, 'codigo' => '8.32', 'nombre' => 'Gestión de cambios', 'dominio' => 'Tecnológico',
     'objetivo' => 'Controlar que las modificaciones a la base de datos sigan un proceso formal de solicitud, prueba y aprobación.',
     'peso' => 3, 'w' => ['C' => 1, 'I' => 3, 'D' => 1],
     'preguntas' => [
        ['id' => 1, 'texto' => '¿Existe control de cambios formal antes de modificar tablas/índices de la BD?'],
     ]],

    ['id' => 5, 'codigo' => '8.9', 'nombre' => 'Gestión de configuración', 'dominio' => 'Tecnológico',
     'objetivo' => 'Asegurar que la configuración e integridad estructural de la base de datos esté correctamente definida y controlada.',
     'peso' => 2, 'w' => ['C' => 0, 'I' => 3, 'D' => 1],
     'preguntas' => [
        ['id' => 2, 'texto' => '¿La BD tiene integridad referencial (llaves foráneas, constraints, triggers)?'],
        ['id' => 9, 'texto' => '¿El motor garantiza transacciones ACID?'],
     ]],

    ['id' => 6, 'codigo' => '8.15', 'nombre' => 'Registro de eventos (logging)', 'dominio' => 'Tecnológico',
     'objetivo' => 'Permitir la trazabilidad de quién modifica los datos y cuándo, para fines de auditoría e integridad.',
     'peso' => 2, 'w' => ['C' => 2, 'I' => 3, 'D' => 0],
     'preguntas' => [
        ['id' => 3, 'texto' => '¿Se registra en bitácora quién modifica los datos?'],
     ]],

    ['id' => 7, 'codigo' => '8.16', 'nombre' => 'Monitoreo de actividades', 'dominio' => 'Tecnológico',
     'objetivo' => 'Detectar oportunamente inconsistencias o fallas del servicio mediante revisión y alertas activas.',
     'peso' => 2, 'w' => ['C' => 0, 'I' => 3, 'D' => 3],
     'preguntas' => [
        ['id' => 10, 'texto' => '¿Se revisa periódicamente la consistencia referencial?'],
        ['id' => 16, 'texto' => '¿Hay alertas automáticas ante caídas del servicio?'],
     ]],

    ['id' => 8, 'codigo' => '8.6', 'nombre' => 'Gestión de capacidad', 'dominio' => 'Tecnológico',
     'objetivo' => 'Anticipar la saturación de recursos y mantener el rendimiento del motor de base de datos mediante mantenimiento programado.',
     'peso' => 2, 'w' => ['C' => 0, 'I' => 2, 'D' => 3],
     'preguntas' => [
        ['id' => 13, 'texto' => '¿Se monitorea capacidad (disco, conexiones, rendimiento)?'],
        ['id' => 15, 'texto' => '¿Se hace mantenimiento preventivo (reindexar, actualizar estadísticas)?'],
     ]],

    ['id' => 9, 'codigo' => '8.13', 'nombre' => 'Respaldo de información', 'dominio' => 'Tecnológico',
     'objetivo' => 'Garantizar que existan copias de seguridad confiables y que su restauración funcione correctamente.',
     'peso' => 3, 'w' => ['C' => 0, 'I' => 3, 'D' => 3],
     'preguntas' => [
        ['id' => 4,  'texto' => '¿Se validan los respaldos con checksums o firmas?'],
        ['id' => 11, 'texto' => '¿Hay backups programados con pruebas de restauración?'],
     ]],

    ['id' => 10, 'codigo' => '8.14', 'nombre' => 'Redundancia', 'dominio' => 'Tecnológico',
     'objetivo' => 'Evitar un punto único de falla mediante mecanismos de alta disponibilidad.',
     'peso' => 2, 'w' => ['C' => 0, 'I' => 1, 'D' => 3],
     'preguntas' => [
        ['id' => 12, 'texto' => '¿Existe redundancia/replicación/alta disponibilidad?'],
     ]],

    ['id' => 11, 'codigo' => '5.30', 'nombre' => 'Continuidad de TI', 'dominio' => 'Organizacional',
     'objetivo' => 'Asegurar la disponibilidad del servicio de base de datos ante desastres, respaldado por un compromiso formal de tiempo de actividad.',
     'peso' => 3, 'w' => ['C' => 0, 'I' => 1, 'D' => 3],
     'preguntas' => [
        ['id' => 14, 'texto' => '¿Hay un plan de recuperación ante desastres (DRP) para la BD?'],
        ['id' => 18, 'texto' => '¿Hay un SLA de tiempo de actividad definido?'],
     ]],

    ['id' => 12, 'codigo' => '8.20', 'nombre' => 'Seguridad de redes', 'dominio' => 'Tecnológico',
     'objetivo' => 'Impedir que el servidor de base de datos quede expuesto a redes no confiables.',
     'peso' => 2, 'w' => ['C' => 3, 'I' => 0, 'D' => 2],
     'preguntas' => [
        ['id' => 17, 'texto' => '¿El servidor está segmentado de redes no confiables?'],
     ]],

    ['id' => 13, 'codigo' => '8.24', 'nombre' => 'Uso de criptografía', 'dominio' => 'Tecnológico',
     'objetivo' => 'Proteger la confidencialidad de la información sensible mediante cifrado en reposo y en tránsito.',
     'peso' => 3, 'w' => ['C' => 3, 'I' => 1, 'D' => 0],
     'preguntas' => [
        ['id' => 20, 'texto' => '¿Los datos sensibles se cifran en reposo y tránsito?'],
     ]],

    ['id' => 14, 'codigo' => '8.11', 'nombre' => 'Enmascaramiento de datos', 'dominio' => 'Tecnológico',
     'objetivo' => 'Evitar la exposición de datos reales sensibles en ambientes que no son de producción.',
     'peso' => 2, 'w' => ['C' => 3, 'I' => 0, 'D' => 0],
     'preguntas' => [
        ['id' => 21, 'texto' => '¿Los ambientes de prueba usan datos enmascarados?'],
     ]],

    ['id' => 15, 'codigo' => '8.12', 'nombre' => 'Prevención de fuga de datos', 'dominio' => 'Tecnológico',
     'objetivo' => 'Detectar o bloquear extracciones masivas no autorizadas de información sensible.',
     'peso' => 2, 'w' => ['C' => 3, 'I' => 1, 'D' => 0],
     'preguntas' => [
        ['id' => 26, 'texto' => '¿Hay controles anti fuga de datos (DLP) en exportaciones masivas?'],
     ]],

    ['id' => 16, 'codigo' => '5.12', 'nombre' => 'Clasificación de la información', 'dominio' => 'Organizacional',
     'objetivo' => 'Determinar el nivel de protección requerido según la sensibilidad de la información almacenada.',
     'peso' => 2, 'w' => ['C' => 3, 'I' => 1, 'D' => 0],
     'preguntas' => [
        ['id' => 22, 'texto' => '¿Hay clasificación de la información (pública/interna/confidencial)?'],
     ]],

    ['id' => 17, 'codigo' => '5.9', 'nombre' => 'Inventario de activos', 'dominio' => 'Organizacional',
     'objetivo' => 'Identificar qué tablas contienen información crítica para priorizar su protección.',
     'peso' => 1, 'w' => ['C' => 2, 'I' => 2, 'D' => 1],
     'preguntas' => [
        ['id' => 5, 'texto' => '¿Hay inventario de qué tablas tienen información crítica?'],
     ]],

    ['id' => 18, 'codigo' => '5.20', 'nombre' => 'Seguridad en acuerdos con proveedores', 'dominio' => 'Organizacional',
     'objetivo' => 'Asegurar que terceros con acceso a la base de datos se comprometan contractualmente a proteger la información.',
     'peso' => 1, 'w' => ['C' => 2, 'I' => 0, 'D' => 0],
     'preguntas' => [
        ['id' => 25, 'texto' => '¿Los terceros con acceso firman acuerdo de confidencialidad?'],
     ]],
];

$grupos = [
    'I' => 'Integridad',
    'D' => 'Disponibilidad',
    'C' => 'Confidencialidad',
];

$rangos_madurez = [
    ['min' => 0,  'max' => 0,   'nivel' => 0, 'label' => 'No existe'],
    ['min' => 1,  'max' => 20,  'nivel' => 1, 'label' => 'Informal'],
    ['min' => 21, 'max' => 45,  'nivel' => 2, 'label' => 'Parcial'],
    ['min' => 46, 'max' => 70,  'nivel' => 3, 'label' => 'Documentado'],
    ['min' => 71, 'max' => 90,  'nivel' => 4, 'label' => 'Implementado y supervisado'],
    ['min' => 91, 'max' => 100, 'nivel' => 5, 'label' => 'Mejora continua'],
];

$recomendaciones = [
    'D' => 'Se recomienda fortalecer mecanismos de respaldo, recuperación ante desastres, monitoreo y continuidad operativa.',
    'C' => 'Se recomienda fortalecer controles de acceso, autenticación, cifrado y protección de información sensible.',
    'I' => 'Se recomienda mejorar controles de cambios',
];