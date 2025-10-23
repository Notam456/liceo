<?php
session_start();
if (!isset($_SESSION['profesor'])) {
    header("Location: /liceo/error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
    <title>Reportes del Sistema</title>
    <link rel="stylesheet" href="/liceo/css/Estilos.css">
    <link rel="stylesheet" href="/liceo/css/style.css">
    <style>
        .categoria-section {
            margin-bottom: 40px;
        }
        
        .categoria-titulo {
            color: #111827;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        
        /* .categoria-linea {
            border: 0;
            height: 2px;
            background: linear-gradient(90deg, #3498db, #2c3e50);
            margin-bottom: 25px;
        }*/
        
        .modulos {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }
        
        .modulo {
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #e9ecef;
        }
        
        /*.modulo:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-color: #3498db;
        }*/
        
        .modulo img {
            width: 70px;
            height: 70px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        
        .modulo:hover img {
            transform: scale(1.1);
        }
        
        .modulo h2 {
            color: #111827;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .modulo p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-top: 8px;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <nav>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>

    <div class="container-fluid" style="margin-top: 30px;">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] != '') : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <!--<img src="/liceo/icons/clipboard-data.svg" class="me-2">---> Reportes del Sistema
                            <small class="float-end">Genera reportes detallados del liceo</small>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Sección de Reportes de Asistencia -->
                        <div style="padding-top: 35px;" class="categoria-section">
                            <h2 class="categoria-titulo">
                                <img src="/liceo/icons/clipboard-check.svg" class="me-2"> Reportes de Asistencia
                            </h2>
                            <hr class="categoria-linea">
                            <section class="modulos">
                                <div class="modulo" data-url="/liceo/controladores/ausencia_controlador.php">
                                    <img src="/liceo/icons/person-dash.svg" alt="Reporte de Ausencias">
                                    <h2>Reporte de Ausencias</h2>
                                    <p>Consulta y genera reportes detallados de inasistencias estudiantiles</p>
                                </div>
                                
                                <div class="modulo" data-url="/liceo/controladores/visita_controlador.php">
                                    <img src="/liceo/icons/house-door.svg" alt="Reporte de Visitas">
                                    <h2>Reporte de Visitas</h2>
                                    <p>Genera reportes de visitas domiciliarias programadas</p>
                                </div>

                                <div class="modulo" data-url="/liceo/controladores/seccion_controlador.php">
                                    <img src="/liceo/icons/collection.svg" alt="Reporte por Secciones">
                                    <h2>Reporte por Secciones</h2>
                                    <p>Genera un Reporte con el Porcentaje de inasistencia de cada seccion</p>
                                </div>
                            </section>
                        </div>

                        <!-- Sección de Reportes Académicos -->
                        <div class="categoria-section">
                            <h2 class="categoria-titulo">
                                <img src="/liceo/icons/journal-text.svg" class="me-2"> Reportes Académicos
                            </h2>
                            <hr class="categoria-linea">
                            <section class="modulos">
                                <div class="modulo" data-url="/liceo/controladores/estudiante_controlador.php">
                                    <img src="/liceo/icons/people.svg" alt="Reporte de Estudiantes">
                                    <h2>Reporte de Estudiantes</h2>
                                    <p>Listados y constancias de estudiantes matriculados</p>
                                </div>

                                <div class="modulo" data-url="/liceo/controladores/seccion_controlador.php">
                                    <img src="/liceo/icons/table.svg" alt="Registro de Asistencia">
                                    <h2>Reporte de Matricula</h2>
                                    <p>Genera un reporte de la matricula de estudiantes en una seccion</p>
                                </div>

                                <div class="modulo" data-url="/liceo/controladores/profesor_controlador.php">
                                    <img src="/liceo/icons/person-workspace.svg" alt="Registro de Asistencia">
                                    <h2>Reporte de Profesores</h2>
                                    <p>Genera un reporte en donde puedas ver los datos de los profesores, sus materias y su cargo</p>
                                </div>

                                <div class="modulo" data-url="/liceo/controladores/asistencia_controlador.php">
                                    <img src="/liceo/icons/calendar-check.svg" alt="Registro de Asistencia">
                                    <h2>Registro de Asistencia</h2>
                                    <p>Gestiona y consulta los registros diarios de asistencia</p>
                                </div>
                            </section>
                        </div>

                        <!-- Sección de Reportes Administrativos 
                        <div class="categoria-section">
                            <h2 class="categoria-titulo">
                                <img src="/liceo/icons/graph-up.svg" class="me-2"> Reportes Administrativos
                            </h2>
                            <hr class="categoria-linea">
                            <section class="modulos">
                                <div class="modulo" data-url="/liceo/controladores/estadistica_controlador.php">
                                    <img src="/liceo/icons/bar-chart-line.svg" alt="Estadísticas Generales">
                                    <h2>Formato de Notas</h2>
                                    <p>Formato de notas para profesores, gestione sus evaluaciones y facilite su trabajo</p>
                                </div>
                            </section>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

    <script>
        document.querySelectorAll('.modulo').forEach(modulo => {
            modulo.addEventListener('click', () => {
                const destino = modulo.getAttribute('data-url');
                if (destino) {
                    window.open(destino, '_blank');
                }
            });
        });

        // Efectos visuales adicionales
        document.querySelectorAll('.modulo').forEach(modulo => {
            modulo.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(135deg, #f8f9fa, #e9ecef)';
            });
            
            modulo.addEventListener('mouseleave', function() {
                this.style.background = 'white';
            });
        });
    </script>
</body>

</html>