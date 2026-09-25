-- PCV Soluciones Industriales — schema + seed
-- SIN columnas de precio (regla de negocio)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS pcv_soluciones CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pcv_soluciones;

DROP TABLE IF EXISTS producto_imagenes;
DROP TABLE IF EXISTS cotizacion_items;
DROP TABLE IF EXISTS cotizaciones;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS subtipos;
DROP TABLE IF EXISTS clasificaciones;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS config;

CREATE TABLE usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(80) NOT NULL UNIQUE,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(160) DEFAULT NULL,
  password_hash VARCHAR(255) NOT NULL,
  estatus ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE clasificaciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  orden INT NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE subtipos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  clasificacion_id INT UNSIGNED NOT NULL,
  nombre VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL,
  orden INT NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  UNIQUE KEY uq_subtipo (clasificacion_id, slug),
  CONSTRAINT fk_sub_clas FOREIGN KEY (clasificacion_id) REFERENCES clasificaciones(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE productos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo ENUM('servicio','producto') NOT NULL DEFAULT 'servicio',
  clasificacion_id INT UNSIGNED NOT NULL,
  subtipo_id INT UNSIGNED DEFAULT NULL,
  nombre VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  resumen TEXT,
  descripcion MEDIUMTEXT,
  imagen_principal VARCHAR(255) DEFAULT NULL,
  destacado TINYINT(1) NOT NULL DEFAULT 0,
  activo TINYINT(1) NOT NULL DEFAULT 1,
  orden INT NOT NULL DEFAULT 0,
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  actualizado_en DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_prod_clas FOREIGN KEY (clasificacion_id) REFERENCES clasificaciones(id),
  CONSTRAINT fk_prod_sub FOREIGN KEY (subtipo_id) REFERENCES subtipos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE producto_imagenes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  producto_id INT UNSIGNED NOT NULL,
  archivo VARCHAR(255) NOT NULL,
  orden INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_img_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE cotizaciones (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  folio VARCHAR(32) NOT NULL UNIQUE,
  nombre VARCHAR(160) NOT NULL,
  empresa VARCHAR(160) DEFAULT NULL,
  correo VARCHAR(160) DEFAULT NULL,
  telefono VARCHAR(40) NOT NULL,
  mensaje TEXT,
  producto_id INT UNSIGNED DEFAULT NULL,
  origen VARCHAR(80) DEFAULT 'web',
  estatus ENUM('nueva','en_proceso','cerrada') NOT NULL DEFAULT 'nueva',
  creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cot_prod FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE config (
  clave VARCHAR(80) PRIMARY KEY,
  valor TEXT
) ENGINE=InnoDB;

-- Seed clasificaciones
INSERT INTO clasificaciones (id, nombre, slug, orden) VALUES
(1, 'Metal Mecánica', 'metal-mecanica', 1),
(2, 'Luminaria', 'luminaria', 2),
(3, 'Refaccionaria', 'refaccionaria', 3);

INSERT INTO subtipos (clasificacion_id, nombre, slug, orden) VALUES
(1, 'Maquinados CNC', 'maquinados-cnc', 1),
(1, 'Maquinados Láser', 'maquinados-laser', 2),
(1, 'Maquinados Convencional', 'maquinados-convencional', 3),
(1, 'Fixturas', 'fixturas', 4),
(2, 'Led Mexico', 'led-mexico', 1),
(2, 'Tecnoled', 'tecnoled', 2),
(3, 'Diesel', 'diesel', 1);

-- Seed servicios / productos ejemplo (brochure) — SIN precios
INSERT INTO productos (tipo, clasificacion_id, subtipo_id, nombre, slug, resumen, descripcion, imagen_principal, destacado, orden) VALUES
('servicio', 1, 1, 'Maquinados CNC — Torno y Centro de Maquinado', 'maquinados-cnc',
 'Fabricación de piezas de precisión con torno CNC y centro de maquinado.',
 'Servicio de maquinados CNC con supervisión de cada proceso para cumplir estándares de calidad exigentes y el mejor tiempo de entrega. Ideales para piezas metálicas de alta precisión.',
 'item-1.png', 1, 1),
('servicio', 1, 2, 'Corte Láser y Procesos Integrados', 'corte-laser',
 'Corte láser, chorro de agua, punzonado, plasma y router.',
 'Procesos integrados: corte láser, corte láser en materiales blandos, chorro de agua, punzonado, plasma y corte con router. Soluciones metal-mecánicas de punta a punta.',
 'item-2.png', 1, 2),
('servicio', 1, 3, 'Maquinados Convencionales', 'maquinados-convencionales',
 'Maquinado convencional para prototipos y series cortas.',
 'Capacidad de maquinado convencional complementaria a CNC, ideal para ajustes, prototipos y piezas especiales.',
 'item-3.png', 1, 3),
('servicio', 1, 2, 'Soldadura Láser', 'soldadura-laser',
 'Soldadura láser para acero inoxidable, aluminio y acero al carbono.',
 'Servicio de soldadura láser para materiales especiales como acero inoxidable, aluminio y acero al carbono, con acabados limpios y mínima deformación.',
 'item-4.png', 1, 4),
('servicio', 2, 1, 'Estudios e Iluminación Industrial LED', 'iluminacion-industrial-led',
 'Estudios de iluminación para garantizar los luxes requeridos.',
 'Iluminación industrial con estudios para garantizar luxes por proyecto. Familias Torino-200, COB-Oval AP-130W, RF-300W COP y más.',
 'item-5.png', 1, 5),
('producto', 1, 4, 'Fixturas y Dispositivos de Sujeción', 'fixturas-dispositivos',
 'Diseño y fabricación de fixturas para procesos industriales.',
 'Diseño y fabricación de fixturas a la medida para líneas de producción, ensamble e inspección.',
 'item-6.png', 0, 6),
('producto', 2, 1, 'Luminaria Torino-200', 'luminaria-torino-200',
 'Luminaria industrial LED Torino-200.',
 'Producto de iluminación industrial LED. Cotiza según luxes y layout de tu planta.',
 'item-5.png', 0, 7),
('producto', 3, 1, 'Refacciones Diesel', 'refacciones-diesel',
 'Línea de refaccionaria diesel para equipo industrial.',
 'Suministro de refacciones diesel. Solicita cotización con número de parte o especificación.',
 'item-1.png', 0, 8);

INSERT INTO producto_imagenes (producto_id, archivo, orden)
SELECT id, imagen_principal, 0 FROM productos WHERE imagen_principal IS NOT NULL;

INSERT INTO usuarios (usuario, nombre, correo, password_hash) VALUES
('admin', 'Administrador PCV', 'gerardo.solind@gmail.com', '$2y$12$ONh8t85XIiJ4uwOCiPBhlOP96x01ETdxV/BeQZC5Hb/7aDazZAdNm');

INSERT INTO config (clave, valor) VALUES
('empresa', 'PCV Soluciones Industriales'),
('eslogan', 'Creamos la figura más difícil de la industria'),
('whatsapp', '5213311444743'),
('telefono1', '3311444743'),
('telefono2', '3310438300'),
('email', 'gerardo.solind@gmail.com'),
('instagram', 'pcvsolind'),
('facebook', 'https://www.facebook.com/share/1DzyvKrbSt/');

SET FOREIGN_KEY_CHECKS = 1;
