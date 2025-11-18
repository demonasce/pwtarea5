Sistema de Biblioteca Online - Tarea 5

Pasos para probar en localhost (XAMPP, Laragon, etc.):

1) Copia la carpeta `biblioteca_online` dentro de:
   - XAMPP: C:\\xampp\\htdocs\\biblioteca_online
   - Laragon: C:\\laragon\\www\\biblioteca_online

2) Crea la base de datos:
   - Entra a phpMyAdmin.
   - Crea una BD llamada `biblioteca_online` con cotejamiento utf8mb4_unicode_ci.
   - Importa el archivo `db.sql` que está en la raíz del proyecto.

3) Ajusta la URL base si es necesario:
   - Abre `config.php`.
   - Cambia la constante BASE_URL, por ejemplo:
     define('BASE_URL', 'http://localhost/biblioteca_online');

4) Abre en tu navegador:
   http://localhost/biblioteca_online/auth/login.php
   o simplemente:
   http://localhost/biblioteca_online/

5) Usuarios de prueba:
   - Administrador:
       email: admin@example.com
       contraseña: admin123
   - Bibliotecario:
       email: biblio@example.com
       contraseña: biblio123
   - Lector:
       email: lector@example.com
       contraseña: lector123
