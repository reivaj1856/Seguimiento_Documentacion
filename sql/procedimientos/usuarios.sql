use mydb;

DROP PROCEDURE IF EXISTS sp_insertar_usuario;
DELIMITER $$
CREATE PROCEDURE sp_insertar_usuario(
    IN p_usuario VARCHAR(45),
    IN p_descripcion VARCHAR(150),
    IN p_estado ENUM('activo', 'externo', 'inactivo'),
    IN p_cargo VARCHAR(150),
    IN p_id_unidad INT,
    IN p_contrasena VARCHAR(100)
)
BEGIN
    INSERT INTO usuario (usuario, descripcion, estado, cargo, id_unidad, contrasena)
    VALUES (p_usuario, p_descripcion, p_estado, p_cargo, p_id_unidad, p_contrasena);
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_editar_usuario;
DELIMITER $$
CREATE PROCEDURE sp_editar_usuario(
    IN p_idusuario INT,
    IN p_usuario VARCHAR(45),
    IN p_descripcion VARCHAR(150),
    IN p_estado ENUM('activo', 'externo', 'inactivo'),
    IN p_cargo VARCHAR(150),
    IN p_id_unidad INT,
    IN p_contrasena VARCHAR(100)
)
BEGIN
    UPDATE usuario
    SET usuario = p_usuario,
        descripcion = p_descripcion,
        estado = p_estado,
        cargo = p_cargo,
        id_unidad = p_id_unidad,
        contrasena = p_contrasena
    WHERE idusuario = p_idusuario;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_eliminar_usuario(
    IN p_idusuario INT
)
BEGIN
    DELETE FROM usuario WHERE idusuario = p_idusuario;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_mostrar_cargo_por_usuario(
    IN p_idusuario INT
)
BEGIN
    SELECT cargo FROM usuario WHERE idusuario = p_idusuario;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_buscar_usuario_por_descripcion(
    IN p_texto VARCHAR(150)
)
BEGIN
    SELECT * FROM usuario
    WHERE descripcion LIKE CONCAT('%', p_texto, '%');
END $$
DELIMITER ;