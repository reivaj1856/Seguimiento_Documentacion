DELIMITER $$

CREATE PROCEDURE sp_get_hoja_ruta(
    IN p_idhoja_ruta INT
)
BEGIN
    SELECT
        hr.*,
        u_remitente.usuario AS remitente_usuario,
        u_remitente.descripcion AS remitente_descripcion,
        u_remitente.cargo AS remitente_cargo,
        u_remitente.estado AS remitente_estado,
        u_destinatario.usuario AS destinatario_usuario,
        u_destinatario.descripcion AS destinatario_descripcion,
        u_destinatario.cargo AS destinatario_cargo,
        u_destinatario.estado AS destinatario_estado
    FROM hoja_ruta hr
    LEFT JOIN derivaciones d ON d.idhoja_ruta = hr.idhoja_ruta AND d.idderivaciones = (
        SELECT MIN(idderivaciones) FROM derivaciones WHERE idhoja_ruta = p_idhoja_ruta
    )
    LEFT JOIN usuario u_remitente ON d.remitente = u_remitente.idusuario
    LEFT JOIN usuario u_destinatario ON d.destinatario = u_destinatario.idusuario
    WHERE hr.idhoja_ruta = p_idhoja_ruta;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE sp_get_derivaciones_hoja_ruta(
    IN p_idhoja_ruta INT
)
BEGIN
    SELECT
        d.*,
        u_remitente.usuario AS remitente_usuario,
        u_remitente.descripcion AS remitente_descripcion,
        u_remitente.cargo AS remitente_cargo,
        u_destinatario.usuario AS destinatario_usuario,
        u_destinatario.descripcion AS destinatario_descripcion,
        u_destinatario.cargo AS destinatario_cargo
    FROM derivaciones d
    LEFT JOIN usuario u_remitente ON d.remitente = u_remitente.idusuario
    LEFT JOIN usuario u_destinatario ON d.destinatario = u_destinatario.idusuario
    WHERE d.idhoja_ruta = p_idhoja_ruta
    ORDER BY d.idderivaciones;
END$$

DELIMITER ;