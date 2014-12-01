
-- -----------------------------------------------------
-- Table `tweeter`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`usuarios` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(60) NULL,
  `login` VARCHAR(20) NULL,
  `senha` VARCHAR(60) NULL,
  `email` VARCHAR(60) NULL,
  `data_cadastro` DATE NULL,
  `foto` VARCHAR(50) NULL,
  `descricao` TEXT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tweeter`.`tweets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`tweets` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `texto` VARCHAR(140) NULL,
  `data_hora_postagem` TIMESTAMP NULL,
  `imagem` VARCHAR(50) NULL,
  `codigo_usuario` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_tweets_usuarios_idx` (`codigo_usuario` ASC),
  CONSTRAINT `fk_tweets_usuarios`
    FOREIGN KEY (`codigo_usuario`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tweeter`.`respostas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`respostas` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `texto` VARCHAR(140) NULL,
  `imagem` VARCHAR(50) NULL,
  `data_hora_postagem` TIMESTAMP NULL,
  `codigo_tweet` INT NOT NULL,
  `codigo_usuario` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_respostas_tweets1_idx` (`codigo_tweet` ASC),
  INDEX `fk_respostas_usuarios1_idx` (`codigo_usuario` ASC),
  CONSTRAINT `fk_respostas_tweets1`
    FOREIGN KEY (`codigo_tweet`)
    REFERENCES `tweeter`.`tweets` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_respostas_usuarios1`
    FOREIGN KEY (`codigo_usuario`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tweeter`.`retweets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`retweets` (
  `data_hora_postagem` TIMESTAMP NOT NULL,
  `codigo_tweet` INT NOT NULL,
  `codigo_usuario` INT NOT NULL,
  PRIMARY KEY (`codigo_tweet`, `codigo_usuario`),
  INDEX `fk_retweets_usuarios1_idx` (`codigo_usuario` ASC),
  CONSTRAINT `fk_retweets_tweets1`
    FOREIGN KEY (`codigo_tweet`)
    REFERENCES `tweeter`.`tweets` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_retweets_usuarios1`
    FOREIGN KEY (`codigo_usuario`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tweeter`.`favoritos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`favoritos` (
  `codigo_tweet` INT NOT NULL,
  `codigo_usuario` INT NOT NULL,
  PRIMARY KEY (`codigo_tweet`, `codigo_usuario`),
  INDEX `fk_favoritos_tweets1_idx` (`codigo_tweet` ASC),
  CONSTRAINT `fk_favoritos_usuarios1`
    FOREIGN KEY (`codigo_usuario`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_favoritos_tweets1`
    FOREIGN KEY (`codigo_tweet`)
    REFERENCES `tweeter`.`tweets` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tweeter`.`mensagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tweeter`.`mensagens` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `texto` TEXT NULL,
  `imagem` VARCHAR(50) NULL,
  `data_hora_envio` TIMESTAMP NULL,
  `remetente` INT NOT NULL,
  `destinatario` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `fk_mensagens_usuarios1_idx` (`remetente` ASC),
  INDEX `fk_mensagens_usuarios2_idx` (`destinatario` ASC),
  CONSTRAINT `fk_mensagens_usuarios1`
    FOREIGN KEY (`remetente`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mensagens_usuarios2`
    FOREIGN KEY (`destinatario`)
    REFERENCES `tweeter`.`usuarios` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

