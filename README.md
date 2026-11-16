# Agenda Personal de Contactos con Autenticación en PHP

## Descripción del Proyecto
Aplicación web orientada a eventos de usuario desarrollada en PHP nativo y MySQL. Permite el registro seguro de usuarios, inicio de sesión mediante autenticación basada en contraseñas encriptadas y la gestión de una agenda personal de contactos. La aplicación implementa aislamiento completo de datos a nivel de base de datos y backend para garantizar que ningún usuario acceda a los registros de otro.

## Tecnologías Utilizadas
* **Lenguaje Backend:** PHP 8.x
* **Base de Datos:** MySQL / MariaDB
* **Servidor Web:** Apache (XAMPP)
* **Frontend:** HTML5 semántico
* **Seguridad:** Encriptación bcrypt (`password_hash`, `password_verify`), Sentencias preparadas (Prepared Statements) y control de acceso por `$_SESSION`.

## Requisitos para su Ejecución
1. Entorno de desarrollo local como XAMPP, WAMP o Laragon con PHP >= 7.4 o 8.x.
2. Servidor de Base de Datos MySQL activo.
3. Navegador web estándar.

## Pasos para Instalar la Aplicación
1. Clonar el repositorio en la carpeta pública del servidor web (`htdocs` en XAMPP):
   `git clone https://github.com/jldynamics-glitch/agenda-php.git agenda_contactos`
2. Abrir **phpMyAdmin** en el navegador (`http://localhost/phpmyadmin`).
3. Crear una nueva base de datos llamada `agenda_db` e importar el archivo `db/database.sql`.
4. Verificar la credencial de conexión en `config/conexion.php` (Por defecto: usuario `root` sin clave).
5. Acceder a la aplicación desde: `http://localhost/agenda/login.php`
