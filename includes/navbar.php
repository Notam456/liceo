<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
    <div class="container-fluid">

        <?php if (isset($_SESSION['usuario'])) { ?>
            <button class="btn btn-outline-light me-3" id="toggleSidebar" title="Menú lateral">
                ☰
            </button>
        <?php } ?>

        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="/liceo/imgs/escudo.jpg" alt="Escudo" width="30" height="30" class="me-2">
            Liceo Bolivariano Prof. Fernando Ramírez
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <?php if (isset($_SESSION['usuario'])) { ?>
                        <a href="/liceo/logout.php" class="btn btn-outline-danger ms-lg-3 mt-2 mt-lg-0">
                            Cerrar sesión
                        </a>
                    <?php } elseif (basename($_SERVER['PHP_SELF']) !== 'index.php') {
                        header("Location: /liceo/index.php");
                    } else { ?>
                        <button
                            type="button"
                            class="btn btn-outline-light ms-lg-3 mt-2 mt-lg-0"
                            data-bs-toggle="modal"
                            data-bs-target="#modalId">
                            Iniciar Sesión
                        </button>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
