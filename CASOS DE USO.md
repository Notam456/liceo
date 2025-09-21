### **Módulo de Asistencias**

---

**Nombre de caso de uso:** Registrar Asistencia de Estudiantes

**Descripción:**
Este caso de uso permite a un profesor registrar la asistencia diaria de los estudiantes de una sección específica. El sistema mostrará la lista de estudiantes de la sección para que el profesor marque su estado (Presente, Ausente o Justificado).

**Actores:**
*   Profesor

**Precondiciones:**
*   El profesor debe haber iniciado sesión en el sistema.
*   Debe existir un año académico activo.
*   Deben existir grados, secciones y estudiantes asignados a esas secciones.

**Activación:**
El profesor accede a la sección "Asistencias" y selecciona la opción para registrar una nueva asistencia.

**Flujo normal:**
1.  **Acciones del Actor:**
    1.  Selecciona la fecha para la cual se registrará la asistencia.
    2.  Selecciona el grado.
    3.  Selecciona la sección.
    4.  El sistema carga la lista de estudiantes de la sección seleccionada.
    5.  Para cada estudiante, marca el estado: "Presente", "Ausente" o "Justificado".
    6.  Si un estudiante está "Justificado", añade una breve nota de justificación.
    7.  Hace clic en el botón "Guardar Asistencia".
2.  **Respuesta del sistema:**
    1.  Valida los datos de entrada.
    2.  Guarda el registro de asistencia para cada estudiante en la base de datos.
    3.  Muestra un mensaje de confirmación: "Asistencia registrada correctamente".
    4.  Redirige a la lista de asistencias.
**Post condiciones:**
*   Se crea un nuevo registro de asistencia para la fecha y sección especificadas.

**Flujo alternativo 1: Intento de registro duplicado**
*   **Precondición:** Ya existe un registro de asistencia para la fecha and sección seleccionadas.
*   **Acciones:**
    1.  El profesor selecciona una fecha y sección para las que ya se ha registrado asistencia.
    2.  El sistema detecta el registro duplicado.
    3.  Muestra un mensaje de advertencia: "Ya existe un registro de asistencia para esta fecha y sección."
    4.  No permite continuar con el registro.

**Flujo alternativo 2: Sección sin estudiantes**
*   **Precondición:** La sección seleccionada no tiene estudiantes asignados.
*   **Acciones:**
    1.  El profesor selecciona el grado y la sección.
    2.  El sistema no encuentra estudiantes.
    3.  Muestra el mensaje: "No hay estudiantes en esta sección".

---

**Nombre de caso de uso:** Consultar Asistencia

**Descripción:**
Permite a los usuarios autorizados (Coordinadores, Administradores) ver los registros de asistencia. Se pueden aplicar filtros para encontrar registros específicos por fecha, grado o sección, y ver un resumen y el detalle de cada registro.

**Actores:**
*   Coordinador
*   Administrador

**Precondiciones:**
*   El usuario debe haber iniciado sesión en el sistema.

**Activación:**
El usuario accede al módulo de "Asistencias".

**Flujo normal:**
1.  **Acciones del Actor:**
    1.  Navega a la página de asistencias. El sistema muestra los registros más recientes.
    2.  (Opcional) Utiliza los filtros para buscar por un rango de fechas, un grado o una sección específica.
    3.  Hace clic en el botón "Filtrar".
    4.  El sistema actualiza la lista mostrando los registros que coinciden con los criterios.
    5.  Para un registro de interés, hace clic en el botón "Consultar" o "Ver detalle".
2.  **Respuesta del sistema:**
    1.  Muestra una lista agrupada de asistencias con un resumen (Total de estudiantes, presentes, ausentes, justificados).
    2.  Al consultar el detalle, muestra una ventana modal con la lista de todos los estudiantes de esa sección y su estado de asistencia para la fecha seleccionada.
**Post condiciones:**
*   El usuario ha consultado la información de asistencia deseada.

**Flujo alternativo 1: Sin resultados**
*   **Precondición:** No existen registros de asistencia que coincidan con los filtros aplicados.
*   **Acciones:**
    1.  El usuario aplica filtros que no arrojan resultados.
    2.  El sistema muestra un mensaje en la tabla: "No hay registros de asistencia con estos filtros".

---

**Nombre de caso de uso:** Modificar Asistencia

**Descripción:**
Permite a un usuario autorizado (Profesor, Coordinador) editar un registro de asistencia existente. Esto es útil para corregir errores o actualizar el estado de un estudiante (por ejemplo, si presenta una justificación tardía).

**Actores:**
*   Profesor
*   Coordinador

**Precondiciones:**
*   El usuario debe haber iniciado sesión.
*   Debe existir al menos un registro de asistencia.

**Activación:**
Desde la lista de asistencias, el usuario hace clic en el botón "Modificar" en el registro que desea editar.

**Flujo normal:**
1.  **Acciones del Actor:**
    1.  Busca y localiza el registro de asistencia que necesita modificar.
    2.  Hace clic en el botón "Modificar".
    3.  El sistema abre una vista de edición con la lista de estudiantes y su estado de asistencia actual.
    4.  Cambia el estado de uno o más estudiantes.
    5.  Si cambia un estado a "Justificado", añade o edita la nota de justificación.
    6.  Hace clic en el botón "Actualizar Asistencia".
2.  **Respuesta del sistema:**
    1.  Valida los datos.
    2.  Actualiza los registros de asistencia en la base de datos.
    3.  Muestra un mensaje de éxito: "Asistencia actualizada correctamente".
    4.  Cierra la vista de edición y refresca la lista de asistencias.
**Post condiciones:**
*   El registro de asistencia ha sido actualizado con la nueva información.

---

**Nombre de caso de uso:** Eliminar Asistencia

**Descripción:**
Permite a un usuario autorizado eliminar un registro de asistencia completo para una fecha y sección específicas. Esta acción es irreversible.

**Actores:**
*   Coordinador
*   Administrador

**Precondiciones:**
*   El usuario debe haber iniciado sesión.
*   Debe existir al menos un registro de asistencia.

**Activación:**
Desde la lista de asistencias, el usuario hace clic en el botón "Eliminar" en el registro que desea borrar.

**Flujo normal:**
1.  **Acciones del Actor:**
    1.  Localiza el registro de asistencia que desea eliminar.
    2.  Hace clic en el botón "Eliminar".
    3.  El sistema muestra un diálogo de confirmación para prevenir la eliminación accidental.
    4.  Confirma la eliminación.
2.  **Respuesta del sistema:**
    1.  Elimina todos los registros de asistencia asociados a esa fecha y sección de la base de datos.
    2.  Muestra un mensaje de éxito: "Registros eliminados correctamente".
    3.  Elimina la fila correspondiente de la tabla de asistencias.
**Post condiciones:**
*   El registro de asistencia para la fecha y sección seleccionadas ha sido eliminado permanentemente.

**Flujo alternativo 1: Cancelar eliminación**
*   **Precondición:** El diálogo de confirmación está visible.
*   **Acciones:**
    1.  El usuario hace clic en el botón "Cancelar" o cierra el diálogo.
    2.  El sistema cancela la operación y no se realiza ningún cambio.
