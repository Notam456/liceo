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
    <section class="modulos">
    <div class="modulo" data-url="controladores/usuario_controlador.php">
        <img src="/liceo/imgs/agregar-usuario.png" alt="">
        <h2>Usuarios</h2>
    </div>
    <div class="modulo" data-url="controladores/estudiante_controlador.php">
        <img src="/liceo/imgs/estudiando.png" alt="">
        <h2>Estudiantes</h2>
    </div>
    <div class="modulo" data-url="controladores/profesor_controlador.php">
        <img src="/liceo/imgs/masculino.png" alt="">
        <h2>Profesores</h2>
    </div>
    <div class="modulo" data-url="controladores/materia_controlador.php">
        <img src="/liceo/imgs/libros.png" alt="">
        <h2>Materias</h2>
    </div>
    <div class="modulo" data-url="controladores/seccion_controlador.php">
        <img src="/liceo/imgs/secciones.png" alt="">
        <h2>Seccion</h2>
    </div>

    <div class="modulo" data-url="controladores/anio_academico_controlador.php">
        <img src="/liceo/imgs/calendario.png" alt="">
        <h2>Año</h2>
    </div>
    <div class="modulo" data-url="controladores/asistencia_controlador.php">
        <img src="/liceo/imgs/lista-de-verificacion.png" alt="">
        <h2>Asistencia</h2>
    </div>
    <div class="modulo" data-url="">
        <img src="/liceo/imgs/asignacion-de-recursos.png" alt="">
        <h2>Asignacion</h2>
    </div>
    <div class="modulo" data-url="controladores/reporte_controlador.php">
        <img src="/liceo/imgs/reportes.png" alt="">
        <h2>Reportes</h2>
    </div>
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