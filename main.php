<?php
session_start();
define('ROOT_PATH', __DIR__ . '/');
print_r($_SESSION);
?>

<!doctype html>
<html lang="en">

<head>
    <?php include(ROOT_PATH . 'includes/head.php'); ?>
    <link rel="stylesheet" href="/liceo/css/Estilos.css">
    <link rel="stylesheet" href="/liceo/css/style.css">
    <title>Liceo Profesor Fernando Ramirez</title>
</head>

<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70">
    <div id="mainContent">
        <nav>
            <?php include(ROOT_PATH . 'includes/navbar.php') ?>
        </nav>
        <?php include(ROOT_PATH . 'includes/sidebar.php') ?>
        <main>
            <h6 class="d-flex justify-content-center mt-5">¡Bienvenido de vuelta, <?= $_SESSION['nombre_prof'] ?>!</h6>
            <br>
            <section class="cabecera">

                <h1>Menu</h1>
                <img src="/liceo/imgs/cuadricula.png" alt="">
            </section>
            <?php
            // Define los módulos visibles para cada rol
            $modulos_por_rol = [
                'admin' => [
                    'usuario' => '/liceo/imgs/agregar-usuario.png',
                    'profesor' => '/liceo/imgs/masculino.png',
                    'estudiante' => '/liceo/imgs/estudiando.png',
                    'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                    'reporte' => '/liceo/imgs/reporte.png',
                    'visita' => '/liceo/imgs/calendar-check.svg',
                    'anio_academico' => '/liceo/imgs/calendario.png',
                    'grado' => '/liceo/imgs/sombrero-de-graduacion.png',
                    'seccion' => '/liceo/imgs/secciones.png',
                    'materia' => '/liceo/imgs/libros.png',
                    'asigna_materia' => '/liceo/imgs/asignacion-de-recursos.png',
                    'sector' => '/liceo/imgs/pueblo.png',
                    'parroquia' => '/liceo/imgs/pueblo.png',
                    'municipio' => '/liceo/imgs/cataluna.png',
                    'asigna_cargo' => '/liceo/imgs/asignacion-de-recursos.png',
                    'cargo' => '/liceo/imgs/suitcase-lg.svg'
                ],
                'coordinador' => [
                    'estudiante' => '/liceo/imgs/estudiando.png',
                    'profesor' => '/liceo/imgs/masculino.png',
                    'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                    'reporte' => '/liceo/imgs/reporte.png',
                    'visita' => '/liceo/imgs/calendar-check.svg',
                    'materia' => '/liceo/imgs/libros.png',
                    'seccion' => '/liceo/imgs/secciones.png',
                    'asigna_materia' => '/liceo/imgs/asignacion-de-recursos.png',
                ],
                'user' => [
                   'seccion' => '/liceo/imgs/secciones.png',
                   'visita' => '/liceo/imgs/calendar-check.svg'
                ]
            ];
            $nombre_legible = [
                'anio_academico' => 'Año académico',
                'asigna_cargo' => 'Asignación de cargo',
                'asigna_materia' => 'Asignación de materia'
            ];
            // Obtén el rol del usuario de la sesión
            $rol_usuario = $_SESSION['rol'];

            // Verifica si el rol existe en la configuración de módulos
            if (isset($modulos_por_rol[$rol_usuario])) {
                $modulos_visibles = $modulos_por_rol[$rol_usuario];
            } else {
                $modulos_visibles = []; // No hay módulos si el rol no está definido
            }

            ?>

            <section class="modulos">
                <?php foreach ($modulos_visibles as $nombre_modulo => $imagen_url) {
                    $ruta = isset($rutas_controladores[$nombre_modulo])
                        ? $rutas_controladores[$nombre_modulo]
                        : 'controladores/' . $nombre_modulo . '_controlador.php';
                    if (isset($nombre_legible[$nombre_modulo])) {
                        $texto = $nombre_legible[$nombre_modulo];
                    } else {
                        // Si no, reemplaza guiones bajos y capitaliza
                        $texto = ucfirst(str_replace('_', ' ', $nombre_modulo));
                    }
                ?>
                    <div class="modulo" data-url="<?php echo $ruta; ?>">
                        <img src="<?php echo $imagen_url; ?>" alt="">
                        <h2><?php echo $texto; ?></h2>
                    </div>
                <?php } ?>
            </section>
        </main>
        <script>
            document.querySelectorAll('.modulo').forEach(modulo => {
                modulo.addEventListener('click', () => {
                    const destino = modulo.getAttribute('data-url');
                    if (destino) {
                        window.location.href = destino;
                    }
                });
            });
        </script>
        <footer>
            <?php include(ROOT_PATH . 'includes/footer.php') ?>
        </footer>
    </div>
</body>

</html>