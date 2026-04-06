-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`hoja_ruta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`hoja_ruta` (
  `idhoja_ruta` INT NOT NULL,
  `tipo_correspondencia` ENUM('interno', 'externo') NULL,
  `emision_recepcion` DATETIME NULL,
  `cant_hojas_anexos` INT NULL,
  `nro_registro_correlativo` INT NULL,
  `referencia` VARCHAR(250) NULL,
  `entrega` DATETIME NULL,
  `salida` DATETIME NULL,
  `estado` ENUM('en proceso','archivado','retrasado') NULL,
  `observacion` TEXT NULL,
  `plazo` INT NOT NULL DEFAULT 30,
  PRIMARY KEY (`idhoja_ruta`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`Unidad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Unidad` (
  `idUnidad` INT NOT NULL AUTO_INCREMENT,
  `nombre_area` VARCHAR(120) NULL,
  PRIMARY KEY (`idUnidad`)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`usuario` (
  `idusuario` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(45) NULL,
  `descripcion` VARCHAR(150) NULL,
  `estado` ENUM('activo', 'externo', 'inactivo') NULL,
  `cargo` VARCHAR(150) NULL,
  `id_unidad` INT NOT NULL,
  `contrasena` VARCHAR(100) NOT NULL,
  `rol` ENUM('administrador', 'auditor', 'encargado', 'dependiente') NOT NULL DEFAULT 'dependiente',
  PRIMARY KEY (`idusuario`),
  INDEX `fk_usuario_Unidad_idx` (`id_unidad` ASC) VISIBLE,
  CONSTRAINT `fk_usuario_Unidad`
    FOREIGN KEY (`id_unidad`)
    REFERENCES `mydb`.`Unidad` (`idUnidad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`derivaciones`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`derivaciones` (
  `idderivaciones` INT NOT NULL AUTO_INCREMENT,
  `idhoja_ruta` INT NOT NULL,
  `remitente` INT NOT NULL,
  `destinatario` INT NOT NULL,
  `nro_registro_interno` INT NULL,
  `ingreso` DATETIME NULL,
  `salida` DATETIME NULL,
  `instructivo_proveido` VARCHAR(255) NULL,
  PRIMARY KEY (`idderivaciones`),
  INDEX `fk_derivaciones_hoja_ruta_idx` (`idhoja_ruta` ASC),
  INDEX `fk_derivaciones_remitente_idx` (`remitente` ASC),
  INDEX `fk_derivaciones_destinatario_idx` (`destinatario` ASC),
  CONSTRAINT `fk_derivaciones_hoja_ruta`
    FOREIGN KEY (`idhoja_ruta`)
    REFERENCES `mydb`.`hoja_ruta` (`idhoja_ruta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_derivaciones_remitente`
    FOREIGN KEY (`remitente`)
    REFERENCES `mydb`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_derivaciones_destinatario`
    FOREIGN KEY (`destinatario`)
    REFERENCES `mydb`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;