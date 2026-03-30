DELIMITER $$
CREATE PROCEDURE sp_mostrar_unidades()
BEGIN
    SELECT * FROM Unidad;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_mostrar_usuarios_por_unidad(IN p_idUnidad INT)
BEGIN
    SELECT * FROM usuario
    WHERE id_unidad = p_idUnidad;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_editar_unidad(
    IN p_idUnidad INT,
    IN p_nombre_area VARCHAR(120)
)
BEGIN
    UPDATE Unidad
    SET nombre_area = p_nombre_area
    WHERE idUnidad = p_idUnidad;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_eliminar_area(
    IN p_idUnidad INT
)
BEGIN
    -- Elimina los usuarios de esa unidad
    DELETE FROM usuario WHERE id_unidad = p_idUnidad;
    -- Elimina la unidad
    DELETE FROM Unidad WHERE idUnidad = p_idUnidad;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_insertar_unidad(
    IN p_nombre_area VARCHAR(120)
)
BEGIN
    INSERT INTO Unidad(nombre_area)
    VALUES (p_nombre_area);
END $$
DELIMITER ;