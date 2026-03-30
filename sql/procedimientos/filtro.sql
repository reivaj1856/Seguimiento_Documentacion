DROP PROCEDURE IF EXISTS sp_documentos_ejecutivos_filtrado;
DELIMITER $$

DELIMITER $$

CREATE PROCEDURE sp_documentos_ejecutivos_filtrado(
    IN p_desde DATETIME,
    IN p_hasta DATETIME,
    IN p_estado ENUM('en proceso','archivado','retrasado'),
    IN p_tipo_correspondencia ENUM('interno', 'externo'),
    IN p_idUnidad INT,
    IN p_busqueda_hoja_ruta VARCHAR(250),
    IN p_idusuario INT
)
BEGIN
    SELECT
        hr.*,
        -- Cantidad total de derivaciones
        (SELECT COUNT(*) FROM derivaciones d WHERE d.idhoja_ruta = hr.idhoja_ruta) AS cant_derivaciones,

        -- Primera derivación
        fd.idderivaciones AS primera_derivacion_id,
        fd.ingreso AS primera_derivacion_ingreso,
        fd.salida AS primera_derivacion_salida,
        fd.instructivo_proveido AS primera_derivacion_instructivo,
        u_remitente.descripcion AS primera_derivacion_remitente,
        u_destinatario.descripcion AS primera_derivacion_destinatario,

        -- Última derivación
        ld.idderivaciones AS ultima_derivacion_id,
        ld.ingreso AS ultima_derivacion_ingreso,
        ld.salida AS ultima_derivacion_salida,
        ld.instructivo_proveido AS ultima_derivacion_instructivo,
        u_remitente_ult.descripcion AS ultima_derivacion_remitente,
        u_destinatario_ult.descripcion AS ultima_derivacion_destinatario

    FROM hoja_ruta hr

    -- Primera derivación (ID mínimo para cada hoja_ruta)
    LEFT JOIN (
        SELECT
            d.idhoja_ruta,
            d.idderivaciones,
            d.ingreso,
            d.salida,
            d.instructivo_proveido,
            d.remitente,
            d.destinatario
        FROM derivaciones d
        INNER JOIN (
            SELECT idhoja_ruta, MIN(idderivaciones) AS min_idderivaciones
            FROM derivaciones
            GROUP BY idhoja_ruta
        ) dm ON d.idhoja_ruta = dm.idhoja_ruta AND d.idderivaciones = dm.min_idderivaciones
    ) fd ON fd.idhoja_ruta = hr.idhoja_ruta

    LEFT JOIN usuario u_remitente ON fd.remitente = u_remitente.idusuario
    LEFT JOIN usuario u_destinatario ON fd.destinatario = u_destinatario.idusuario

    -- Última derivación (ID máximo para cada hoja_ruta)
    LEFT JOIN (
        SELECT
            d.idhoja_ruta,
            d.idderivaciones,
            d.ingreso,
            d.salida,
            d.instructivo_proveido,
            d.remitente,
            d.destinatario
        FROM derivaciones d
        INNER JOIN (
            SELECT idhoja_ruta, MAX(idderivaciones) AS max_idderivaciones
            FROM derivaciones
            GROUP BY idhoja_ruta
        ) dm ON d.idhoja_ruta = dm.idhoja_ruta AND d.idderivaciones = dm.max_idderivaciones
    ) ld ON ld.idhoja_ruta = hr.idhoja_ruta

    LEFT JOIN usuario u_remitente_ult ON ld.remitente = u_remitente_ult.idusuario
    LEFT JOIN usuario u_destinatario_ult ON ld.destinatario = u_destinatario_ult.idusuario

    -- Filtros
    WHERE
        (p_desde IS NULL OR hr.emision_recepcion >= p_desde)
        AND (p_hasta IS NULL OR hr.emision_recepcion <= p_hasta)
        AND (p_estado IS NULL OR hr.estado = p_estado)
        AND (p_tipo_correspondencia IS NULL OR hr.tipo_correspondencia = p_tipo_correspondencia)
        AND (p_idUnidad IS NULL OR EXISTS (
                SELECT 1 FROM usuario u2 WHERE u2.id_unidad = p_idUnidad AND u2.idusuario = fd.remitente))
        AND (p_busqueda_hoja_ruta IS NULL OR hr.idhoja_ruta LIKE CONCAT('%', p_busqueda_hoja_ruta, '%'))
        -- Si idusuario está presente, el destinatario en la última derivación debe ser ese usuario
        AND (
            p_idusuario IS NULL
            OR ld.destinatario = p_idusuario
        )
    ;

END$$

DELIMITER ;