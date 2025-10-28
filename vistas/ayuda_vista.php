<!DOCTYPE html>
<html lang="es">
<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

  <style>
    :root {
      --navbar-height: 70px;
      --sidebar-width: 250px;
      --bg-light: #f8f9fa;
      --border-color: #dee2e6;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
      font-family: "Segoe UI", Arial, sans-serif;
    }

    /* ====== LAYOUT GENERAL ====== */
    .layout {
      display: grid;
      grid-template-columns: 1fr var(--sidebar-width);
      height: 100vh;
      width: 100%;
    }

    /* ====== CONTENEDOR PRINCIPAL ====== */
    .main-area {
      display: flex;
      flex-direction: column;
      height: 100%;
      overflow: hidden;
    }

    /* ====== CONTENIDO PRINCIPAL (scroll interno) ====== */
    .content-area {
      flex: 1;
      overflow-y: auto;
      padding: 2rem 3rem;
      background-color: #fff;
    }

    .content-area h2, .content-area h3 {
      margin-top: 2rem;
      font-weight: 600;
    }

    .content-area h4 {
      margin-top: 1.5rem;
      font-weight: 500;
      color: #444;
    }

    .content-area p {
      text-align: justify;
      line-height: 1.6;
    }

    .content-area ul {
      padding-left: 1.5rem;
    }

    .content-area li {
      margin-bottom: 0.5rem;
      line-height: 1.5;
    }

    .step-list {
      background: #f8f9fa;
      padding: 1rem 1.5rem;
      border-radius: 8px;
      margin: 1rem 0;
    }

    /* ====== SIDEBAR DERECHA (ÍNDICE) ====== */
    .sidebar2 {
      background-color: var(--bg-light);
      border-left: 1px solid var(--border-color);
      height: 100vh;
      overflow-y: auto;
      position: sticky;
      top: 0;
      padding: 1.5rem 1rem;
    }

    .sidebar2 h6 {
      font-size: 0.9rem;
      text-transform: uppercase;
      color: #666;
      margin-bottom: 1rem;
    }

    .sidebar2 .nav-link {
      color: #333;
      font-size: 0.95rem;
      padding: 0.35rem 0;
      display: block;
      transition: all 0.2s ease;
    }

    .sidebar2 .nav-link:hover,
    .sidebar2 .nav-link.active {
      color: #0d6efd;
      font-weight: 500;
    }

    .images {
      width: 800px;
    }

    /* ====== SCROLLBAR ESTILO ====== */
    .sidebar2::-webkit-scrollbar,
    .content-area::-webkit-scrollbar {
      width: 8px;
    }
    
    .sidebar2::-webkit-scrollbar-thumb,
    .content-area::-webkit-scrollbar-thumb {
      background-color: #ccc;
      border-radius: 10px;
    }
  </style>
</head>

<body>

<div class="layout">
  <div class="main-area">

      <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php');  ?>

      <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>

    <!-- CONTENIDO -->
    <div class="content-area">
      <section id="introduccion">
        <h2>Manual de Usuario - Sistema de Gestión Académica</h2>
        <p>Bienvenido al manual de usuario del sistema de gestión académica. Esta documentación le guiará en el uso de todas las funcionalidades del sistema para la administración eficiente de su institución educativa.</p>
      </section>

      <section id="inicio-sesion">
        <h3>Inicio de Sesión</h3>
        <div class="step-list">
          <ul>
            <li>El usuario debe pulsar el botón "Inicio de sesión" ubicado en la parte superior derecha.</li>
            <li>Se desplegará un campo en el que se deberán rellenar los datos solicitados, previamente generados para cada usuario.</li>
            <li>Luego de rellenar los datos solicitados, el usuario será redirigido al apartado principal del sistema.</li>
          </ul>
          
          <img class="images" src="../screenshots/1.jpg" alt="">
          <img class="images" src="../screenshots/2.jpg" alt="">

        </div>
      </section>

      <section id="anio-escolar">
        <h3>Agregar Año Escolar</h3>
        <div class="step-list">
          <ul>
            <li>Haciendo clic en "Agregar":</li>
            <li>Introducir la fecha de inicio y fecha final del año escolar y pulsar el botón "Guardar datos".</li>
            <li>Aparecerá un mensaje de confirmación al momento de crear el año académico.</li>
            <li>Si hay más de un año académico registrado, hacer clic en "Establecer activo" sobre el año académico en el cual se esté trabajando.</li>
            <li>En el apartado "Historial de cambios" se podrá visualizar y filtrar las veces que se cambió la actividad de un año académico.</li>
          </ul>

          <img class="images" src="../screenshots/3.jpg" alt="">
          <img class="images" src="../screenshots/4.jpg" alt="">
          <img class="images" src="../screenshots/5.jpg" alt="">
          <img class="images" src="../screenshots/6.jpg" alt="">


        </div>
      </section>

      <section id="profesor">
        <h3>Profesor</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Profesor" o desde el menú desplegado: "Registro de datos → Gestión académica → Profesores".</li>
            <li>Hacer clic en el botón "Agregar" y llenar los datos solicitados del profesor, luego hacer clic en "Guardar datos".</li>
            <li>Una vez registrado el profesor, se podrá consultar, modificar y eliminar.</li>
            <li>Se podrán generar reportes en formato PDF con el listado de todos los profesores registrados en el sistema.</li>
          </ul>

          <img class="images" src="../screenshots/7.jpg" alt="">
          <img class="images" src="../screenshots/8.jpg" alt="">
        </div>
      </section>

      <section id="usuario">
        <h3>Usuario</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en "Usuario" o desde el menú desplegado: "Registro de datos → Usuario".</li>
            <li>Hacer clic en "Agregar", llenar los datos solicitados, asignar el rol que tendrá dicho usuario y hacer clic en "Guardar datos".</li>
            <li>Se podrá consultar, modificar y eliminar los usuarios agregados.</li>
          </ul>
          <img class="images" src="../screenshots/9.jpg" alt="">
          <img class="images" src="../screenshots/10.jpg" alt="">
        </div>
      </section>

      <section id="estudiantes">
        <h3>Estudiante</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Estudiante" o desde el menú desplegado: "Registro de datos → Gestión académica → Estudiantes".</li>
            <li>Hacer clic en el botón "Agregar", llenar los datos solicitados del estudiante y luego hacer clic en "Guardar datos".</li>
            <li>Una vez registrado el estudiante, se podrá consultar, modificar y eliminar.</li>
            <li>Se podrán generar constancias de estudio en formato PDF para todos los estudiantes que la soliciten.</li>
          </ul>
          <img class="images" src="../screenshots/11.jpg" alt="">
          <img class="images" src="../screenshots/12.jpg" alt="">
        </div>
      </section>

      <section id="grado">
        <h3>Grado</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Grados" o desde el menú desplegado: "Registro de datos → Gestión académica → Grados".</li>
            <li>Hacer clic en el botón "Agregar" y limitar la cantidad de grados a agregar con un máximo de 5, luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregados los grados, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/13.jpg" alt="">
          <img class="images" src="../screenshots/14.jpg" alt="">
          <img class="images" src="../screenshots/15.jpg" alt="">
        </div>
      </section>

      <section id="materias">
        <h3>Materia</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Materias" o desde el menú desplegado: "Registro de datos → Gestión académica → Materias".</li>
            <li>Hacer clic en el botón "Agregar", agregar el nombre y la descripción de la materia deseada, luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregadas las materias, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/16.jpg" alt="">
          <img class="images" src="../screenshots/17.jpg" alt="">
        </div>
      </section>

      <section id="secciones">
        <h3>Seccion</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Secciones" o desde el menú desplegado: "Registro de datos → Gestión académica → Secciones".</li>
            <li>Hacer clic en el botón "Agregar", agregar la cantidad y el grado al cual serán asignadas las secciones, luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregadas las secciones, se podrá consultar, modificar y eliminar.</li>
            <li>Se podrán generar reportes de asistencia de las secciones en formato PDF.</li>
            <li>Al presionar "Consultar", se podrá asignar los estudiantes a las secciones correspondientes y generar una matrícula de los estudiantes de la sección.</li>
          </ul>
          <img class="images" src="../screenshots/18.jpg" alt="">
          <img class="images" src="../screenshots/19.jpg" alt="">
          <img class="images" src="../screenshots/20.jpg" alt="">
          <img class="images" src="../screenshots/21.jpg" alt="">
        </div>
      </section>

      <section id="cargos">
        <h3>Cargo</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Cargos" o desde el menú desplegado: "Registro de datos → Gestión académica → Cargos".</li>
            <li>Hacer clic en el botón "Agregar", agregar el nombre del cargo y su tipo, luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregado el cargo, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/22.jpg" alt="">
          <img class="images" src="../screenshots/23.jpg" alt="">
          <img class="images" src="../screenshots/24.jpg" alt="">
        </div>
      </section>

      <section id="municipio">
        <h3>Municipio</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Municipio" o desde el menú desplegado: "Registro de datos → Ubicación → Municipio".</li>
            <li>Hacer clic en el botón "Agregar", agregar el municipio y luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregado el nuevo municipio, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/25.jpg" alt="">
          <img class="images" src="../screenshots/26.jpg" alt="">
        </div>
      </section>

      <section id="parroquia">
        <h3>Parroquia</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Parroquia" o desde el menú desplegado: "Registro de datos → Ubicación → Parroquia".</li>
            <li>Hacer clic en el botón "Agregar", agregar la parroquia y luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregada la parroquia, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/27.jpg" alt="">
          <img class="images" src="../screenshots/28.jpg" alt="">
        </div>
      </section>

      <section id="sector">
        <h3>Sector</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Sector" o desde el menú desplegado: "Registro de datos → Ubicación → Sector".</li>
            <li>Hacer clic en el botón "Agregar", agregar el sector y luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregado el sector, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/29.jpg" alt="">
          <img class="images" src="../screenshots/30.jpg" alt="">
        </div>
      </section>

      <section id="horario">
        <h3>Horario</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Secciones" o desde el menú desplegado: "Registro de datos → Gestión académica → Secciones".</li>
            <li>Hacer clic en el botón "Consultar" en la sección a elegir, llenar la matrícula de estudiantes y hacer clic en "Horario" para crearlo.</li>
            <li>Seleccionar las materias con sus docentes y el día de la semana al que asisten.</li>
          </ul>
          <img class="images" src="../screenshots/31.jpg" alt="">
          <img class="images" src="../screenshots/32.jpg" alt="">
        </div>
      </section>

      <section id="asignacion-materia">
        <h3>Asignación de Materia</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Asignación de materia" o desde el menú desplegado: "Gestión operativa → Asignación de materia".</li>
            <li>Hacer clic en el botón "Asignar", agregar las materias al profesor y luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregada la materia, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/33.jpg" alt="">
          <img class="images" src="../screenshots/34.jpg" alt="">
          <img class="images" src="../screenshots/35.jpg" alt="">
        </div>
      </section>

      <section id="asignacion-cargo">
        <h3>Asignación de Cargo</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Asignación de cargo" o desde el menú desplegado: "Gestión operativa → Asignación de cargo".</li>
            <li>Hacer clic en el botón "Asignar", agregar los cargos al profesor y luego hacer clic en "Guardar datos".</li>
            <li>Una vez agregado el cargo, se podrá consultar, modificar y eliminar.</li>
          </ul>
          <img class="images" src="../screenshots/36.jpg" alt="">
          <img class="images" src="../screenshots/37.jpg" alt="">
          <img class="images" src="../screenshots/38.jpg" alt="">
        </div>
      </section>

      <section id="asistencia">
        <h3>Asistencia</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Asistencia" o desde el menú desplegado: "Gestión operativa → Asistencia".</li>
            <li>Hacer clic en el botón "Registrar asistencia", llenar los datos solicitados y marcar las casillas dependiendo si los estudiantes asistieron o no, luego hacer clic en "Guardar asistencia".</li>
            <li>Una vez registrada la asistencia, se podrá consultar y modificar.</li>
          </ul>
          <img class="images" src="../screenshots/39.jpg" alt="">
          <img class="images" src="../screenshots/40.jpg" alt="">
          <img class="images" src="../screenshots/41.jpg" alt="">
        </div>
      </section>

      <section id="ausencias">
        <h3>Ausencia</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Ausencias" o desde el menú desplegado: "Gestión operativa → Ausencias".</li>
            <li>El sistema genera una alerta de los estudiantes que tienen 3 o más ausencias.</li>
            <li>Haciendo clic en "Agendar visita", el usuario será redirigido al apartado de visita.</li>
            <li>Se podrán generar reportes del estudiante ausente.</li>
          </ul>
          <img class="images" src="../screenshots/42.jpg" alt="">
          <img class="images" src="../screenshots/43.jpg" alt="">
        </div>
      </section>

      <section id="visita">
        <h3>Visita</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Visita" o desde el menú desplegado: "Gestión operativa → Visita".</li>
            <li>Se podrá elegir la fecha de la visita a voluntad del coordinador.</li>
            <li>Una vez realizada la visita, se podrán guardar apuntes de la misma.</li>
            <li>Una vez agendada la visita, se podrá realizar o cancelar; a su vez, se podrá consultar, eliminar y generar un reporte.</li>
          </ul>
          <img class="images" src="../screenshots/44.jpg" alt="">
          <img class="images" src="../screenshots/45.jpg" alt="">
          <img class="images" src="../screenshots/46.jpg" alt="">
        </div>
      </section>

      <section id="reporte">
        <h3>Reporte</h3>
        <div class="step-list">
          <ul>
            <li>Desde el menú principal, hacer clic en el icono "Reporte" o desde el menú desplegado: "Reporte".</li>
            <li>Se podrán realizar todos los reportes académicos y de asistencia haciendo clic en sus respectivos apartados.</li>
          </ul>
          <img class="images" src="../screenshots/47.jpg" alt="">
        </div>
      </section>
    </div>

    <footer>
      <?php  include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php'); ?>
    </footer>

  </div>

  <aside class="sidebar2">
    <h6>Índice de Contenidos</h6>
    <nav class="nav flex-column">
      <a class="nav-link" href="#introduccion">Introducción</a>
      <a class="nav-link" href="#inicio-sesion">Inicio de Sesión</a>
      <a class="nav-link" href="#anio-escolar">Año Escolar</a>
      <a class="nav-link" href="#profesor">Profesor</a>
      <a class="nav-link" href="#usuario">Usuario</a>
      <a class="nav-link" href="#estudiantes">Estudiantes</a>
      <a class="nav-link" href="#grado">Grado</a>
      <a class="nav-link" href="#materias">Materias</a>
      <a class="nav-link" href="#secciones">Secciones</a>
      <a class="nav-link" href="#cargos">Cargos</a>
      <a class="nav-link" href="#municipio">Municipio</a>
      <a class="nav-link" href="#parroquia">Parroquia</a>
      <a class="nav-link" href="#sector">Sector</a>
      <a class="nav-link" href="#horario">Horario</a>
      <a class="nav-link" href="#asignacion-materia">Asignación Materia</a>
      <a class="nav-link" href="#asignacion-cargo">Asignación Cargo</a>
      <a class="nav-link" href="#asistencia">Asistencia</a>
      <a class="nav-link" href="#ausencias">Ausencias</a>
      <a class="nav-link" href="#visita">Visita</a>
      <a class="nav-link" href="#reporte">Reporte</a>
    </nav>
  </aside>
</div>

<script>
  // Resalta el índice activo al hacer scroll
  const links = document.querySelectorAll('.sidebar2 .nav-link');
  const sections = Array.from(links).map(l => document.querySelector(l.getAttribute('href')));
  const contentArea = document.querySelector('.content-area');

  contentArea.addEventListener('scroll', () => {
    const fromTop = contentArea.scrollTop + 120;
    sections.forEach((sec, i) => {
      if (sec.offsetTop <= fromTop && sec.offsetTop + sec.offsetHeight > fromTop) {
        links[i].classList.add('active');
      } else {
        links[i].classList.remove('active');
      }
    });
  });
</script>

</body>
</html>