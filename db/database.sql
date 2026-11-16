CREATE DATABASE IF NOT EXISTS agenda_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agenda_db;

-- 1. Tabla: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla: contactos
CREATE TABLE IF NOT EXISTS contactos (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT(6) UNSIGNED NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    ciudad VARCHAR(50) NOT NULL,
    CONSTRAINT fk_usuario_contacto FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);
