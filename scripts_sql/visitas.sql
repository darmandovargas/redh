/*
	Script para crear tabla de visitas
	29-10-2014
*/

CREATE TABLE IF NOT EXISTS `visitas` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Id tabla visitas',
  `host` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'Ip del equipo que se conecta',
  `last_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora de ingreso',
  `visitas` int(11) NOT NULL DEFAULT '1' COMMENT 'Cantidad de visitas realizadas',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;