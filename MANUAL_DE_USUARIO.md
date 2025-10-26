# Manual de Usuario: Sistema de Gestión Escolar

Este documento describe las funcionalidades del sistema para cada tipo de usuario: Administrador, Coordinador y Profesor.

---

## Tabla de Contenido
1.  [Manual para el Administrador](#manual-para-el-administrador)
    - [Inicio de Sesión](#inicio-de-sesión)
    - [Panel Principal](#panel-principal)
    - [Módulo de Ubicaciones](#módulo-de-ubicaciones)
    - [Módulo de Personal](#módulo-de-personal)
    - [Módulo Académico](#módulo-académico)
    - [Módulo de Estudiantes](#módulo-de-estudiantes)
    - [Módulo de Usuarios](#módulo-de-usuarios)
2.  [Manual para el Coordinador](#manual-para-el-coordinador)
    - [Inicio de Sesión y Panel Principal](#inicio-de-sesión-y-panel-principal-coordinador)
    - [Módulo de Asistencia](#módulo-de-asistencia)
    - [Módulo de Visitas](#módulo-de-visitas)
    - [Reportes](#reportes)
3.  [Manual para el Profesor](#manual-para-el-profesor)
    - [Inicio de Sesión y Panel Principal](#inicio-de-sesión-y-panel-principal-profesor)
    - [Consultar Horario](#consultar-horario)
    - [Consultar Asistencia](#consultar-asistencia)

---

# Manual para el Administrador

## 1. Inicio de Sesión
El primer paso para acceder al sistema es iniciar sesión con sus credenciales.
1.  Abra el sistema en su navegador web.
2.  Verá la pantalla de inicio de sesión.
    > *[IMAGEN: Pantalla de inicio de sesión]*
3.  Ingrese su nombre de usuario en el campo "Usuario".
4.  Ingrese su contraseña en el campo "Contraseña".
5.  Haga clic en el botón "Ingresar".

Si las credenciales son correctas, será redirigido al panel principal del sistema.

## 2. Panel Principal
El panel principal le da la bienvenida y muestra los accesos directos a los módulos principales del sistema a través del menú lateral izquierdo.
> *[IMAGEN: Panel principal del administrador]*

## 3. Módulo de Ubicaciones
Este módulo permite gestionar la información geográfica utilizada en el sistema.

### 3.1. Gestionar Municipios
Para añadir, editar o eliminar un municipio:
1.  En el menú lateral, haga clic en "Ubicaciones" y luego en "Municipio".
    > *[IMAGEN: Menú de ubicaciones y pantalla de municipios]*
2.  Para agregar un nuevo municipio, haga clic en "Agregar" e ingrese el nombre.
3.  Para editar un municipio, búsquelo en la lista, haga clic en el ícono de "Editar", modifique el nombre y guarde.
4.  Para ocultar un municipio, haga clic en el ícono de "Eliminar". Esto no lo borrará permanentemente, solo lo ocultará de las listas.

### 3.2. Gestionar Parroquias
Las parroquias dependen de un municipio. Los pasos son similares a la gestión de municipios.
1.  En el menú, haga clic en "Ubicaciones" y luego en "Parroquia".
2.  Al agregar o editar una parroquia, deberá seleccionar el municipio al que pertenece.
    > *[IMAGEN: Formulario de creación de parroquia]*

### 3.3. Gestionar Sectores
Los sectores dependen de una parroquia.
1.  En el menú, haga clic en "Ubicaciones" y luego en "Sector".
2.  Al agregar o editar un sector, deberá seleccionar la parroquia a la que pertenece.

## 4. Módulo de Personal

### 4.1. Gestionar Profesores
1.  En el menú, vaya a "Personal" y haga clic en "Profesor".
    > *[IMAGEN: Pantalla de gestión de profesores]*
2.  Para añadir un profesor, haga clic en "Agregar" y complete los campos: cédula, nombre y apellido.
3.  Puede editar o eliminar profesores usando los botones correspondientes en la lista.

### 4.2. Gestionar Cargos
1.  En el menú, vaya a "Personal" y haga clic en "Cargo".
2.  Agregue, edite o elimine los cargos que puede ocupar el personal (ej: Director, Coordinador).

### 4.3. Asignar Cargos
1.  En el menú, vaya a "Personal" y haga clic en "Asignar Cargo".
    > *[IMAGEN: Pantalla de asignación de cargos]*
2.  Haga clic en "Agregar".
3.  Seleccione un profesor de la lista.
4.  Seleccione el cargo a asignar.
5.  Haga clic en "Guardar". La asignación quedará como "activa".

## 5. Módulo Académico

### 5.1. Año Académico
1.  Vaya a "Académico" > "Año Académico".
    > *[IMAGEN: Pantalla de gestión de año académico]*
2.  Para crear un año, haga clic en "Agregar" y defina las fechas de inicio y fin.
3.  El sistema solo permite un año académico activo a la vez. Use el botón de "Activar" o "Desactivar" para cambiar el estado.

### 5.2. Grados, Secciones y Materias
La gestión de Grados, Secciones y Materias sigue el mismo patrón:
1.  Vaya al submenú correspondiente en "Académico".
2.  Use el botón "Agregar" para crear nuevos registros y los botones de acción para editar o eliminar.
*Nota: Para crear una sección, primero debe existir el grado al que pertenece.*

### 5.3. Asignar Materias a Profesores
1.  Vaya a "Académico" > "Asignar Materia".
2.  Haga clic en "Agregar" para abrir el formulario.
    > *[IMAGEN: Formulario de asignación de materia]*
3.  Seleccione un profesor y la materia que impartirá.
4.  Guarde la asignación. Esto permitirá luego crear horarios con este profesor y materia.

### 5.4. Gestionar Horarios
1.  Vaya a "Académico" > "Horario".
    > *[IMAGEN: Pantalla de gestión de horarios]*
2.  Haga clic en "Agregar".
3.  Seleccione la sección (ej: 1er Año, Sección A).
4.  Seleccione el día de la semana.
5.  Seleccione la materia (la lista mostrará las materias asignadas a profesores).
6.  Guarde para añadir la hora a la parrilla de esa sección.

## 6. Módulo de Estudiantes
1.  En el menú, haga clic en "Estudiante".
    > *[IMAGEN: Pantalla de gestión de estudiantes]*
2.  Para inscribir un nuevo estudiante, haga clic en "Agregar".
3.  Complete todos los datos personales, de ubicación y académicos del estudiante.
    > *[IMAGEN: Formulario de inscripción de estudiante]*
4.  Seleccione el grado y la sección a la que pertenecerá.
5.  Guarde los cambios.
6.  Puede editar la información o eliminar a un estudiante desde la lista principal.

## 7. Módulo de Usuarios
1.  Vaya a "Usuarios" en el menú.
    > *[IMAGEN: Pantalla de gestión de usuarios]*
2.  Para crear un usuario, haga clic en "Agregar".
3.  Ingrese un nombre de usuario y una contraseña.
4.  Seleccione el rol: Administrador, Coordinador o Profesor.
5.  Si el rol es Coordinador o Profesor, deberá asociar el usuario a un profesor existente en la base de datos.
    > *[IMAGEN: Formulario de creación de usuario]*
6.  Guarde el usuario. Ahora podrá acceder al sistema con esas credenciales.

---

# Manual para el Coordinador
El coordinador tiene acceso a un subconjunto de las funcionalidades del administrador, centrándose en la gestión diaria de la asistencia y el seguimiento de los estudiantes.

## 1. Inicio de Sesión y Panel Principal (Coordinador)
El proceso de inicio de sesión es idéntico al del administrador. Una vez dentro, el panel principal mostrará las opciones disponibles para su rol.
> *[IMAGEN: Panel principal del coordinador]*

## 2. Módulo de Asistencia
Este es el módulo principal para el coordinador.

### 2.1. Registrar Asistencia
1.  En el menú, haga clic en "Asistencia".
    > *[IMAGEN: Pantalla de registro de asistencia]*
2.  Seleccione la sección sobre la que desea pasar asistencia.
3.  La lista de estudiantes de esa sección aparecerá.
4.  Por defecto, todos los estudiantes están marcados como "Presente".
5.  Si un estudiante está ausente, desmarque la casilla "Asistió".
6.  Si el estudiante está ausente pero tiene una justificación, marque la casilla "Justificado" y añada una observación.
7.  Haga clic en el botón "Guardar Asistencia".

### 2.2. Ver Ausencias
1.  En el menú, vaya a "Asistencia" > "Ausencias".
    > *[IMAGEN: Pantalla de historial de ausencias]*
2.  Esta pantalla muestra un registro histórico de todas las inasistencias.
3.  Puede filtrar por fecha para encontrar registros específicos.
4.  Desde aquí puede agendar una visita domiciliaria si es necesario (ver siguiente punto).

## 3. Módulo de Visitas
Este módulo permite gestionar las visitas a los hogares de los estudiantes con inasistencias recurrentes.

### 3.1. Agendar una Visita
1.  Desde la pantalla de "Ausencias", localice al estudiante y haga clic en el botón de "Agendar Visita".
    > *[IMAGEN: Formulario para agendar visita]*
2.  Se abrirá un formulario. Asigne un encargado para la visita (un profesor o coordinador).
3.  Establezca la fecha en que la visita debe realizarse.
4.  Añada cualquier observación relevante y guarde.

### 3.2. Gestionar Visitas Agendadas
1.  En el menú, haga clic en "Visitas".
    > *[IMAGEN: Pantalla de gestión de visitas]*
2.  La lista mostrará todas las visitas, tanto las agendadas como las ya realizadas.
3.  Para marcar una visita como completada, haga clic en el botón de "Editar".
4.  Cambie el estado a "Realizada", complete la fecha en que se hizo y añada las conclusiones en "Observaciones".

## 4. Reportes
El coordinador puede generar reportes para analizar la asistencia.
1.  En el menú, haga clic en "Reportes".
    > *[IMAGEN: Pantalla de generación de reportes]*
2.  Seleccione el tipo de reporte que desea (ej: Reporte de Asistencia por Sección).
3.  Filtre por el rango de fechas y la sección deseada.
4.  Haga clic en "Generar" para ver el reporte en pantalla o descargarlo.

---

# Manual para el Profesor
El rol de profesor está diseñado para un acceso rápido a la información que necesita para sus clases diarias.

## 1. Inicio de Sesión y Panel Principal (Profesor)
El inicio de sesión es igual que para los otros roles. El panel principal del profesor es más simplificado y muestra las opciones clave.
> *[IMAGEN: Panel principal del profesor]*

## 2. Consultar Horario
Una de las funciones principales para el profesor es la consulta de su horario de clases.
1.  En el menú, haga clic en "Horario".
    > *[IMAGEN: Pantalla de consulta de horario]*
2.  El sistema mostrará automáticamente el horario asignado al profesor que ha iniciado sesión.
3.  La vista presenta las clases organizadas por día y sección, permitiéndole ver qué materia imparte y dónde.

## 3. Consultar Asistencia
Los profesores pueden consultar el registro de asistencia de sus secciones.
1.  En el menú, haga clic en "Asistencia".
    > *[IMAGEN: Pantalla de consulta de asistencia]*
2.  Seleccione una sección y una fecha para ver el registro de asistencia de ese día.
*Nota: Esta vista es de solo lectura. El registro de la asistencia lo realiza el coordinador.*
