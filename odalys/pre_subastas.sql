/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : odalyscs_edgar

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2015-11-09 22:57:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_subastas
-- ----------------------------
DROP TABLE IF EXISTS `pre_subastas`;
CREATE TABLE `pre_subastas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria',
  `usuario_id` int(11) NOT NULL COMMENT 'Usuario que realiza la acción.',
  `puja_maxima` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Ìndica si hará una puja maxima en la presubasta.',
  `puja_telefonica` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si hará una puja telefónica',
  `asistir_subasta` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Indica si asitirar a una subasta en vivo.',
  `imagen_s_id` int(11) NOT NULL COMMENT 'Clave foránea a la imágen pertenenciente al usuario actual',
  `no_hacer_nada` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Indica si no hará nada el usuario.',
  `subasta_id` int(11) NOT NULL COMMENT 'Clave foránea a la subasta la cuál pertenece este registro.',
  `monto` float DEFAULT NULL COMMENT 'Monto para el caso que sea por puja maxima la selección para la presubasta.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`,`imagen_s_id`,`subasta_id`),
  KEY `subasta_id` (`subasta_id`),
  KEY `imagen_s_id` (`imagen_s_id`),
  CONSTRAINT `pre_subastas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pre_subastas_ibfk_2` FOREIGN KEY (`subasta_id`) REFERENCES `subastas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pre_subastas_ibfk_3` FOREIGN KEY (`imagen_s_id`) REFERENCES `imagen_s` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='Control de la presubasta.';
