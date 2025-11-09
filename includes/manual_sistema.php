<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="text-center mb-4">Manual del Sistema – Control de Asistencia Estudiantil</h2>

            <!-- 1. Introducción -->
            <section class="mb-4">
                <img class="images" src="../screenshots/Portada_manual_de_usuario.png" style="width: 80%; height: auto; display: block; margin:auto; margin-bottom: 100px">
                <h4 class="text-primary">1. Introducción</h4>

                <h5 class="mt-3">1.1 Propósito del Sistema</h5>
                <p>
                    El presente documento describe la estructura técnica, instalación y mantenimiento del sistema web de
                    <strong>Control de Asistencia Estudiantil</strong>, desarrollado para automatizar los procesos de registro,
                    control y seguimiento de la asistencia de los alumnos, reemplazando los métodos manuales basados en carpetas
                    físicas y planillas.
                </p>

                <h5 class="mt-3">1.2 Objetivos</h5>
                <ul>
                    <li>Optimizar el registro y control de asistencia mediante una plataforma digital.</li>
                    <li>Reducir errores humanos y mejorar la precisión de los registros.</li>
                    <li>Permitir la generación automática de reportes.</li>
                    <li>Proveer alertas tempranas por inasistencias recurrentes.</li>
                    <li>Garantizar el acceso rápido al historial académico de asistencia.</li>
                </ul>
            </section>

            <!-- 2. Tecnologías -->
            <section class="mb-4">
                <h4 class="text-primary">2. Tecnologías Utilizadas</h4>

                <h5 class="mt-3">2.1 Arquitectura del Sistema</h5>
                <p>
                    El sistema emplea una arquitectura <strong>cliente-servidor</strong> bajo el patrón
                    <strong>MVC (Modelo–Vista–Controlador)</strong>:
                </p>
                <p><em>Cliente (Navegador Web) → Servidor Web (Apache) → PHP → MySQL → Respuesta JSON</em></p>

                <h5 class="mt-3">2.2 Herramientas y Versiones</h5>
                <ul>
                    <li><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Bootstrap (interfaz gráfica y diseño responsivo).</li>
                    <li><strong>Backend:</strong> PHP 7.4 o superior.</li>
                    <li><strong>Base de Datos:</strong> MySQL 8.0.</li>
                    <li><strong>Servidor Web:</strong> Apache (XAMPP 3.3.0 o superior).</li>
                    <li><strong>Librerías:</strong> DataTables, SweetAlert2 y DomPDF.</li>
                </ul>
            </section>

            <!-- 3. Requisitos -->
            <section class="mb-4">
                <h4 class="text-primary">3. Requisitos del Sistema</h4>

                <h5 class="mt-3">3.1 Requisitos del Servidor</h5>
                <ul>
                    <li>Sistema Operativo: Windows 10 o Linux (Ubuntu 20.04+).</li>
                    <li>Memoria RAM: mínimo 2 GB.</li>
                    <li>Almacenamiento: 3 GB libres.</li>
                    <li>Software: XAMPP versión 3.3.0 o superior.</li>
                    <li>Navegador actualizado: Chrome, Firefox o Edge.</li>
                </ul>

                <h5 class="mt-3">3.2 Requisitos del Cliente</h5>
                <ul>
                    <li>Navegador compatible: Chrome 90+, Firefox 85+, Edge 90+.</li>
                    <li>Resolución mínima: 1024 × 768 píxeles.</li>
                </ul>
            </section>

            <!-- 4. Instalación -->
            <section class="mb-4">
                <h4 class="text-primary">4. Instalación y Configuración del Sistema</h4>

                <h5 class="mt-3">4.1 Requisitos Previos</h5>
                <p>Antes de iniciar la instalación, asegúrese de contar con:</p>
                <ul>
                    <li>XAMPP correctamente instalado y en ejecución.</li>
                    <li>Permisos de administrador en el equipo.</li>
                    <li>Script SQL de creación de la base de datos.</li>
                </ul>

                <h5 class="mt-3">4.2 Procedimiento de Instalación</h5>

                <h6 class="text-secondary mt-3">Paso 1: Instalación de XAMPP</h6>
                <ul>
                    <li>Descargue XAMPP desde <a href="https://www.apachefriends.org" target="_blank">apachefriends.org</a>.</li>
                    <li>Ejecute el instalador como administrador.</li>
                    <li>Active Apache y MySQL desde el panel de XAMPP.</li>
                </ul>

                <h6 class="text-secondary">Paso 2: Creación de la Base de Datos</h6>
                <ul>
                    <li>Acceda a <a href="http://localhost/phpmyadmin" target="_blank">phpMyAdmin</a>.</li>
                    <li>Importe el archivo SQL con la estructura y datos iniciales del sistema.</li>
                </ul>

                <h6 class="text-secondary">Paso 3: Despliegue de la Aplicación</h6>
                <ul>
                    <li>Copie la carpeta completa del sistema dentro de <code>C:\xampp\htdocs\</code>.</li>
                    <li>Ejemplo: <code>C:\xampp\htdocs\liceo</code></li>
                    <li>Verifique los permisos de escritura en <code>/uploads/</code> o <code>/reportes/</code>.</li>
                </ul>

                <h6 class="text-secondary">Paso 4: Configuración de la Conexión</h6>
                <p>Edite el archivo <code>con.php</code> con los siguientes valores:</p>
                <pre class="bg-light border rounded p-2">
$host = 'localhost';
$dbname = 'control_asistencia';
$username = 'root';
$password = '';
                </pre>

                <h6 class="text-secondary">Paso 5: Verificación</h6>
                <ul>
                    <li>Inicie Apache y MySQL desde el panel de XAMPP.</li>
                    <li>Ingrese en el navegador: <code>http://localhost/liceo/</code>.</li>
                    <li>Verifique que cargue la pantalla de inicio sin errores.</li>
                </ul>
            </section>

            <!-- 5. Mantenimiento -->
            <section class="mb-4">
                <h4 class="text-primary">5. Mantenimiento del Sistema</h4>

                <h5 class="mt-3">5.1 Respaldo de Información</h5>
                <ul>
                    <li>Acceder a phpMyAdmin.</li>
                    <li>Seleccionar la base de datos.</li>
                    <li>Click en “Exportar” → Formato SQL, compresión gzip.</li>
                    <li>Guardar el archivo externamente de forma segura.</li>
                </ul>

                <h5 class="mt-3">5.2 Monitoreo y Supervisión</h5>
                <ul>
                    <li>Revisar los logs de Apache en <code>xampp/apache/logs/</code>.</li>
                    <li>Verificar espacio libre en disco.</li>
                </ul>

                <h5 class="mt-3">5.3 Seguridad y Permisos</h5>
                <p><strong>Roles definidos:</strong></p>
                <ul>
                    <li><strong>Administrador:</strong> Acceso total al sistema.</li>
                    <li><strong>Coordinador:</strong> Registro de asistencias, gestión de visitas, control de asignaciones y reportes.</li>
                    <li><strong>Docente:</strong> Seguimiento de secciones asignadas.</li>
                </ul>

                <p><strong>Políticas de seguridad:</strong></p>
                <ul>
                    <li>Contraseñas deben actualizarse cada 90 días.</li>
                    <li>Sesiones expiran tras 30 minutos de inactividad.</li>
                </ul>
            </section>

            <!-- 6. Solución de Problemas -->
            <section class="mb-4">
                <h4 class="text-primary">6. Solución de Problemas Comunes</h4>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Problema</th>
                                <th>Posible Causa</th>
                                <th>Solución Recomendada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>No puedo iniciar sesión</td>
                                <td>Error en credenciales o conexión fallida</td>
                                <td>Verificar usuario/contraseña y conexión con la base de datos.</td>
                            </tr>
                            <tr>
                                <td>No aparecen las secciones</td>
                                <td>Error en permisos o asignación de usuario</td>
                                <td>Contactar al administrador del sistema.</td>
                            </tr>
                            <tr>
                                <td>Error al generar reportes</td>
                                <td>Período vacío o sin registros disponibles</td>
                                <td>Verificar que existan datos válidos en el rango seleccionado.</td>
                            </tr>
                            <tr>
                                <td>El servidor no inicia</td>
                                <td>Puerto ocupado o XAMPP mal configurado</td>
                                <td>Cerrar procesos previos y reiniciar Apache/MySQL.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 8. Consideraciones Finales -->
            <section>
                <h4 class="text-primary">8. Consideraciones Finales</h4>
                <p>
                    El correcto funcionamiento del sistema depende de mantener actualizados los componentes de software
                    (PHP, MySQL y Apache), así como de realizar mantenimientos regulares de la base de datos y copias de seguridad.
                </p>
            </section>
        </div>
    </div>
</div>
