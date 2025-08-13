<style>
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background-color: #212529;
        color: white;
        padding-top: 70px;
        transition: left 0.3s ease-in-out;
        z-index: 1040;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar a {
        color: white;
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    .sidebar a:hover {
        background-color: #343a40;
        text-decoration: none;
    }

    #mainContent {
        transition: margin-left 0.3s ease-in-out;
        margin-left: 0;
    }

    #mainContent.shifted {
        margin-left: 250px; 
    }
</style>

<div id="sidebar" class="sidebar">
    <a href="/liceo/main.php"><i class="bi bi-house-door me-2"></i> Inicio</a>
    <a href="/liceo/controladores/usuario_controlador.php"><i class="bi bi-people me-2"></i> Usuarios</a>
    <a href="/liceo/controladores/estudiante_controlador.php"><i class="bi bi-person-badge me-2"></i> Estudiantes</a>
    <a href="/liceo/controladores/profesor_controlador.php"><i class="bi bi-person-workspace me-2"></i> Profesores</a>
    <a href="/liceo/controladores/coordinador_controlador.php"><i class="bi bi-person-lines-fill me-2"></i> Coordinadores</a>
    <a href="/liceo/controladores/seccion_controlador.php"><i class="bi bi-diagram-3 me-2"></i> Secciones</a>
    <a href="/liceo/controladores/materia_controlador.php"><i class="bi bi-journal-bookmark me-2"></i> Materias</a>
    <a href="/liceo/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a>
</div>

<script>
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    // Mostrar/ocultar sidebar
    toggleButton?.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        mainContent?.classList.toggle('shifted');
    });

    // Cerrar al hacer clic fuera
    document.addEventListener('click', function (event) {
        const isClickInside = sidebar.contains(event.target) || toggleButton?.contains(event.target);
        if (!isClickInside && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            mainContent?.classList.remove('shifted');
        }
    });
</script>
