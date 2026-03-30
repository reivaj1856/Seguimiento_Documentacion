use mydb;

DROP PROCEDURE IF EXISTS sp_insertar_hoja_ruta;
DELIMITER $$
CREATE PROCEDURE sp_insertar_hoja_ruta(
    IN p_idhoja_ruta INT,
    IN p_tipo_correspondencia ENUM('interno', 'externo'),
    IN p_emision_recepcion DATETIME,
    IN p_cant_hojas_anexos INT,
    IN p_nro_registro_correlativo INT,
    IN p_referencia VARCHAR(250),
    IN p_entrega DATETIME,
    IN p_salida DATETIME,
    IN p_estado ENUM('en proceso','archivado','retrasado'),

    -- Primera derivación
    IN p_remitente INT,
    IN p_destinatario INT,
    IN p_nro_registro_interno INT,
    IN p_ingreso DATETIME,
    IN p_salida_derivacion DATETIME,
    IN p_instructivo_proveido VARCHAR(255)
)
BEGIN
    INSERT INTO hoja_ruta (
        idhoja_ruta,
        tipo_correspondencia,
        emision_recepcion,
        cant_hojas_anexos,
        nro_registro_correlativo,
        referencia,
        entrega,
        salida,
        estado
    ) VALUES (
        p_idhoja_ruta,
        p_tipo_correspondencia,
        p_emision_recepcion,
        p_cant_hojas_anexos,
        p_nro_registro_correlativo,
        p_referencia,
        p_entrega,
        p_salida,
        p_estado
    );

    INSERT INTO derivaciones (
        hoja_ruta_idhoja_ruta,
        remitente,
        destinatario,
        nro_registro_interno,
        ingreso,
        salida,
        instructivo_proveido
    ) VALUES (
        p_idhoja_ruta,
        p_remitente,
        p_destinatario,
        p_nro_registro_interno,
        p_ingreso,
        p_salida_derivacion,
        p_instructivo_proveido
    );
END $$
DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_eliminar_hoja_ruta(
    IN p_idhoja_ruta INT
)
BEGIN
    -- Eliminar las derivaciones asociadas
    DELETE FROM derivaciones WHERE idhoja_ruta = p_idhoja_ruta;
    
    -- Eliminar la hoja de ruta principal
    DELETE FROM hoja_ruta WHERE idhoja_ruta = p_idhoja_ruta;
END$$

DELIMITER ;