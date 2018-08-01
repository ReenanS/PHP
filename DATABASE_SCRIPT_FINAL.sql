-- MySQL Script generated by MySQL Workbench
-- Wed Aug  1 00:02:37 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema NaN
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `NaN` ;

-- -----------------------------------------------------
-- Schema NaN
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `NaN` DEFAULT CHARACTER SET utf8 ;
USE `NaN` ;

-- -----------------------------------------------------
-- Table `NaN`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`user` ;

CREATE TABLE IF NOT EXISTS `NaN`.`user` (
  `user` INT NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`student`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`student` ;

CREATE TABLE IF NOT EXISTS `NaN`.`student` (
  `student` INT NOT NULL,
  `user` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `ra` VARCHAR(45) NULL,
  `course` VARCHAR(45) NULL,
  `year_registration` VARCHAR(45) NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`student`, `user`),
  INDEX `fk_student_user1_idx` (`user` ASC),
  CONSTRAINT `fk_student_user1`
    FOREIGN KEY (`user`)
    REFERENCES `NaN`.`user` (`user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`teacher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`teacher` ;

CREATE TABLE IF NOT EXISTS `NaN`.`teacher` (
  `teacher` INT NOT NULL,
  `user` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`teacher`, `user`),
  INDEX `fk_teacher_user1_idx` (`user` ASC),
  CONSTRAINT `fk_teacher_user1`
    FOREIGN KEY (`user`)
    REFERENCES `NaN`.`user` (`user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`course`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`course` ;

CREATE TABLE IF NOT EXISTS `NaN`.`course` (
  `course` INT NOT NULL COMMENT 'coordenador do curso',
  `name` VARCHAR(45) NULL,
  `period` INT NULL,
  `quality` INT NULL,
  `teacher` INT NOT NULL COMMENT 'coordenador do curso',
  PRIMARY KEY (`course`, `teacher`),
  INDEX `fk_course_teacher1_idx` (`teacher` ASC),
  CONSTRAINT `fk_course_teacher1`
    FOREIGN KEY (`teacher`)
    REFERENCES `NaN`.`teacher` (`teacher`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`schoolsubject`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`schoolsubject` ;

CREATE TABLE IF NOT EXISTS `NaN`.`schoolsubject` (
  `schoolsubject` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `moodle` VARCHAR(45) NULL,
  `period` INT NULL,
  `qtdP` INT NULL,
  `qtdT` INT NULL,
  `kP` FLOAT NULL,
  `kT` FLOAT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `menu` VARCHAR(500) NULL,
  `course` INT NOT NULL,
  PRIMARY KEY (`schoolsubject`, `course`),
  INDEX `fk_schoolsubject_course1_idx` (`course` ASC),
  CONSTRAINT `fk_schoolsubject_course1`
    FOREIGN KEY (`course`)
    REFERENCES `NaN`.`course` (`course`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`teaches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`teaches` ;

CREATE TABLE IF NOT EXISTS `NaN`.`teaches` (
  `schoolsubject` INT NOT NULL,
  `teacher` INT NOT NULL,
  `notificado` TINYINT NULL,
  `status` INT NULL COMMENT 'tipo de professor (coordenador, regular, integral)',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`schoolsubject`, `teacher`),
  INDEX `fk_teaches_schoolsubject_idx` (`schoolsubject` ASC),
  CONSTRAINT `fk_teaches_teacher1`
    FOREIGN KEY (`teacher`)
    REFERENCES `NaN`.`teacher` (`teacher`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_teaches_schoolsubject1`
    FOREIGN KEY (`schoolsubject`)
    REFERENCES `NaN`.`schoolsubject` (`schoolsubject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`registration`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`registration` ;

CREATE TABLE IF NOT EXISTS `NaN`.`registration` (
  `registration` INT NOT NULL,
  `student` INT NOT NULL,
  `schoolsubject` INT NOT NULL,
  `criated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`registration`, `student`, `schoolsubject`),
  INDEX `fk_registration_schoolsubject1_idx` (`schoolsubject` ASC),
  CONSTRAINT `fk_registration_student1`
    FOREIGN KEY (`student`)
    REFERENCES `NaN`.`student` (`student`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_registration_schoolsubject1`
    FOREIGN KEY (`schoolsubject`)
    REFERENCES `NaN`.`schoolsubject` (`schoolsubject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`message` ;

CREATE TABLE IF NOT EXISTS `NaN`.`message` (
  `message` INT NOT NULL,
  `title` VARCHAR(45) NULL,
  `description` VARCHAR(500) NULL,
  `importance` INT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`message`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`notification`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`notification` ;

CREATE TABLE IF NOT EXISTS `NaN`.`notification` (
  `notification` INT NOT NULL COMMENT 'fk -> mensagem',
  `user` INT NOT NULL,
  `read` TINYINT NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification`, `user`),
  INDEX `fk_notification_message1_idx` (`notification` ASC),
  CONSTRAINT `fk_notification_user1`
    FOREIGN KEY (`user`)
    REFERENCES `NaN`.`user` (`user`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_notification_message1`
    FOREIGN KEY (`notification`)
    REFERENCES `NaN`.`message` (`message`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`knowledge`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`knowledge` ;

CREATE TABLE IF NOT EXISTS `NaN`.`knowledge` (
  `knowledge` INT NULL,
  `description` VARCHAR(45) NULL,
  `difficulty` INT NULL,
  PRIMARY KEY (`knowledge`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`prerequisite`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`prerequisite` ;

CREATE TABLE IF NOT EXISTS `NaN`.`prerequisite` (
  `knowledge` INT NOT NULL,
  `schoolsubject` INT NOT NULL,
  `importance` INT NULL,
  `type` VARCHAR(45) NULL,
  PRIMARY KEY (`knowledge`, `schoolsubject`),
  INDEX `fk_prerequisite_schoolsubject1_idx` (`schoolsubject` ASC),
  CONSTRAINT `fk_prerequisite_knowledge1`
    FOREIGN KEY (`knowledge`)
    REFERENCES `NaN`.`knowledge` (`knowledge`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_prerequisite_schoolsubject1`
    FOREIGN KEY (`schoolsubject`)
    REFERENCES `NaN`.`schoolsubject` (`schoolsubject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`learned`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`learned` ;

CREATE TABLE IF NOT EXISTS `NaN`.`learned` (
  `student` INT NOT NULL,
  `knowledge` INT NOT NULL,
  `level` INT NULL,
  PRIMARY KEY (`student`, `knowledge`),
  INDEX `fk_learned_knowledge1_idx` (`knowledge` ASC),
  CONSTRAINT `fk_learned_student1`
    FOREIGN KEY (`student`)
    REFERENCES `NaN`.`student` (`student`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_learned_knowledge1`
    FOREIGN KEY (`knowledge`)
    REFERENCES `NaN`.`knowledge` (`knowledge`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`detail` ;

CREATE TABLE IF NOT EXISTS `NaN`.`detail` (
  `detail` INT NOT NULL,
  `type` ENUM('T', 'P', 'S') NULL,
  `number` INT NULL,
  `weight` FLOAT NULL,
  PRIMARY KEY (`detail`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `NaN`.`grade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `NaN`.`grade` ;

CREATE TABLE IF NOT EXISTS `NaN`.`grade` (
  `grade` INT NOT NULL,
  `value` FLOAT NOT NULL,
  `created` DATETIME NOT NULL,
  `modified` TIMESTAMP NOT NULL,
  `released` TINYINT NOT NULL,
  `registration` INT NOT NULL,
  `student` INT NOT NULL,
  `schoolsubject` INT NOT NULL,
  `detail` INT NOT NULL,
  PRIMARY KEY (`grade`, `registration`, `student`, `schoolsubject`, `detail`),
  INDEX `fk_grade_registration1_idx` (`registration` ASC, `student` ASC, `schoolsubject` ASC),
  INDEX `fk_grade_detail1_idx` (`detail` ASC),
  CONSTRAINT `fk_grade_registration1`
    FOREIGN KEY (`registration` , `student` , `schoolsubject`)
    REFERENCES `NaN`.`registration` (`registration` , `student` , `schoolsubject`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_grade_detail1`
    FOREIGN KEY (`detail`)
    REFERENCES `NaN`.`detail` (`detail`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `NaN`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`user` (`user`, `email`, `password`, `created`) VALUES (1, 'renan@gmail.com', '123', '1000-01-01 00:00:00');
INSERT INTO `NaN`.`user` (`user`, `email`, `password`, `created`) VALUES (2, 'nat@gmail.com', '123', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`user` (`user`, `email`, `password`, `created`) VALUES (3, 'gos@gmail.com', '123', '6666-12-31 22:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`student`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`student` (`student`, `user`, `name`, `lastname`, `ra`, `course`, `year_registration`, `created`, `modified`) VALUES (1, 1, 'Renan', 'Souza Silva', '13.00563-4', 'Computação', '2013', '1000-01-01 00:00:00', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`student` (`student`, `user`, `name`, `lastname`, `ra`, `course`, `year_registration`, `created`, `modified`) VALUES (2, 2, 'Natalia', 'Ferraz Ribeiro', '13.00234-1', 'Computação', '2013', '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`student` (`student`, `user`, `name`, `lastname`, `ra`, `course`, `year_registration`, `created`, `modified`) VALUES (3, 3, 'Gostavo', 'Romano', '14.00132-1', 'Computação', '2014', '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`teacher`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`teacher` (`teacher`, `user`, `name`, `lastname`, `created`, `modified`) VALUES (1, 1, 'Magrinha', 'da Silva', '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`teacher` (`teacher`, `user`, `name`, `lastname`, `created`, `modified`) VALUES (2, 2, 'Ricardo', 'Balistiero', '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`teacher` (`teacher`, `user`, `name`, `lastname`, `created`, `modified`) VALUES (3, 3, 'Everson', 'Denis', '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`course`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`course` (`course`, `name`, `period`, `quality`, `teacher`) VALUES (1, 'Computação', 1, 10, 1);
INSERT INTO `NaN`.`course` (`course`, `name`, `period`, `quality`, `teacher`) VALUES (2, 'Alimentos', 1, 5, 2);
INSERT INTO `NaN`.`course` (`course`, `name`, `period`, `quality`, `teacher`) VALUES (3, 'Quimica', 2, 7, 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`schoolsubject`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`schoolsubject` (`schoolsubject`, `name`, `moodle`, `period`, `qtdP`, `qtdT`, `kP`, `kT`, `created`, `modified`, `menu`, `course`) VALUES (1, 'Higiene', 'www.lalal.com', 1, 2, 0, 0.0, 2.0, '9999-12-31 23:59:59', '9999-12-31 23:59:59', 'www.', 1);
INSERT INTO `NaN`.`schoolsubject` (`schoolsubject`, `name`, `moodle`, `period`, `qtdP`, `qtdT`, `kP`, `kT`, `created`, `modified`, `menu`, `course`) VALUES (2, 'Economia', 'www.econ.com', 1, 4, 0, 0.0, 1.0, '9999-12-31 23:59:59', '9999-12-31 23:59:59', 'www.econ.com', 1);
INSERT INTO `NaN`.`schoolsubject` (`schoolsubject`, `name`, `moodle`, `period`, `qtdP`, `qtdT`, `kP`, `kT`, `created`, `modified`, `menu`, `course`) VALUES (3, 'Segurança', 'www.everson.com', 1, 4, 4, 0.0, 3.0, '9999-12-31 23:59:59', '9999-12-31 23:59:59', 'raspberry', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`teaches`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`teaches` (`schoolsubject`, `teacher`, `notificado`, `status`, `created`, `modified`) VALUES (1, 1, 1, 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`teaches` (`schoolsubject`, `teacher`, `notificado`, `status`, `created`, `modified`) VALUES (2, 2, 0, 0, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`teaches` (`schoolsubject`, `teacher`, `notificado`, `status`, `created`, `modified`) VALUES (3, 3, 1, 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`registration`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`registration` (`registration`, `student`, `schoolsubject`, `criated`, `modified`) VALUES (1, 1, 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`registration` (`registration`, `student`, `schoolsubject`, `criated`, `modified`) VALUES (2, 2, 2, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`registration` (`registration`, `student`, `schoolsubject`, `criated`, `modified`) VALUES (3, 3, 3, '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`message`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`message` (`message`, `title`, `description`, `importance`, `created`, `modified`) VALUES (1, 'Boa Tarde', 'Saiu nota da P2', 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`message` (`message`, `title`, `description`, `importance`, `created`, `modified`) VALUES (2, 'Boa Noite', 'Saiu nota da P1', 0, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`message` (`message`, `title`, `description`, `importance`, `created`, `modified`) VALUES (3, 'Bom Dia', 'Saiu nota da P3', 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`notification`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`notification` (`notification`, `user`, `read`, `created`, `modified`) VALUES (1, 1, 0, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`notification` (`notification`, `user`, `read`, `created`, `modified`) VALUES (2, 2, 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');
INSERT INTO `NaN`.`notification` (`notification`, `user`, `read`, `created`, `modified`) VALUES (3, 3, 1, '9999-12-31 23:59:59', '9999-12-31 23:59:59');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`knowledge`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`knowledge` (`knowledge`, `description`, `difficulty`) VALUES (1, 'Lalal', 1);
INSERT INTO `NaN`.`knowledge` (`knowledge`, `description`, `difficulty`) VALUES (2, 'Blabla', 2);
INSERT INTO `NaN`.`knowledge` (`knowledge`, `description`, `difficulty`) VALUES (3, 'Oi', 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`prerequisite`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`prerequisite` (`knowledge`, `schoolsubject`, `importance`, `type`) VALUES (1, 1, 1, 'Sei la');
INSERT INTO `NaN`.`prerequisite` (`knowledge`, `schoolsubject`, `importance`, `type`) VALUES (2, 2, 2, 'Oi');
INSERT INTO `NaN`.`prerequisite` (`knowledge`, `schoolsubject`, `importance`, `type`) VALUES (3, 3, 3, 'Tudo bem?');

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`aprendizado`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`learned` (`student`, `knowledge`, `level`) VALUES (1, 1, 1);
INSERT INTO `NaN`.`learned` (`student`, `knowledge`, `level`) VALUES (2, 2, 2);
INSERT INTO `NaN`.`learned` (`student`, `knowledge`, `level`) VALUES (3, 3, 3);

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`detail`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`detail` (`detail`, `type`, `number`, `weight`) VALUES (1, 'T', 1, 3);
INSERT INTO `NaN`.`detail` (`detail`, `type`, `number`, `weight`) VALUES (2, 'P', 2, 1);
INSERT INTO `NaN`.`detail` (`detail`, `type`, `number`, `weight`) VALUES (3, 'S', 1, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `NaN`.`grade`
-- -----------------------------------------------------
START TRANSACTION;
USE `NaN`;
INSERT INTO `NaN`.`grade` (`grade`, `value`, `created`, `modified`, `released`, `registration`, `student`, `schoolsubject`, `detail`) VALUES (1, 5.2, '9999-12-31 23:59:59', 'CURRENT_TIMESTAMP', 0, 1, 1, 1, 1);
INSERT INTO `NaN`.`grade` (`grade`, `value`, `created`, `modified`, `released`, `registration`, `student`, `schoolsubject`, `detail`) VALUES (2, 7.0, '9999-12-31 23:59:59', 'CURRENT_TIMESTAMP', 1, 2, 2, 2, 2);
INSERT INTO `NaN`.`grade` (`grade`, `value`, `created`, `modified`, `released`, `registration`, `student`, `schoolsubject`, `detail`) VALUES (3, 10.0, '9999-12-31 23:59:59', 'CURRENT_TIMESTAMP', 1, 3, 3, 3, 3);

COMMIT;

