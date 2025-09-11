<?php
    session_start();
    /*
    if($_POST){
        include_once '../configuraciones/bd.php';
        $conexionBD=BD::crearInstancia(); 

        $sentencia=$conexionBD->prepare("SELECT *,count(*) as n_usuarios
        FROM `usuario` 
        WHERE `usuario` =:usuario 
        AND `contrasena` =:password
        ");

        $usuario=$_POST['usuario'];
        $password=$_POST['password'];

        $sentencia->bindParam(':usuario',$usuario);
        $sentencia->bindParam(':password',$password);

        $sentencia->execute();
        
        $registro=$sentencia->fetch(PDO::FETCH_LAZY);
        if($registro["n_usuarios"]>0){
            $_SESSION['usuario']=$registro["usuario"];
            $_SESSION['rol']=$registro["rol"];
            $_SESSION['logged']=true;
            header("Location:/app/mainmenu/index.php");
        } else{     
            $mensaje="Usuario o contraseña incorrectos";
        }
    } 
    */

    define('ROOT_PATH', __DIR__ . '/');
?>

<!doctype html>
<html lang="en">

<head>
    <?php include(ROOT_PATH . 'includes/head.php'); ?>
    <link rel="stylesheet" href="/liceo/css/Estilos.css">
    <title>Liceo Profesor Fernando Ramirez</title>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="70">
<div id="mainContent">
    <nav>
        <?php include(ROOT_PATH . 'includes/navbar.php') ?>
    </nav>
    <?php include(ROOT_PATH . 'includes/sidebar.php') ?>

     <section class="cabecera">
        <h1>Menu</h1>
        <img src="/liceo/imgs/cuadricula.png" alt="">
    </section>
    <?php
        // Define los módulos visibles para cada rol
        $modulos_por_rol = [
            'admin' => [
                'estudiante' => '/liceo/imgs/estudiando.png',
                'profesor' => '/liceo/imgs/masculino.png',
                'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
                'usuario' => '/liceo/imgs/agregar-usuario.png',
                'materia' => '/liceo/imgs/libros.png',
                'seccion' => '/liceo/imgs/secciones.png',
                'anio_academico' => '/liceo/imgs/calendario.png',
                'asignacion' => '/liceo/imgs/asignacion-de-recursos.png',
                'reporte' => '/liceo/imgs/reporte.png',
                'grado' => '/liceo/imgs/sombrero-de-graduacion.png',
                'parroquia' => '/liceo/imgs/pueblo.png',
                'municipio' => '/liceo/imgs/cataluna.png',
            ],
            'user' => [
                'estudiante' => '/liceo/imgs/estudiando.png',
                'profesor' => '/liceo/imgs/masculino.png',
                'asistencia' => '/liceo/imgs/lista-de-verificacion.png',
            ],
            'profesor' => [
                // Define los módulos específicos del profesor aquí
            ]
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
            ?>
                <div class="modulo" data-url="<?php echo $ruta; ?>">
                    <img src="<?php echo $imagen_url; ?>" alt="">
                    <h2><?php echo ucfirst($nombre_modulo); ?></h2>
                </div>
            <?php } ?>
        </section>
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