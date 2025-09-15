# Sistema de Gestión Escolar

Este es un sistema de gestión escolar basado en la web desarrollado en PHP. La aplicación proporciona una solución integral para la gestión de diversas actividades escolares, incluyendo la gestión de estudiantes, profesores, asistencia y más.

## Características

- **Gestión de Estudiantes:** Añadir, editar, ver y eliminar registros de estudiantes.
- **Gestión de Profesores:** Gestionar la información de los profesores.
- **Gestión de Coordinadores:** Asignar y gestionar coordinadores.
- **Año Académico:** Crear y gestionar años académicos.
- **Grados y Secciones:** Administrar grados y secciones para cada año académico.
- **Materias y Horarios:** Gestionar materias y crear horarios para diferentes secciones.
- **Asignaciones:** Asignar materias y cargos a los profesores.
- **Seguimiento de Asistencia:** Registrar y hacer seguimiento de la asistencia de los estudiantes.
- **Gestión de Visitas:** Registrar las visitas de seguimiento a los estudiantes.
- **Autenticación de Usuarios:** Sistema de inicio de sesión seguro con diferentes roles de usuario (por ejemplo, administrador, profesor).
- **Generación de Informes:** Crear informes en formato PDF y Word utilizando las bibliotecas TCPDF y PHPWord.

## Tecnologías Utilizadas

- **Backend:** PHP
- **Base de Datos:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Frameworks/Librerías:**
  - Bootstrap para el diseño responsivo.
  - TCPDF para la generación de PDFs.
  - PHPWord para la creación de documentos de Word.
  - jQuery para la manipulación del DOM y AJAX.
  - SweetAlert2 para alertas y modales atractivos.
  - DataTables para tablas de datos interactivas.

## Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

1.  **Prerrequisitos:**
    - Asegúrate de tener un servidor web local como [XAMPP](https://www.apachefriends.org/index.html) o [WAMP](http://www.wampserver.com/en/) instalado. Esto proporcionará Apache, MySQL y PHP.
    - Se recomienda PHP 7.4 o superior.

2.  **Clonar el Repositorio:**
    - Clona o descarga este repositorio en el directorio `htdocs` (para XAMPP) o `www` (para WAMP) de tu servidor.

3.  **Crear la Base de Datos:**
    - Abre phpMyAdmin (generalmente accesible en `http://localhost/phpmyadmin`).
    - Crea una nueva base de datos llamada `liceoo`.

4.  **Importar el Esquema de la Base de Datos:**
    - Selecciona la base de datos `liceoo` que acabas de crear.
    - Ve a la pestaña "Importar".
    - Haz clic en "Elegir archivo" y selecciona el archivo `bd.sql` de la raíz del proyecto.
    - Haz clic en "Ir" para importar el esquema y los datos iniciales.

5.  **Configuración de la Conexión a la Base de Datos:**
    - El archivo de conexión a la base de datos se encuentra en `includes/conn.php`.
    - Por defecto, la configuración es:
      ```php
      $conn = mysqli_connect("localhost", "root", "", "liceoo");
      ```
    - Si tu configuración de MySQL es diferente (por ejemplo, tienes una contraseña para el usuario `root`), actualiza este archivo con tus credenciales.

6.  **Ejecutar la Aplicación:**
    - Inicia tu servidor Apache y MySQL a través del panel de control de XAMPP/WAMP.
    - Abre tu navegador web y navega a `http://localhost/nombre-del-directorio-del-proyecto`.

## Uso

- Una vez que la aplicación esté en funcionamiento, puedes iniciar sesión con las credenciales de usuario.
- Si no hay usuarios por defecto, puedes registrar un nuevo usuario o insertar un registro de usuario en la tabla `usuario` de la base de datos `liceoo` a través de phpMyAdmin. La contraseña debe estar encriptada (se recomienda usar `password_hash()` en PHP).

## Estructura de Carpetas

```
.
├── controladores/      # Contiene la lógica de la aplicación
├── css/                # Hojas de estilo
├── icons/              # Iconos
├── imgs/               # Imágenes
├── includes/           # Archivos PHP reutilizables (conexión a BD, cabecera, pie de página)
├── js/                 # Archivos JavaScript
├── modelos/            # Lógica de negocio y de base de datos
├── vistas/             # Archivos de presentación (UI)
├── TCPDF/              # Biblioteca para la generación de PDF
├── PHPWord-master/     # Biblioteca para la creación de documentos de Word
├── bd.sql              # Volcado de la base de datos
├── index.php           # Página de inicio de sesión
├── main.php            # Página principal de la aplicación
└── README.md           # Este archivo
```
