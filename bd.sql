
CREATE DATABASE liceoo;
USE liceoo;


CREATE TABLE municipio (
    id_municipio INT AUTO_INCREMENT PRIMARY KEY,
    municipio VARCHAR(20) NOT NULL
);

CREATE TABLE parroquia (
    id_parroquia INT AUTO_INCREMENT PRIMARY KEY,
    parroquia VARCHAR(20) NOT NULL,
    id_municipio INT NOT NULL,
    FOREIGN KEY (id_municipio) REFERENCES municipio(id_municipio)
          ON UPDATE CASCADE
);

CREATE TABLE sector (
    id_sector INT(11) AUTO_INCREMENT PRIMARY KEY,
    sector VARCHAR(50) NOT NULL,
    id_parroquia INT(11) NOT NULL,
    FOREIGN KEY (id_parroquia) REFERENCES parroquia(id_parroquia)
          ON UPDATE CASCADE
);

CREATE TABLE profesor (
    id_profesor INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(15) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL
);


CREATE TABLE anio_academico (
    id_anio INT AUTO_INCREMENT PRIMARY KEY,
    desde DATE NOT NULL,
    hasta DATE NOT NULL,
    estado TINYINT NOT NULL
);


CREATE TABLE grado (
    id_grado INT AUTO_INCREMENT PRIMARY KEY,
    id_anio INT NOT NULL,
    numero_anio INT NOT NULL,
    FOREIGN KEY (id_anio) REFERENCES anio_academico(id_anio)
          ON UPDATE CASCADE
);


CREATE TABLE seccion (
    id_seccion INT AUTO_INCREMENT PRIMARY KEY,
    id_grado INT NOT NULL,
    letra CHAR(1) NOT NULL,
    id_tutor INT NULL,
    FOREIGN KEY (id_grado) REFERENCES grado(id_grado)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_tutor) REFERENCES profesor(id_profesor)
        ON DELETE SET NULL ON UPDATE CASCADE
);


CREATE TABLE estudiante (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(15) UNIQUE NOT NULL,
    id_seccion INT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    contacto VARCHAR(100),
    id_grado int,
    id_sector INT,
    direccion_exacta TEXT,
    punto_referencia TEXT,
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_sector) REFERENCES sector(id_sector)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_grado) REFERENCES grado(id_grado)
          ON UPDATE CASCADE
);


CREATE TABLE materia (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);


CREATE TABLE cargo (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('inferior', 'superior'),
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE asigna_cargo (
    id_asignacion int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_profesor int(11) NOT NULL,
    id_cargo int(11) NOT NULL,
    fecha_asignacion timestamp NOT NULL DEFAULT current_timestamp(),
    estado enum('activa','inactiva') DEFAULT 'activa',
    FOREIGN KEY (id_profesor) REFERENCES profesor(id_profesor)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
          ON UPDATE CASCADE
);

CREATE TABLE asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_coordinador INT NULL,
    id_estudiante INT NOT NULL,
    id_seccion INT NOT NULL,
    fecha DATE NOT NULL,
    observacion TEXT,
    inasistencia BOOLEAN DEFAULT false,
    justificado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_coordinador) REFERENCES profesor(id_profesor)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
          ON UPDATE CASCADE
);

CREATE TABLE visita (
    id_visita INT AUTO_INCREMENT PRIMARY KEY,
    id_asistencia INT NOT NULL,
    encargado_id INT NULL,
    fecha_visita DATE NOT NULL,
    estado VARCHAR(50) DEFAULT 'agendada',
    observaciones TEXT,
    fecha_realizada DATE,
    FOREIGN KEY (id_asistencia) REFERENCES asistencia(id_asistencia)
          ON UPDATE CASCADE,
    FOREIGN KEY (encargado_id) REFERENCES profesor(id_profesor)
        ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE asigna_materia (
    id_asignacion int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_profesor int(11) NOT NULL,
    id_materia int(11) NOT NULL,
    fecha_asignacion timestamp NOT NULL DEFAULT current_timestamp(),
    estado enum('activa','inactiva') DEFAULT 'activa',
    FOREIGN KEY (id_profesor) REFERENCES profesor(id_profesor)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
          ON UPDATE CASCADE
  
);

CREATE TABLE horario (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_seccion INT NOT NULL,
    id_asignacion INT NOT NULL,
    dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_asignacion) REFERENCES asigna_materia(id_asignacion)
          ON UPDATE CASCADE
);

CREATE TABLE asistencia_detalle (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_asistencia INT NOT NULL,
    id_asignacion INT NOT NULL,
    FOREIGN KEY (id_asistencia) REFERENCES asistencia(id_asistencia)
          ON UPDATE CASCADE,
    FOREIGN KEY (id_asignacion) REFERENCES asigna_materia(id_asignacion)
          ON UPDATE CASCADE
);

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(255) NOT NULL, -- Posibles roles: admin, coordinador, user
    id_profesor int(11) NULL,
    FOREIGN KEY (id_profesor) REFERENCES profesor(id_profesor)
          ON UPDATE CASCADE

);


CREATE TABLE logs_anio (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_anio INT NOT NULL,
    id_usuario INT NOT NULL,
    accion ENUM('activar','desactivar') NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_anio) REFERENCES anio_academico(id_anio),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);


INSERT INTO usuario (usuario, contrasena, rol, id_profesor)
VALUES ('administrador', 'Hola1234!', 'admin', NULL);

INSERT INTO cargo (tipo, nombre) 
VALUES ('superior', 'Director');


INSERT INTO municipio (municipio) VALUES
('Arístides Bastidas'),
('Bolívar'),
('Bruzual'),
('Cocorote'),
('Independencia'),
('José Antonio Páez'),
('La Trinidad'),
('Manuel Monge'),
('Nirgua'),
('Peña'),
('San Felipe'),
('Sucre'),
('Urachiche'),
('Veroes');


INSERT INTO parroquia (parroquia, id_municipio) VALUES
-- Municipio Arístides Bastidas (id_municipio = 1)
('Arístides Bastidas', 1),

-- Municipio Bolívar (2)
('Bolívar', 2),

-- Municipio Bruzual (3)
('Chivacoa', 3),
('Campo Elías', 3),

-- Municipio Cocorote (4)
('Cocorote', 4),

-- Municipio Independencia (5)
('Independencia', 5),

-- Municipio José Antonio Páez (6)
('José Antonio Páez', 6),

-- Municipio La Trinidad (7)
('La Trinidad', 7),

-- Municipio Manuel Monge (8)
('Manuel Monge', 8),

-- Municipio Nirgua (9)
('Salóm', 9),
('Temerla', 9),
('Nirgua', 9),
('Cogollos', 9),

-- Municipio Peña (10)
('San Andrés', 10),
('Yaritagua', 10),

-- Municipio San Felipe (11)
('San Javier', 11),
('Albarico', 11),
('San Felipe', 11),

-- Municipio Sucre (12)
('Sucre', 12),

-- Municipio Urachiche (13)
('Urachiche', 13),

-- Municipio Veroes (14)
('El Guayabo', 14),
('Farriar', 14);


INSERT INTO sector (sector, id_parroquia) VALUES
-- Municipio Arístides Bastidas (id_municipio = 1)
('Arístides Bastidas', 1),

-- Municipio Bolívar (2)
('Bolívar', 2),

-- Municipio Bruzual (3)
('Chivacoa', 3),
('Campo Elías', 4),

-- Municipio Cocorote (4)
('Cocorote', 5),

-- Municipio Independencia (5)
('Independencia', 6),

-- Municipio José Antonio Páez (6)
('José Antonio Páez', 7),

-- Municipio La Trinidad (7)
('La Trinidad', 8),

-- Municipio Manuel Monge (8)
('Manuel Monge', 9),

-- Municipio Nirgua (9)
('Salóm', 10),
('Temerla', 11),
('Nirgua', 12),
('Cogollos', 13),

-- Municipio Peña (10)
('San Andrés', 14),
('Yaritagua', 15),

-- Municipio San Felipe (11)
('San Javier', 16),
('Albarico', 17),
('San Felipe', 18),

-- Municipio Sucre (12)
('Sucre', 19),

-- Municipio Urachiche (13)
('Urachiche', 20),

-- Municipio Veroes (14)
('El Guayabo', 21),
('Farriar', 22);