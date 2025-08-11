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
    <link rel="stylesheet" href="/liceo/css/Estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous"> 
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70">

<div id="mainContent">

<nav>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
</nav>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modalId"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
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
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
                </form>
        </div>
    </div>
</div>

    <div class="portada">
        <img src="/liceo/imgs/portada3.jpeg" alt="">
    </div>
    <section class="description">
        <h1>“LICEO PROF.FERNANDO RAMÍREZ”</h1>
        <p>Institución comprometida con la formación integral de los jóvenes, fomentando valores, disciplina y excelencia académica en cada etapa educativa.</p>
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
        const myModal = new bootstrap.Modal(
            document.getElementById("modalId"),
            options,
        );
    </script>
    <?php if (isset($_SESSION['status'])): ?>
        <script>
            console.log('status exists you dumbass');
            window.addEventListener('load', function() {
                var myModal = new bootstrap.Modal(document.getElementById('modalId'));
                myModal.show();
            });
        </script>
        <?php unset($_SESSION['status']); // Optionally clear it after showing ?>
    <?php endif; ?>
</body>

</html>