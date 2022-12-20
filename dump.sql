CREATE TABLE IF NOT EXISTS `adopet`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email_validation` TINYINT NOT NULL DEFAULT 0,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  PRIMARY KEY (`id`))
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS `adopet`.`pets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `age` INT NOT NULL,
  `size` VARCHAR(45) NOT NULL,
  `feature` VARCHAR(45) NOT NULL,
  `city` VARCHAR(90) NOT NULL,
  `tel` VARCHAR(11) NOT NULL,
  `user_id` INT NOT NULL,
  `photo` TEXT NULL,
  PRIMARY KEY (`id`, `user_id`),
  INDEX `fk_pets_user_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_pets_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `adopet`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS `adopet`.`Perfil` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `phone` VARCHAR(13) NULL,
  `city` VARCHAR(45) NULL,
  `about` VARCHAR(255) NULL,
  `photo` TEXT NULL,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Perfil_user1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_Perfil_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `adopet`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB