CREATE TABLE `odalyscs_edgar`.`pre_subastas` ( `id` INT NOT NULL COMMENT 'Clave primaria' , `usuario_id` INT NULL COMMENT 'Usuario que realiza la acci�n.' , `puja_maxima` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '�ndica si har� una puja maxima en la presubasta.' , `puja_telefonica` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Indica si har� una puja telef�nica' , `asistir_subasta` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Indica si asitirar a una subasta en vivo.' , `imagen_s_id` INT NULL COMMENT 'Clave for�nea a la im�gen pertenenciente al usuario actual' , `no_hacer_nada` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Indica si no har� nada el usuario.' ) ENGINE = InnoDB COMMENT = 'Control de la presubasta.';

ALTER TABLE `pre_subastas` ADD `subasta_id` INT NOT NULL COMMENT 'Clave for�nea a la subasta la cu�l pertenece este registro.';

ALTER TABLE `pre_subastas` ADD `monto` FLOAT NULL DEFAULT NULL COMMENT 'Monto para el caso que sea por puja maxima la selecci�n para la presubasta.' ;

ALTER TABLE `pre_subastas` ADD PRIMARY KEY(`id`);

ALTER TABLE `pre_subastas` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria';

ALTER TABLE `pre_subastas` ADD UNIQUE( `usuario_id`, `imagen_s_id`, `subasta_id`);

ALTER TABLE `subastas` ADD `envio_correos` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Notifica si se realiz� el envi� de correos masivos al finalizar esta subasta.' ;



/*****   10 de Noviembre 2015 ******/


ALTER TABLE imagen_s ENGINE = InnoDB;
ALTER TABLE log_usuarios ENGINE = InnoDB;
ALTER TABLE paleta ENGINE = InnoDB;


/*****   22 de Noviembre 2015 ******/


ALTER TABLE `pre_subastas` ADD `observaciones` VARCHAR(255) COMMENT 'Observaciones de la presubasta' , ADD `telefonos` VARCHAR(255) COMMENT 'Telefonos de contacto' ;


/***** 24 de Noviembre 2015 ******/


ALTER TABLE `subastas` ADD `envio_correos_pre` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Notifica si se realiz� el envi� de correos masivos al finalizar la presubasta.' ;


/***** 03 de Diciembre 2015 ******/


ALTER TABLE `subastas` ADD `presubasta` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Indica si la subasta posee presubasta.' ;