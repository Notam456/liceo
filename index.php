<?php
    session_start();
    if (isset($_SESSION['usuario'])) {
        header("Location: main.php");
    }
?>

<!doctype html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>

   
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="70">

<style>
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
            opacity: 0.8 !important;
        }
        
        .modal-backdrop.in {
            opacity: 0.8 !important;
            filter: alpha(opacity=80) !important;
        }
        
        .modal.in .modal-dialog {
            transform: translate(0, 0);
        }
    </style>

<div id="mainContent">

<nav>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
</nav>

<div
    class="modal"
    id="modalId"
    tabindex="-1"
    data-backdrop="static"
    data-keyboard="false"
    role="dialog"
    aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div
        class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Inicio de sesión
                </h5>
            </div>
            <div class="modal-body">
                <?php
                    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php
                    }
                ?>
                <form action="verificar_login.php" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Nombre de usuario</label>
                        <input
                            type="text"
                            class="form-control"
                            name="usuario"
                            id="usuario"
                            aria-describedby="helpId"
                            placeholder="Ingrese su usuario"
                            required />
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input
                            type="password"
                            class="form-control"
                            name="contrasena"
                            id="contrasena"
                            aria-describedby="helpId"
                            placeholder="Ingrese su contraseña"
                            required />
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
                </form>
        </div>
    </div>
</div>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="hero-image">
                        <img src="/liceo/imgs/portada3.jpeg" alt="Liceo Prof. Fernando Ramírez" class="img-fluid rounded">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="hero-content">
                        <h1 class="hero-title">"LICEO PROF. FERNANDO RAMÍREZ"</h1>
                        <p class="hero-description">Institución comprometida con la formación integral de los jóvenes, fomentando valores, disciplina y excelencia académica en cada etapa educativa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="cajaTarjetas">
        <div class="Tarjeta">
            <div class="ImgTarjeta"><img src="/liceo/imgs/mision.png" alt=""></div>
            <h2>Mision</h2>
            <p>Trabajar con determinación en los asentamientos informales para superar la pobreza a través de la formación y acción conjunta de sus pobladores y pobladoras, jóvenes voluntarios y voluntarias, y otros actores.</p>
        </div>
        <div class="Tarjeta">
            <div class="ImgTarjeta"><img src="/liceo/imgs/anteojos-finos-rectangulares.png" alt=""></div>
            <h2>Vision</h2>
            <p>Una sociedad justa, igualitaria, integrada y sin pobreza en la que todas las personas puedan ejercer plenamente sus derechos y deberes, y tengan las oportunidades para desarrollar sus capacidades.</p>
        </div>
        <div class="Tarjeta">
            <div class="ImgTarjeta"><img src="/liceo/imgs/apreton-de-manos.png" alt=""></div>
            <h2>Valores</h2>
            <p>Libertad, educacion, igualdad, solidaridad, etica y paz.</p>
        </div>
    </section>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>
    <script>
     
    </script>
    <?php if (isset($_SESSION['status'])): ?>
        <script>
            console.log('Status exists, showing modal');
            window.addEventListener('load', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalId'));
                myModal.show();
            });
        </script>
        <?php unset($_SESSION['status']); // Optionally clear it after showing ?>
    <?php endif; ?>
</body>

</html>
