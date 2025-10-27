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
      overflow: hidden; /* Evita scroll de la ventana */
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

    .content-area p {
      text-align: justify;
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
        <h2>Introducción</h2>
        <p>Bienvenido a la documentación del sistema de gestión académica. Aquí encontrarás información detallada sobre los módulos, su uso y ejemplos visuales.</p>
      </section>

      <section id="usuarios">
        <h3>Gestión de Usuarios</h3>
        <p>Permite registrar, modificar y eliminar cuentas de usuario. Cada usuario tiene un rol que define sus permisos dentro del sistema.</p>
      </section>

      <section id="docentes">
        <h3>Gestión de Docentes</h3>
        <p>Desde este módulo puedes agregar docentes, actualizar sus datos y consultar su carga académica asignada.</p>
      </section>

      <section id="anio">
        <h3>Años Académicos</h3>
        <p>Define los períodos lectivos y su estructura. Este módulo incluye trayectos, materias, secciones y aulas asociadas.</p>
      </section>

      <section id="asignacion">
        <h3>Asignación de Carga Horaria</h3>
        <p>Permite relacionar docentes con materias específicas según el año académico activo.</p>
      </section>

      <section id="reportes">
        <h3>Reportes</h3>
        <p>Genera reportes en formato PDF o Excel con la información de carga horaria y asistencia docente.</p>
      </section>

      <section id="faq">
        <h3>Preguntas Frecuentes</h3>
        <ul>
          <li><strong>¿Cómo cambio mi contraseña?</strong> → Desde el módulo de usuarios.</li>
          <li><strong>¿Puedo exportar los datos?</strong> → Sí, en el módulo de reportes.</li>
        </ul>
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
      <a class="nav-link" href="#usuarios">Gestión de Usuarios</a>
      <a class="nav-link" href="#docentes">Gestión de Docentes</a>
      <a class="nav-link" href="#anio">Años Académicos</a>
      <a class="nav-link" href="#asignacion">Asignación de Carga Horaria</a>
      <a class="nav-link" href="#reportes">Reportes</a>
      <a class="nav-link" href="#faq">Preguntas Frecuentes</a>
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
