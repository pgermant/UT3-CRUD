CREATE DATABASE BinterMas;

USE BinterMas;

-- Crear la tabla para registrar los usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni_nie ENUM('DNI', 'NIE') NOT NULL,
    titulo ENUM('Sr.', 'Sra.') NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    primer_apellido VARCHAR(100) NOT NULL,
    segundo_apellido VARCHAR(100),
    fecha_nacimiento DATE NOT NULL,
    nacionalidad VARCHAR(50),
    isla_residencia VARCHAR(50),
    municipio VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    foto VARCHAR (255) NULL,
    puntos INT NOT NULL,
    numero_tarjeta VARCHAR(20) NOT NULL,
    tipo_tarjeta ENUM('Verde', 'Plata', 'Oro')NOT NULL,

    );

-- Insertar un ejemplo de un registro de usuario
INSERT INTO
    usuarios (
        dni_nie,
        titulo,
        nombre,
        primer_apellido,
        segundo_apellido,
        fecha_nacimiento,
        nacionalidad,
        isla_residencia,
        municipio,
        telefono,
        email,
        contrasena,
        foto,
        puntos,
        numero_tarjeta,
        tipo_tarjeta,
    )
VALUES
    (
        '12345678X',
        'Sr.',
        'Juan',
        'Pérez',
        'Gómez',
        '1990-05-10',
        'Española',
        'Tenerife',
        'Santa Cruz de Tenerife',
        '612345678',
        'juan.perez@example.com',
        'hashed_password_example',
        'NT101245',
        'Verde'
    );