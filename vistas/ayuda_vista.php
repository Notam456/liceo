<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
define('ROOT_PATH', dirname(__DIR__) . '/');
include(ROOT_PATH . 'includes/permissions.php');

$ayuda_mapping = [
  'anio-escolar' => 'anio_academico',
  'profesor' => 'profesor',
  'usuario' => 'usuario',
  'estudiantes' => 'estudiante',
  'grado' => 'grado',
  'materias' => 'materia',
  'secciones' => 'seccion',
  'cargos' => 'cargo',
  'municipio' => 'municipio',
  'parroquia' => 'parroquia',
  'sector' => 'sector',
  'horario' => 'seccion', // Se accede desde secciones
  'asignacion-materia' => 'asigna_materia',
  'asignacion-cargo' => 'asigna_cargo',
  'asistencia' => 'asistencia',
  'ausencias' => 'ausencia',
  'visita' => 'visita',
  'reporte' => 'reporte'
];
?>
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

    html,
    body {
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

    .content-area h2,
    .content-area h3 {
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
          <img class="images" src="../screenshots/Portada_manual_de_usuario.png" style="width: 80%; height: auto; display: block; margin:auto; margin-bottom: 100px">
          <h2>Manual de Usuario - Control de Asistencia Estudiantil</h2>
          <p>Bienvenido al manual de usuario del sistema de control de asistencia estudiantil. Esta documentación le guiará en el uso de todas las funcionalidades del sistema para la administración eficiente de su institución educativa.</p>
        </section>
        
        
        <section id="inicio-sesion">
          <h3>Inicio de Sesión</h3>
          <div class="step-list">
            <ul>
              <li>El usuario debe pulsar el botón "Inicio de sesión" ubicado en la parte superior derecha.</li>
              <li>Se desplegará un campo en el que se deberán rellenar los datos solicitados, previamente generados para cada usuario.</li>
              <li>Luego de rellenar los datos solicitados, el usuario será redirigido al apartado principal del sistema.</li>
            </ul>

            <img class="images" src="../screenshots/1.png" alt="">
            <img class="images" src="../screenshots/2.png" alt="" style="width: 300px; height: auto; display: block; margin-left: auto; margin-right: auto;">

          </div>
        </section>

        <?php
        if (in_array($ayuda_mapping['anio-escolar'], $modulos_visibles)) { ?>
          <section id="anio-escolar">
            <h3>Agregar Año Escolar</h3>
            <div class="step-list">
              <ul>
                <li>Paso 1: Hacer click en “agregar”</li>
                <li>Paso 2: Introducimos la fecha de inicio del año académico.</li>
                <li>Paso 3: Introducimos la fecha fin del año académico.</li>
                <li>Paso 4: Hacer click en “guardar datos”.</li>
                <li>Si hay más de un año académico registrado, hacer clic en "Establecer activo" sobre el año académico en el cual se esté trabajando.</li>
                <li>En el apartado "Historial de cambios" se podrá visualizar y filtrar las veces que se cambió la actividad de un año académico.</li>
              </ul>

              <img class="images" src="../screenshots/3.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/4.png" alt=""style="width: 400px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/5.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/6.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">


            </div>
          </section>
        <?php } ?>

        <?php if (in_array($ayuda_mapping['profesor'], $modulos_visibles)): ?>
          <section id="profesor">
            <h3>Profesor</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Profesor" o desde el menú desplegado: "Registro de datos → Gestión académica → Profesor".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: llenar los datos solicitados.</li>
                <li>Paso 3: Hacer click en “guardar datos”.</li>
                <li>Una vez Registrado el profesor, se podrá consultar, modificar y eliminar.</li>
                <li>Se podrán generar reportes en formato PDF con el listado de todos los profesores registrados en el sistema.</li>
              </ul>

              <img class="images" src="../screenshots/7.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/8.png" alt=""style="width: 400px; height: auto; display: block; margin-left: auto; margin-right: auto;" >
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['usuario'], $modulos_visibles)): ?>
          <section id="usuario">
            <h3>Usuario</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en "Usuario" o desde el menú desplegado: "Registro de datos → Usuario".</li>
                <li>Paso 1:Hacer click en “agregar”.</li>
                <li>Paso 2:Llenar los datos solicitados.</li>
                <li>Paso 3:Seleccionar el rol.</li>
                <li>Paso 4:Hacer click en “guardar datos”.</li>
                <li>Se podrá consultar, modificar y eliminar los usuarios agregados.</li>
              </ul>
              <img class="images" src="../screenshots/9.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;" >
              <img class="images" src="../screenshots/10.png" alt=""style="width: 400px; height: auto; display: block; margin-left: auto; margin-right: auto;" >
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['estudiantes'], $modulos_visibles)): ?>
          <section id="estudiantes">
            <h3>Estudiante</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Estudiante" o desde el menú desplegado: "Registro de datos → Gestión académica → Estudiantes".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: Llenar los datos solicitados.</li>
                <li>Paso 3: Seleccionar al año a cursar.</li>
                <li>Paso 4: Hacer click en “guardar datos”.</li>
                <li>Una vez registrado el estudiante, se podrá consultar, modificar y eliminar.</li>
                <li>Se podrán generar constancias de estudio en formato PDF para todos los estudiantes que la soliciten.</li>
              </ul>
              <img class="images" src="../screenshots/11.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/12.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['grado'], $modulos_visibles)): ?>
          <section id="grado">
            <h3>Grado</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Grados" o desde el menú desplegado: "Registro de datos → Gestión académica → Grados".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: Definir la cantidad de grados hasta un máximo de 5.</li>
                <li>Paso 3: Hacer clik en “guardar datos”.</li>
                <li>Una vez agregados los grados, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/13.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/14.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['materias'], $modulos_visibles)): ?>
          <section id="materias">
            <h3>Materia</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Materias" o desde el menú desplegado: "Registro de datos → Gestión académica → Materias".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: Nombre de la materia.</li>
                <li>Paso 3: Descripción de la materia.</li>
                <li>Paso 3: Hacer clik en “guardar datos”.</li>
                <li>Una vez agregadas las materias, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/15.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/16.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['secciones'], $modulos_visibles)): ?>
          <section id="secciones">
            <h3>Seccion</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Secciones" o desde el menú desplegado: "Registro de datos → Gestión académica → Secciones".</li>
                <li>Paso 1: Hacer click en “guardar datos”.</li>
                <li>Paso 2: Agregar la cantidad de secciones.</li>
                <li>Paso 3: seleccionar el año al cual pertenecen.</li>
                <li>Paso 4: Hacer click en “guardar datos”.</li>
                <li>Una vez agregadas las secciones, se podrá consultar, modificar y eliminar.</li>
                <li>Asignar: Hacer click en el consultar u asignar estudiantes de la sesión a llenar.</li>
                <li>Paso 1: Seleccionar los estudiantes.</li>
                <li>Paso 2: Seleccionar al tutor.</li>
                <li>Paso 3: Hacer click en “guardar datos“.</li>
                <li>Se podrán generar reportes de asistencia de las secciones en formato PDF.</li>
                <li>Se podrán generar matrículas de los estudiantes de la sección.</li>
              </ul>
              <img class="images" src="../screenshots/17.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/18.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/19.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/20.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['cargos'], $modulos_visibles)): ?>
          <section id="cargos">
            <h3>Cargo</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Cargos" o desde el menú desplegado: "Registro de datos → Gestión académica → Cargos".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: Asignar un nombre al cargo.</li>
                <li>Paso 3: seleccionar el tipo.</li>
                <li>Paso 4: Hacer click en “guardar datos”.</li>
                <li>Una vez agregado el cargo, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/21.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/22.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['municipio'], $modulos_visibles)): ?>
          <section id="municipio">
            <h3>Municipio</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Municipio" o desde el menú desplegado: "Registro de datos → Ubicación → Municipio".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: llenar el nombre del nuevo municipio.</li>
                <li>Paso 3: Hacer click en “guardar datos”.</li>
                <li>Una vez agregado el nuevo municipio, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/23.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/24.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['parroquia'], $modulos_visibles)): ?>
          <section id="parroquia">
            <h3>Parroquia</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Parroquia" o desde el menú desplegado: "Registro de datos → Ubicación → Parroquia".</li>
                <li>Paso 1: Hacer click en “agregar”.</li>
                <li>Paso 2: llenar el nombre de la nueva parroquia.</li>
                <li>Paso 3: Hacer click en “guardar datos”.</li>
                <li>Una vez agregada la parroquia, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/25.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/26.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['sector'], $modulos_visibles)): ?>
          <section id="sector">
            <h3>Sector</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Sector" o desde el menú desplegado: "Registro de datos → Ubicación → Sector".</li>
                <li>Paso 1: Hacer click en “agregar".</li>
                <li>Paso 2: llenar el nombre del nuevo sector.</li>
                <li>Paso 3: Hacer click en “guardar datos”.</li>
                <li>Una vez agregado el sector, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/27.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/28.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['horario'], $modulos_visibles)): ?>
          <section id="horario">
            <h3>Horario</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Secciones" o desde el menú desplegado: "Registro de datos → Gestión académica → Secciones".</li>
                <li>Hacer click en el botón “consultar “ y “Horario”.</li>
                <li>Paso 1: Seleccionar la materia y profesor.</li>
                <li>Paso 2: Seleccionar el día.</li>
                <li>Paso 3: Hacer click en “agregar”.</li>
                <li>Paso 4: Hacer click en “regresar a la sección”.</li>
                <li>Seleccionar las materias con sus docentes y el día de la semana al que asisten.</li>
              </ul>
              <img class="images" src="../screenshots/29.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/30.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['asignacion-materia'], $modulos_visibles)): ?>
          <section id="asignacion-materia">
            <h3>Asignación de Materia</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Asignación de materia" o desde el menú desplegado: "Gestión operativa → Asignación de materia".</li>
                <li>Paso 1: Hacer click en “asignar”.</li>
                <li>Paso 2: Seleccionar al profesor.</li>
                <li>Paso 3: Seleccionar la materia.</li>
                <li>Paso 4:Hacer click en guardar datos.</li>
                <li>Una vez agregada la materia, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/31.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/32.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['asignacion-cargo'], $modulos_visibles)): ?>
          <section id="asignacion-cargo">
            <h3>Asignación de Cargo</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Asignación de cargo" o desde el menú desplegado: "Gestión operativa → Asignación de cargo".</li>
                <li>Paso 1: Hacer click en “guardar datos”.</li>
                <li>Paso 2: Seleccionar el profesor.</li>
                <li>Paso 3: Seleccionar el cargo.</li>
                <li>Paso 4: Hacer click en “guardar datos”.</li>
                <li>Una vez agregado el cargo, se podrá consultar, modificar y eliminar.</li>
              </ul>
              <img class="images" src="../screenshots/34.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/35.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['asistencia'], $modulos_visibles)): ?>
          <section id="asistencia">
            <h3>Asistencia</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Asistencia" o desde el menú desplegado: "Gestión operativa → Asistencia".</li>
                <li>Hacer click en “Registrar asistencia”.</li>
                <li>Paso 1: llenar fecha.</li>
                <li>Paso 2: Seleccionar grado.</li>
                <li>Paso 3: Seleccionar sección.</li>
                <li>Paso 4: Seleccionar las materias asistidas y justificación en caso de inasistencia.</li>
                <li>Paso 5: Guardar asistencia.</li>
                <li>Una vez registrada la asistencia, se podrá consultar y modificar.</li>
              </ul>
              <img class="images" src="../screenshots/36.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/37.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/38.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['ausencias'], $modulos_visibles)): ?>
          <section id="ausencias">
            <h3>Ausencia</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Ausencias" o desde el menú desplegado: "Gestión operativa → Ausencias".</li>
                <li>El sistema genera una alerta de los estudiantes que tienen 3 o más ausencias.</li>
                <li>Paso 1: Hacer click en “agendar visita” será redirigido al apartado de visita.</li>
                <li>Se podrán generar reportes del estudiante ausente.</li>
              </ul>
              <img class="images" src="../screenshots/39.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/40.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['visita'], $modulos_visibles)): ?>
          <section id="visita">
            <h3>Visita</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Visita" o desde el menú desplegado: "Gestión operativa → Visita".</li>
                <li>Paso 1: Realizado en el apartado de ausencia .</li>
                <li>Paso 2: Asignar fecha.</li>
                <li>Paso 3: Hacer click en “Agendar”.</li>
                <li>Realizar visita:</li>
                <li>Paso 1: Hacer clik en “Realizar”</li>
                <li>Paso 2: llenar las observaciones.</li>
                <li>Paso 3: Asignar la fecha.</li>
                <li>Paso 4: Hacer clik en “Guardar cambios”.</li>
                <li>Una vez agendada la visita, se podrá realizar o cancelar; a su vez, se podrá consultar, eliminar y generar un reporte.</li>
              </ul>
              <img class="images" src="../screenshots/41.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/42.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
              <img class="images" src="../screenshots/43.png" alt=""style="width: 500px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>

        <?php if (in_array($ayuda_mapping['reporte'], $modulos_visibles)): ?>
          <section id="reporte">
            <h3>Reporte</h3>
            <div class="step-list">
              <ul>
                <li>Desde el menú principal, hacer clic en el icono "Reporte" o desde el menú desplegado: "Reporte".</li>
                <li>Se podrán realizar todos los reportes académicos y de asistencia haciendo clic en sus respectivos apartados.</li>
              </ul>
              <img class="images" src="../screenshots/44.png" alt=""style="width: 800px; height: auto; display: block; margin-left: auto; margin-right: auto;">
            </div>
          </section>
        <?php endif; ?>
      </div>

      <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php'); ?>
      </footer>

    </div>

    <aside class="sidebar2">
      <h6>Índice de Contenidos</h6>
      <nav class="nav flex-column">
        <a class="nav-link" href="#introduccion">Introducción</a>
        <a class="nav-link" href="#inicio-sesion">Inicio de Sesión</a>
        <?php if (in_array($ayuda_mapping['anio-escolar'], $modulos_visibles)): ?>
          <a class="nav-link" href="#anio-escolar">Año Escolar</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['profesor'], $modulos_visibles)): ?>
          <a class="nav-link" href="#profesor">Profesor</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['usuario'], $modulos_visibles)): ?>
          <a class="nav-link" href="#usuario">Usuario</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['estudiantes'], $modulos_visibles)): ?>
          <a class="nav-link" href="#estudiantes">Estudiantes</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['grado'], $modulos_visibles)): ?>
          <a class="nav-link" href="#grado">Grado</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['materias'], $modulos_visibles)): ?>
          <a class="nav-link" href="#materias">Materias</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['secciones'], $modulos_visibles)): ?>
          <a class="nav-link" href="#secciones">Secciones</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['cargos'], $modulos_visibles)): ?>
          <a class="nav-link" href="#cargos">Cargos</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['municipio'], $modulos_visibles)): ?>
          <a class="nav-link" href="#municipio">Municipio</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['parroquia'], $modulos_visibles)): ?>
          <a class="nav-link" href="#parroquia">Parroquia</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['sector'], $modulos_visibles)): ?>
          <a class="nav-link" href="#sector">Sector</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['horario'], $modulos_visibles)): ?>
          <a class="nav-link" href="#horario">Horario</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['asignacion-materia'], $modulos_visibles)): ?>
          <a class="nav-link" href="#asignacion-materia">Asignación Materia</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['asignacion-cargo'], $modulos_visibles)): ?>
          <a class="nav-link" href="#asignacion-cargo">Asignación Cargo</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['asistencia'], $modulos_visibles)): ?>
          <a class="nav-link" href="#asistencia">Asistencia</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['ausencias'], $modulos_visibles)): ?>
          <a class="nav-link" href="#ausencias">Ausencias</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['visita'], $modulos_visibles)): ?>
          <a class="nav-link" href="#visita">Visita</a>
        <?php endif; ?>
        <?php if (in_array($ayuda_mapping['reporte'], $modulos_visibles)): ?>
          <a class="nav-link" href="#reporte">Reporte</a>
        <?php endif; ?>
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