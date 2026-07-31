-- ============================================================
-- Carga de dominios, controles y preguntas
-- Basado en las 26 preguntas ya diseñadas por el equipo,
-- agrupadas en controles reales de ISO/IEC 27002:2022
-- ============================================================

-- Completar dominios (5 y 8 ya deberían existir desde schema_riskguard.sql)
INSERT INTO dominios (codigo, nombre)
SELECT '6', 'Controles de personas'
WHERE NOT EXISTS (SELECT 1 FROM dominios WHERE codigo = '6');

-- ============================================================
-- CONTROLES
-- ============================================================

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.32', 'Gestión de cambios', 'Controlar los cambios en la base de datos mediante un proceso formal.', 3.00, 1, 1, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.28', 'Integridad y validación de datos', 'Asegurar la integridad referencial y transaccional de los datos.', 3.00, 0, 1, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.15', 'Registro y monitoreo (logging)', 'Registrar en bitácora las modificaciones realizadas a los datos.', 2.50, 1, 1, 0 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.13', 'Copias de seguridad', 'Garantizar respaldos periódicos y verificables de la información.', 3.00, 0, 1, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '5.9', 'Inventario de activos de información', 'Mantener inventario de las tablas con información crítica.', 2.00, 1, 1, 1 FROM dominios WHERE codigo='5';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.2', 'Gestión de accesos privilegiados', 'Restringir privilegios de escritura y administración a perfiles autorizados.', 3.00, 1, 1, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.5', 'Autenticación segura', 'Exigir autenticación individual y multifactor para accesos sensibles.', 3.00, 1, 1, 0 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.24', 'Uso de criptografía', 'Cifrar los datos sensibles en reposo y en tránsito.', 3.00, 1, 1, 0 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.11', 'Enmascaramiento de datos', 'Enmascarar datos sensibles en ambientes de prueba.', 2.00, 1, 0, 0 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '5.12', 'Clasificación de la información', 'Clasificar la información según su nivel de sensibilidad.', 2.00, 1, 1, 0 FROM dominios WHERE codigo='5';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '6.6', 'Acuerdos de confidencialidad', 'Formalizar acuerdos de confidencialidad con terceros con acceso a datos.', 1.50, 1, 0, 0 FROM dominios WHERE codigo='6';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.12', 'Prevención de fuga de datos (DLP)', 'Controlar la exportación masiva de información sensible.', 2.50, 1, 1, 0 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.14', 'Redundancia de instalaciones de procesamiento', 'Garantizar redundancia y niveles de servicio definidos.', 3.00, 0, 0, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.6', 'Gestión de capacidad', 'Monitorear capacidad y generar alertas ante caídas del servicio.', 2.50, 0, 0, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '5.30', 'Continuidad de TIC', 'Contar con un plan de recuperación ante desastres para la base de datos.', 3.00, 0, 1, 1 FROM dominios WHERE codigo='5';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.8', 'Gestión de vulnerabilidades técnicas', 'Realizar mantenimiento preventivo periódico de la base de datos.', 2.00, 0, 1, 1 FROM dominios WHERE codigo='8';

INSERT INTO controles (id_dominio, codigo, nombre, objetivo, peso, afecta_confidencialidad, afecta_integridad, afecta_disponibilidad)
SELECT id_dominio, '8.20', 'Seguridad de redes', 'Segmentar el servidor de base de datos de redes no confiables.', 2.50, 1, 0, 1 FROM dominios WHERE codigo='8';

-- ============================================================
-- PREGUNTAS (ligadas a cada control por su código)
-- ============================================================

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Existe control de cambios formal antes de modificar tablas/índices de la BD?', 1 FROM controles WHERE codigo='8.32';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay segregación entre quien programa un cambio y quien lo aprueba?', 2 FROM controles WHERE codigo='8.32';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿La BD tiene integridad referencial (llaves foráneas, constraints, triggers)?', 1 FROM controles WHERE codigo='8.28';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿El motor garantiza transacciones ACID?', 2 FROM controles WHERE codigo='8.28';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se revisa periódicamente la consistencia referencial?', 3 FROM controles WHERE codigo='8.28';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se registra en bitácora quién modifica los datos?', 1 FROM controles WHERE codigo='8.15';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se validan los respaldos con checksums o firmas?', 1 FROM controles WHERE codigo='8.13';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay backups programados con pruebas de restauración?', 2 FROM controles WHERE codigo='8.13';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay inventario de qué tablas tienen información crítica?', 1 FROM controles WHERE codigo='5.9';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Los privilegios de escritura están restringidos a perfiles autorizados?', 1 FROM controles WHERE codigo='8.2';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿El acceso es por roles con privilegio mínimo (RBAC)?', 2 FROM controles WHERE codigo='8.2';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Están segregadas las funciones de DBA/dev/usuario final?', 3 FROM controles WHERE codigo='8.2';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se exige autenticación individual (no usuarios compartidos)?', 1 FROM controles WHERE codigo='8.5';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se exige autenticación multifactor para datos sensibles?', 2 FROM controles WHERE codigo='8.5';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Los datos sensibles se cifran en reposo y tránsito?', 1 FROM controles WHERE codigo='8.24';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Los ambientes de prueba usan datos enmascarados?', 1 FROM controles WHERE codigo='8.11';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay clasificación de la información (pública/interna/confidencial)?', 1 FROM controles WHERE codigo='5.12';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Los terceros con acceso firman acuerdo de confidencialidad?', 1 FROM controles WHERE codigo='6.6';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay controles anti fuga de datos (DLP) en exportaciones masivas?', 1 FROM controles WHERE codigo='8.12';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Existe redundancia/replicación/alta disponibilidad?', 1 FROM controles WHERE codigo='8.14';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay un SLA de tiempo de actividad definido?', 2 FROM controles WHERE codigo='8.14';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se monitorea capacidad (disco, conexiones, rendimiento)?', 1 FROM controles WHERE codigo='8.6';
INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay alertas automáticas ante caídas del servicio?', 2 FROM controles WHERE codigo='8.6';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Hay un plan de recuperación ante desastres (DRP) para la BD?', 1 FROM controles WHERE codigo='5.30';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿Se hace mantenimiento preventivo (reindexar, actualizar estadísticas)?', 1 FROM controles WHERE codigo='8.8';

INSERT INTO preguntas (id_control, texto, orden)
SELECT id_control, '¿El servidor está segmentado de redes no confiables?', 1 FROM controles WHERE codigo='8.20';
