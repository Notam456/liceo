**Nombre de caso de uso:** Registrar Asistencia
**Descripción:** Permite a un profesor registrar la asistencia de los estudiantes de una sección para una fecha específica.
**Actores:** Profesor
**Precondiciones:** El profesor debe haber iniciado sesión en el sistema. Deben existir grados, secciones y estudiantes cargados en el sistema. Debe existir un año académico activo.
**Activación:** El profesor selecciona la opción "Registrar Asistencia" en el módulo de asistencia.

**Flujo normal**
**Acciones del Actor:**
1.  Selecciona la fecha para la cual desea registrar la asistencia.
2.  Selecciona el grado y la sección.
3.  El sistema muestra la lista de estudiantes de la sección.
4.  Para cada estudiante, selecciona las materias a las que asistió.
5.  Si un estudiante está ausente, puede dejar todas las materias sin seleccionar y, opcionalmente, agregar una justificación.
6.  Hace clic en "Guardar Asistencia".
**Respuesta del sistema:**
1.  El sistema valida que la fecha y la sección hayan sido seleccionadas.
2.  Verifica si ya existe un registro de asistencia para esa fecha y sección.
3.  Verifica si hay materias cargadas en el horario para el día de la semana correspondiente a la fecha seleccionada.
4.  Almacena la información de la asistencia en la base de datos.
5.  Muestra un mensaje de confirmación.
**Post condiciones:** La asistencia de los estudiantes para la fecha y sección seleccionadas queda registrada en el sistema.

**Flujo alternativo 1**
**Precondicion:** Ya existe un registro de asistencia para la fecha y sección seleccionadas.
**Acciones:**
1.  El sistema muestra un mensaje de advertencia indicando que ya existe un registro y no permite continuar.

**Flujo alternativo 2**
**Precondicion:** No hay materias registradas en el horario para el día de la semana de la fecha seleccionada.
**Acciones:**
1.  El sistema muestra un mensaje de error indicando que no se pueden registrar asistencias porque no hay materias asignadas a ese día.

---

**Nombre de caso de uso:** Consultar Detalle de Asistencia
**Descripción:** Permite a un usuario consultar el detalle de un registro de asistencia, viendo el estado (presente, ausente, justificado) de cada estudiante y las materias a las que asistió.
**Actores:** Administrador, Profesor
**Precondiciones:** El usuario debe haber iniciado sesión. Debe existir al menos un registro de asistencia.
**Activación:** El usuario hace clic en el botón "Consultar" en la lista de registros de asistencia.

**Flujo normal**
**Acciones del Actor:**
1.  Hace clic en el botón "Consultar" de un registro de asistencia existente.
**Respuesta del sistema:**
1.  Muestra una ventana modal con la lista de estudiantes de la sección para la fecha seleccionada.
2.  Para cada estudiante, muestra su estado (Presente, Ausente o Justificado).
3.  Si el estudiante estuvo presente, muestra las materias a las que asistió.
4.  Si el estudiante estuvo justificado, muestra la justificación.
**Post condiciones:** El usuario ha visualizado el detalle de la asistencia.

---

**Nombre de caso de uso:** Modificar Asistencia
**Descripción:** Permite a un profesor modificar un registro de asistencia existente.
**Actores:** Profesor
**Precondiciones:** El profesor debe haber iniciado sesión. Debe existir al menos un registro de asistencia.
**Activación:** El profesor hace clic en el botón "Modificar" en la lista de registros de asistencia.

**Flujo normal**
**Acciones del Actor:**
1.  Hace clic en el botón "Modificar" de un registro de asistencia.
2.  El sistema muestra una ventana modal con la lista de estudiantes y la asistencia previamente registrada.
3.  Modifica las materias a las que asistió un estudiante o cambia su estado a justificado, agregando una nota.
4.  Hace clic en "Guardar Cambios".
**Respuesta del sistema:**
1.  Actualiza la información de la asistencia en la base de datos.
2.  Muestra un mensaje de confirmación.
**Post condiciones:** La asistencia de los estudiantes para la fecha y sección seleccionadas queda actualizada.

---

**Nombre de caso de uso:** Eliminar Asistencia
**Descripción:** Permite a un usuario eliminar todos los registros de asistencia de una fecha y sección específicas.
**Actores:** Administrador, Profesor
**Precondiciones:** El usuario debe haber iniciado sesión. Debe existir al menos un registro de asistencia.
**Activación:** El usuario hace clic en el botón "Eliminar" en la lista de registros de asistencia.

**Flujo normal**
**Acciones del Actor:**
1.  Hace clic en el botón "Eliminar" de un registro de asistencia.
2.  El sistema solicita confirmación.
3.  El usuario confirma la eliminación.
**Respuesta del sistema:**
1.  Elimina todos los registros de asistencia asociados a esa fecha y sección de la base de datos.
2.  Muestra un mensaje de confirmación.
**Post condiciones:** Los registros de asistencia para la fecha y sección seleccionadas son eliminados del sistema.

**Flujo alternativo 1**
**Precondicion:** El usuario cancela la eliminación.
**Acciones:**
1.  El sistema cierra el cuadro de diálogo de confirmación y no realiza ninguna acción.

---

**Nombre de caso de uso:** Filtrar Registros de Asistencia
**Descripción:** Permite a un usuario buscar y filtrar los registros de asistencia por fecha, grado y/o sección.
**Actores:** Administrador, Profesor
**Precondiciones:** El usuario debe haber iniciado sesión.
**Activación:** El usuario utiliza los campos de filtro en la pantalla de asistencia.

**Flujo normal**
**Acciones del Actor:**
1.  Selecciona una fecha, un grado o una sección en los campos de filtro.
2.  Hace clic en el botón "Buscar".
**Respuesta del sistema:**
1.  Actualiza la tabla de registros de asistencia mostrando solo aquellos que coinciden con los criterios de búsqueda.
**Post condiciones:** El usuario visualiza una lista filtrada de los registros de asistencia.

**Flujo alternativo 1**
**Precondicion:** No se encuentran registros que coincidan con los filtros.
**Acciones:**
1.  El sistema muestra un mensaje en la tabla indicando que no se encontraron resultados.
