<?php
/**
 * Catálogo del Módulo de Evaluación de Riesgos.
 * Basado en controles reales de ISO/IEC 27002:2022 aplicables a la
 * administración de bases de datos. Cada control agrupa 1 o varias
 * preguntas (Sí / No / No aplica). El peso_c/peso_i/peso_d representa
 * el nivel de contribución del control a cada dimensión de la triada
 * (0 = sin influencia ... 3 = alta), y "peso" es la importancia relativa
 * del control dentro de su dimensión dominante (1-3).
 */

$dominios = [
    'ORG' => 'Organizacional',
    'TEC' => 'Tecnológico',
];

$controles = [
    [ 'id' => 1,  'codigo' => '5.15', 'nombre' => 'Control de acceso',
      'dominio' => 'ORG', 'grupo' => 'C', 'peso' => 3,
      'peso_c' => 2.5, 'peso_i' => 2.5, 'peso_d' => 0.5,
      'objetivo' => 'Asegurar el acceso autorizado y prevenir el acceso no autorizado a la base de datos.',
      'preguntas' => [
        ['id' => 19, 'texto' => '¿El acceso es por roles con privilegio mínimo (RBAC)?'],
        ['id' => 6,  'texto' => '¿Los privilegios de escritura están restringidos a perfiles autorizados?'],
      ]],
    [ 'id' => 2,  'codigo' => '8.5', 'nombre' => 'Autenticación segura',
      'dominio' => 'TEC', 'grupo' => 'C', 'peso' => 3,
      'peso_c' => 3, 'peso_i' => 1, 'peso_d' => 0,
      'objetivo' => 'Garantizar procedimientos de autenticación seguros de acuerdo con la política de control de acceso.',
      'preguntas' => [
        ['id' => 7,  'texto' => '¿Se exige autenticación individual (no usuarios compartidos)?'],
        ['id' => 24, 'texto' => '¿Se exige autenticación multifactor para datos sensibles?'],
      ]],
    [ 'id' => 3,  'codigo' => '5.3', 'nombre' => 'Segregación de funciones',
      'dominio' => 'ORG', 'grupo' => 'I', 'peso' => 2,
      'peso_c' => 1.5, 'peso_i' => 2.5, 'peso_d' => 0,
      'objetivo' => 'Separar funciones y áreas de responsabilidad en conflicto para reducir el riesgo de modificaciones no autorizadas.',
      'preguntas' => [
        ['id' => 8,  'texto' => '¿Hay segregación entre quien programa un cambio y quien lo aprueba?'],
        ['id' => 23, 'texto' => '¿Están segregadas las funciones de DBA/dev/usuario final?'],
      ]],
    [ 'id' => 4,  'codigo' => '8.32', 'nombre' => 'Gestión de cambios',
      'dominio' => 'TEC', 'grupo' => 'I', 'peso' => 3,
      'peso_c' => 1, 'peso_i' => 3, 'peso_d' => 1,
      'objetivo' => 'Someter los cambios a las instalaciones de procesamiento a procedimientos de control de cambios.',
      'preguntas' => [
        ['id' => 1,  'texto' => '¿Existe control de cambios formal antes de modificar tablas/índices de la BD?'],
      ]],
    [ 'id' => 5,  'codigo' => '8.9', 'nombre' => 'Gestión de configuración',
      'dominio' => 'TEC', 'grupo' => 'I', 'peso' => 2,
      'peso_c' => 0, 'peso_i' => 3, 'peso_d' => 1,
      'objetivo' => 'Establecer, documentar, implementar y monitorear las configuraciones de seguridad.',
      'preguntas' => [
        ['id' => 2,  'texto' => '¿La BD tiene integridad referencial (llaves foráneas, constraints, triggers)?'],
        ['id' => 9,  'texto' => '¿El motor garantiza transacciones ACID?'],
      ]],
    [ 'id' => 6,  'codigo' => '8.15', 'nombre' => 'Registro de eventos (logging)',
      'dominio' => 'TEC', 'grupo' => 'I', 'peso' => 2,
      'peso_c' => 2, 'peso_i' => 3, 'peso_d' => 0,
      'objetivo' => 'Producir, almacenar, proteger y analizar registros de eventos.',
      'preguntas' => [
        ['id' => 3,  'texto' => '¿Se registra en bitácora quién modifica los datos?'],
      ]],
    [ 'id' => 7,  'codigo' => '8.16', 'nombre' => 'Monitoreo de actividades',
      'dominio' => 'TEC', 'grupo' => 'D', 'peso' => 2,
      'peso_c' => 0, 'peso_i' => 1, 'peso_d' => 2.3,
      'objetivo' => 'Monitorear redes, sistemas y aplicaciones para detectar comportamiento anómalo.',
      'preguntas' => [
        ['id' => 10, 'texto' => '¿Se revisa periódicamente la consistencia referencial?'],
        ['id' => 13, 'texto' => '¿Se monitorea capacidad (disco, conexiones, rendimiento)?'],
        ['id' => 16, 'texto' => '¿Hay alertas automáticas ante caídas del servicio?'],
      ]],
    [ 'id' => 8,  'codigo' => '8.13', 'nombre' => 'Respaldo de información',
      'dominio' => 'TEC', 'grupo' => 'D', 'peso' => 3,
      'peso_c' => 0, 'peso_i' => 2, 'peso_d' => 2.5,
      'objetivo' => 'Mantener copias de respaldo probadas de la información, software y sistemas.',
      'preguntas' => [
        ['id' => 4,  'texto' => '¿Se validan los respaldos con checksums o firmas?'],
        ['id' => 11, 'texto' => '¿Hay backups programados con pruebas de restauración?'],
      ]],
    [ 'id' => 9,  'codigo' => '8.14', 'nombre' => 'Redundancia',
      'dominio' => 'TEC', 'grupo' => 'D', 'peso' => 2,
      'peso_c' => 0, 'peso_i' => 1, 'peso_d' => 3,
      'objetivo' => 'Implementar redundancia suficiente para cumplir los requisitos de disponibilidad.',
      'preguntas' => [
        ['id' => 12, 'texto' => '¿Existe redundancia/replicación/alta disponibilidad?'],
      ]],
    [ 'id' => 10, 'codigo' => '5.30', 'nombre' => 'Continuidad de TI',
      'dominio' => 'ORG', 'grupo' => 'D', 'peso' => 3,
      'peso_c' => 0, 'peso_i' => 1, 'peso_d' => 3,
      'objetivo' => 'Planificar, implementar, mantener y probar la continuidad de TI ante disrupciones.',
      'preguntas' => [
        ['id' => 14, 'texto' => '¿Hay un plan de recuperación ante desastres (DRP) para la BD?'],
      ]],
    [ 'id' => 11, 'codigo' => '8.20', 'nombre' => 'Seguridad de redes',
      'dominio' => 'TEC', 'grupo' => 'C', 'peso' => 2,
      'peso_c' => 3, 'peso_i' => 0, 'peso_d' => 2,
      'objetivo' => 'Asegurar y gestionar las redes para proteger la información en sistemas y aplicaciones.',
      'preguntas' => [
        ['id' => 17, 'texto' => '¿El servidor está segmentado de redes no confiables?'],
      ]],
    [ 'id' => 12, 'codigo' => '8.24', 'nombre' => 'Uso de criptografía',
      'dominio' => 'TEC', 'grupo' => 'C', 'peso' => 3,
      'peso_c' => 3, 'peso_i' => 1, 'peso_d' => 0,
      'objetivo' => 'Definir y aplicar reglas para el uso efectivo de la criptografía.',
      'preguntas' => [
        ['id' => 20, 'texto' => '¿Los datos sensibles se cifran en reposo y tránsito?'],
      ]],
    [ 'id' => 13, 'codigo' => '8.11', 'nombre' => 'Enmascaramiento de datos',
      'dominio' => 'TEC', 'grupo' => 'C', 'peso' => 2,
      'peso_c' => 3, 'peso_i' => 0, 'peso_d' => 0,
      'objetivo' => 'Usar enmascaramiento de datos para limitar la exposición de información sensible.',
      'preguntas' => [
        ['id' => 21, 'texto' => '¿Los ambientes de prueba usan datos enmascarados?'],
      ]],
    [ 'id' => 14, 'codigo' => '8.12', 'nombre' => 'Prevención de fuga de datos',
      'dominio' => 'TEC', 'grupo' => 'C', 'peso' => 2,
      'peso_c' => 3, 'peso_i' => 1, 'peso_d' => 0,
      'objetivo' => 'Aplicar medidas de prevención de fuga de datos (DLP) a sistemas que procesan información sensible.',
      'preguntas' => [
        ['id' => 26, 'texto' => '¿Hay controles anti fuga de datos (DLP) en exportaciones masivas?'],
      ]],
    [ 'id' => 15, 'codigo' => '5.12', 'nombre' => 'Clasificación de la información',
      'dominio' => 'ORG', 'grupo' => 'C', 'peso' => 2,
      'peso_c' => 3, 'peso_i' => 1, 'peso_d' => 0,
      'objetivo' => 'Clasificar la información según los requisitos de confidencialidad, integridad y disponibilidad.',
      'preguntas' => [
        ['id' => 22, 'texto' => '¿Hay clasificación de la información (pública/interna/confidencial)?'],
      ]],
    [ 'id' => 16, 'codigo' => '5.9', 'nombre' => 'Inventario de activos',
      'dominio' => 'ORG', 'grupo' => 'C', 'peso' => 1,
      'peso_c' => 2, 'peso_i' => 2, 'peso_d' => 1,
      'objetivo' => 'Desarrollar y mantener un inventario de activos de información.',
      'preguntas' => [
        ['id' => 5,  'texto' => '¿Hay inventario de qué tablas tienen información crítica?'],
      ]],
    [ 'id' => 17, 'codigo' => '5.20', 'nombre' => 'Seguridad en acuerdos con proveedores',
      'dominio' => 'ORG', 'grupo' => 'C', 'peso' => 1,
      'peso_c' => 2, 'peso_i' => 0, 'peso_d' => 0,
      'objetivo' => 'Establecer y acordar requisitos de seguridad con proveedores según el tipo de relación.',
      'preguntas' => [
        ['id' => 25, 'texto' => '¿Los terceros con acceso firman acuerdo de confidencialidad?'],
      ]],
    [ 'id' => 18, 'codigo' => '8.6', 'nombre' => 'Gestión de capacidad',
      'dominio' => 'TEC', 'grupo' => 'D', 'peso' => 2,
      'peso_c' => 0, 'peso_i' => 2, 'peso_d' => 2,
      'objetivo' => 'Monitorear y ajustar el uso de recursos según proyecciones de capacidad futura.',
      'preguntas' => [
        ['id' => 15, 'texto' => '¿Se hace mantenimiento preventivo (reindexar, actualizar estadísticas)?'],
      ]],
    [ 'id' => 19, 'codigo' => '5.22', 'nombre' => 'Gestión de servicios de proveedores',
      'dominio' => 'ORG', 'grupo' => 'D', 'peso' => 2,
      'peso_c' => 0, 'peso_i' => 0, 'peso_d' => 3,
      'objetivo' => 'Monitorear y revisar regularmente los servicios prestados por proveedores según acuerdos definidos.',
      'preguntas' => [
        ['id' => 18, 'texto' => '¿Hay un SLA de tiempo de actividad definido?'],
      ]],
];

$opciones_respuesta = [
    'si' => 'Sí',
    'no' => 'No',
    'na' => 'No aplica',
];

$niveles_madurez = [
    0 => 'No existe',
    1 => 'Informal',
    2 => 'Parcial',
    3 => 'Documentado',
    4 => 'Implementado y supervisado',
    5 => 'Mejora continua',
];

$recomendaciones = [
    'D' => 'Se recomienda fortalecer mecanismos de respaldo, recuperación ante desastres, monitoreo y continuidad operativa.',
    'C' => 'Se recomienda fortalecer controles de acceso, autenticación, cifrado y protección de información sensible.',
    'I' => 'Se recomienda mejorar controles de cambios, auditoría, validación de datos y segregación de funciones.',
];

$grupos = ['C' => 'Confidencialidad', 'I' => 'Integridad', 'D' => 'Disponibilidad'];

