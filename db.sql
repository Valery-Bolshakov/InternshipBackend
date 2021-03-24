CREATE DATABASE db;
CREATE USER 'db'@'localhost' IDENTIFIED WITH mysql_native_password BY 'db';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON db.* TO 'db'@'localhost';

CREATE TABLE `db`.`test` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Name_of_product` VARCHAR(100) NOT NULL ,
  `single_cost` FLOAT NOT NULL ,
  `wholesale_cost` INT NOT NULL ,
  `In_stock_1` INT NOT NULL ,
  `In_stock_2` INT NOT NULL ,
  `Country` VARCHAR(100) NOT NULL ,
  `Comment` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`)) ENGINE = InnoDB;