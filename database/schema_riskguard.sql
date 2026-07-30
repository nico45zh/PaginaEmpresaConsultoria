-- ============================================================
-- RiskGuard Consulting
-- Evaluación de Riesgo en la Administración de Bases de Datos
-- basada en ISO/IEC 27002
--
-- Motor: MySQL / MariaDB (compatible con InfinityFree)
-- ============================================================


-- ============================================================
-- 1. USUARIOS
-- Usuarios del sistema (login único, sin distinción de roles).
-- ============================================================
CREATE TABLE usuarios (
    id_usuario        INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo    VARCHAR(150)  NOT NULL,
    correo             VARCHAR(150)  NOT NULL UNIQUE,
    contrasena_hash    VARCHAR(255)  NOT NULL,
    activo             TINYINT(1)    NOT NULL DEFAULT 1,
    fecha_creacion     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 2. ORGANIZACIONES
-- Empresas u organizaciones que son auditadas.
-- ============================================================
CREATE TABLE organizaciones (
    id_organizacion    INT AUTO_INCREMENT PRIMARY KEY,
    nombre             VARCHAR(150)  NOT NULL,
    sector             VARCHAR(100)  NULL,
    contacto_nombre    VARCHAR(150)  NULL,
    contacto_correo    VARCHAR(150)  NULL,
    fecha_registro     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 3. DOMINIOS
-- Dominios/categorías de la norma ISO/IEC 27002 seleccionados
-- por el equipo (ej. Organizacionales, Tecnológicos, etc.)
-- ============================================================
CREATE TABLE dominios (
    id_dominio     INT AUTO_INCREMENT PRIMARY KEY,
    codigo         VARCHAR(10)   NOT NULL,
    nombre         VARCHAR(100)  NOT NULL
) ENGINE=InnoDB;

-- ============================================================
-- 4. CONTROLES
-- Controles de ISO/IEC 27002 seleccionados como aplicables
-- a la administración de bases de datos.
-- ============================================================
CREATE TABLE controles (
    id_control                 INT AUTO_INCREMENT PRIMARY KEY,
    id_dominio                 INT           NOT NULL,
    codigo                     VARCHAR(10)   NOT NULL,
    nombre                     VARCHAR(150)  NOT NULL,
    objetivo                   TEXT          NULL,
    descripcion                TEXT          NULL,
    peso                       DECIMAL(3,2)  NOT NULL DEFAULT 1.00,
    afecta_confidencialidad    TINYINT(1)    NOT NULL DEFAULT 0,
    afecta_integridad          TINYINT(1)    NOT NULL DEFAULT 0,
    afecta_disponibilidad      TINYINT(1)    NOT NULL DEFAULT 0,
    CONSTRAINT fk_control_dominio
        FOREIGN KEY (id_dominio) REFERENCES dominios(id_dominio)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ============================================================
-- 5. PREGUNTAS
-- Cada control se mide mediante una o varias preguntas.
-- ============================================================
CREATE TABLE preguntas (
    id_pregunta    INT AUTO_INCREMENT PRIMARY KEY,
    id_control     INT           NOT NULL,
    texto          VARCHAR(300)  NOT NULL,
    orden          INT           NOT NULL DEFAULT 1,
    CONSTRAINT fk_pregunta_control
        FOREIGN KEY (id_control) REFERENCES controles(id_control)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 6. AUDITORIAS
-- Una auditoría aplicada a una organización, por un auditor,
-- en una fecha determinada. estado controla si se puede
-- seguir editando (en_progreso) o ya quedó fija (finalizada).
-- ============================================================
CREATE TABLE auditorias (
    id_auditoria         INT AUTO_INCREMENT PRIMARY KEY,
    id_organizacion      INT           NOT NULL,
    id_usuario_auditor   INT           NOT NULL,
    area_evaluada        VARCHAR(150)  NULL,
    nombre_dba           VARCHAR(150)  NULL,
    fecha_auditoria      DATE          NOT NULL,
    estado               ENUM('en_progreso','finalizada') NOT NULL DEFAULT 'en_progreso',
    fecha_creacion       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_finalizacion   DATETIME      NULL,
    CONSTRAINT fk_auditoria_organizacion
        FOREIGN KEY (id_organizacion) REFERENCES organizaciones(id_organizacion)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_auditoria_usuario
        FOREIGN KEY (id_usuario_auditor) REFERENCES usuarios(id_usuario)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Solo puede existir una auditoría en_progreso por organización.
-- (La validación fuerte se hace también desde la aplicación antes
-- de insertar; este índice ayuda a la consulta rápida del estado.)
CREATE INDEX idx_auditoria_org_estado ON auditorias (id_organizacion, estado);

-- ============================================================
-- 7. RESPUESTAS
-- Respuesta del auditor a cada pregunta, dentro de una auditoría.
-- valor: 0 = No cumple, 1 = Cumple parcialmente, 2 = Cumple completamente
-- ============================================================
CREATE TABLE respuestas (
    id_respuesta      INT AUTO_INCREMENT PRIMARY KEY,
    id_auditoria       INT           NOT NULL,
    id_pregunta        INT           NOT NULL,
    valor              TINYINT       NULL,
    observacion        TEXT          NULL,
    evidencia          VARCHAR(255)  NULL,
    fecha_respuesta    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
                                       ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_respuesta_auditoria
        FOREIGN KEY (id_auditoria) REFERENCES auditorias(id_auditoria)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_respuesta_pregunta
        FOREIGN KEY (id_pregunta) REFERENCES preguntas(id_pregunta)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT uq_respuesta_auditoria_pregunta
        UNIQUE (id_auditoria, id_pregunta),
    CONSTRAINT chk_respuesta_valor
        CHECK (valor IS NULL OR valor IN (0,1,2))
) ENGINE=InnoDB;

-- ============================================================
-- 8. RESULTADOS_CONTROL
-- Madurez calculada por control, dentro de una auditoría.
-- ============================================================
CREATE TABLE resultados_control (
    id_resultado_control     INT AUTO_INCREMENT PRIMARY KEY,
    id_auditoria             INT           NOT NULL,
    id_control                INT           NOT NULL,
    porcentaje_cumplimiento   DECIMAL(5,2)  NOT NULL,
    nivel_madurez              TINYINT       NOT NULL,
    CONSTRAINT fk_resultctrl_auditoria
        FOREIGN KEY (id_auditoria) REFERENCES auditorias(id_auditoria)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_resultctrl_control
        FOREIGN KEY (id_control) REFERENCES controles(id_control)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT uq_resultctrl_auditoria_control
        UNIQUE (id_auditoria, id_control),
    CONSTRAINT chk_resultctrl_madurez
        CHECK (nivel_madurez BETWEEN 0 AND 5)
) ENGINE=InnoDB;

-- ============================================================
-- 9. RESULTADOS_AUDITORIA
-- Resumen final de exposición al riesgo por dimensión CID
-- y el índice general, una fila por auditoría finalizada.
-- ============================================================
CREATE TABLE resultados_auditoria (
    id_resultado_auditoria    INT AUTO_INCREMENT PRIMARY KEY,
    id_auditoria               INT           NOT NULL UNIQUE,
    riesgo_confidencialidad    DECIMAL(5,2)  NOT NULL,
    riesgo_integridad          DECIMAL(5,2)  NOT NULL,
    riesgo_disponibilidad      DECIMAL(5,2)  NOT NULL,
    indice_general_riesgo      DECIMAL(5,2)  NOT NULL,
    fecha_calculo               DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resultaud_auditoria
        FOREIGN KEY (id_auditoria) REFERENCES auditorias(id_auditoria)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Datos semilla mínimos de ejemplo (opcional, se puede borrar)
-- ============================================================
INSERT INTO dominios (codigo, nombre) VALUES
    ('5', 'Controles organizacionales'),
    ('8', 'Controles tecnológicos');

-- Un usuario de prueba (contraseña: "admin123" ya hasheada con password_hash)
-- Generar el hash real desde PHP antes de usar en producción.
