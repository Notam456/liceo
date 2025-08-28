
CREATE DATABASE liceoo;
USE liceoo;

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(255) NOT NULL

);

CREATE TABLE municipio (
    id_municipio INT AUTO_INCREMENT PRIMARY KEY,
    municipio VARCHAR(20) NOT NULL
);

CREATE TABLE parroquia (
    id_parroquia INT AUTO_INCREMENT PRIMARY KEY,
    parroquia VARCHAR(20) NOT NULL,
    id_municipio INT NOT NULL,
    FOREIGN KEY (id_municipio) REFERENCES municipio(id_municipio)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE profesor (
    id_personal INT AUTO_INCREMENT PRIMARY KEY,
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
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE seccion (
    id_seccion INT AUTO_INCREMENT PRIMARY KEY,
    id_grado INT NOT NULL,
    letra CHAR(1) NOT NULL,
    FOREIGN KEY (id_grado) REFERENCES grado(id_grado)
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE estudiante (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(15) UNIQUE NOT NULL,
    id_seccion INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    fecha_nacimiento DATE,
    contacto VARCHAR(100),
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE materia (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);


CREATE TABLE horario (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_seccion INT NOT NULL,
    id_materia INT NOT NULL,
    dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'),
    hora_entrada TIME NOT NULL,
    hora_salida TIME NOT NULL,
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
        ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE cargo (
    id_cargo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE asigna_cargo (
    id_personal INT NOT NULL,
    id_cargo INT NOT NULL,
    PRIMARY KEY (id_personal, id_cargo),
    FOREIGN KEY (id_personal) REFERENCES profesor(id_personal)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_cargo) REFERENCES cargo(id_cargo)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE asistencia (
    id_asistencia INT AUTO_INCREMENT PRIMARY KEY,
    id_coordinador INT NOT NULL,
    id_estudiante INT NOT NULL,
    id_seccion INT NOT NULL,
    fecha DATE NOT NULL,
    inasistencia BOOLEAN DEFAULT FALSE,
    observacion TEXT,
    justificado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_coordinador) REFERENCES profesor(id_personal)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_seccion) REFERENCES seccion(id_seccion)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE visita (
    id_visita INT AUTO_INCREMENT PRIMARY KEY,
    id_asistencia INT NOT NULL,
    fecha_visita DATE NOT NULL,
    estado VARCHAR(50),
    FOREIGN KEY (id_asistencia) REFERENCES asistencia(id_asistencia)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE asigna_materia (
    id_asignacion int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_personal int(11) NOT NULL,
    id_materia int(11) NOT NULL,
    fecha_asignacion timestamp NOT NULL DEFAULT current_timestamp(),
    estado enum('activa','inactiva') DEFAULT 'activa',
    FOREIGN KEY (id_personal) REFERENCES profesor(id_personal)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia)
        ON DELETE CASCADE ON UPDATE CASCADE
  
) 

