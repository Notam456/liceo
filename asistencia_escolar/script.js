// Define la URL base de tu API PHP.
// ASEGÚRATE DE QUE ESTA RUTA ES CORRECTA EN TU SERVIDOR XAMPP.
// Ejemplo: Si tus archivos están en 'htdocs/mi_proyecto/', la URL podría ser:
// const API_URL = 'http://localhost/mi_proyecto/api.php';
// O si está en la misma carpeta que attendance.html:
const API_URL = 'http://localhost/liceo/asistencia_escolar/api.php';

// Datos de estudiantes (simulados, idealmente se cargarían desde tu DB MySQL vía PHP)
// En una aplicación real, esta lista debería obtenerse de tu base de datos MySQL
// (por ejemplo, mediante una nueva función GET en api.php para estudiantes).
const estudiantes = [
    { id_estudiante: 1, nombre_estudiante: 'José Felix', apellido_estudiante: 'Yajure Arrieche', seccion_estudiante: 'B', año_academico: '04-2025' },
    { id_estudiante: 2, nombre_estudiante: 'Roberto Carlos', apellido_estudiante: 'Vielma Quevedo', seccion_estudiante: 'A', año_academico: '05-2025' },
    { id_estudiante: 3, nombre_estudiante: 'Yhoenyer Alexander', apellido_estudiante: 'Alvarado Fernández', seccion_estudiante: 'B', año_academico: '04-2025' },
    { id_estudiante: 4, nombre_estudiante: 'Jose Luis', apellido_estudiante: 'Peralta', seccion_estudiante: 'C', año_academico: '02-2025' },
    { id_estudiante: 5, nombre_estudiante: 'Jose Antonio', apellido_estudiante: 'González', seccion_estudiante: 'C', año_academico: '02-2025' },
    { id_estudiante: 12, nombre_estudiante: 'Pepito Alberto', apellido_estudiante: 'Camaron Rodriguez', seccion_estudiante: 'D', año_academico: '01-2025' }
];

// Obtener secciones únicas de los estudiantes para el selector
const secciones = [...new Set(estudiantes.map(e => e.seccion_estudiante))].sort();

// Variable global para almacenar los registros de asistencia obtenidos de la API
let asistencias = [];

/**
 * Muestra un mensaje de notificación en la esquina superior derecha.
 * @param {string} message - El mensaje a mostrar.
 * @param {string} type - 'success' para verde, 'error' para rojo.
 */
function showMessage(message, type = 'success') {
    const messageBox = document.getElementById('messageBox');
    messageBox.textContent = message;
    messageBox.className = 'message-box show';
    if (type === 'error') {
        messageBox.classList.add('error');
    } else {
        messageBox.classList.remove('error');
    }

    setTimeout(() => {
        messageBox.classList.remove('show');
    }, 3000);
}

/**
 * Muestra un modal de confirmación personalizado.
 * @param {string} message - El mensaje de confirmación.
 * @returns {Promise<boolean>} Resuelve a true si se confirma, false si se cancela.
 */
let resolveConfirmationPromise;
function showConfirmation(message) {
    const modal = document.getElementById('confirmationModal');
    const msgElem = document.getElementById('confirmationMessage');
    const okBtn = document.getElementById('confirmOk');
    const cancelBtn = document.getElementById('confirmCancel');

    msgElem.textContent = message;
    modal.classList.add('show');

    return new Promise((resolve) => {
        resolveConfirmationPromise = resolve;
        okBtn.onclick = () => {
            modal.classList.remove('show');
            resolveConfirmationPromise(true);
        };
        cancelBtn.onclick = () => {
            modal.classList.remove('show');
            resolveConfirmationPromise(false);
        };
    });
}

/**
 * Carga las secciones en el menú desplegable.
 */
function loadSections() {
    const seccionSelect = document.getElementById('seccionSelect');
    secciones.forEach(seccion => {
        const option = document.createElement('option');
        option.value = seccion;
        option.textContent = seccion;
        seccionSelect.appendChild(option);
    });
}

/**
 * Filtra estudiantes por la sección seleccionada.
 * @param {string} selectedSection - La sección para filtrar.
 * @returns {Array} - Array de estudiantes en la sección seleccionada.
 */
function filterStudentsBySection(selectedSection) {
    return estudiantes.filter(est => est.seccion_estudiante === selectedSection);
}

/**
 * Obtiene todos los registros de asistencia del backend.
 */
async function fetchAllAttendance() {
    try {
        const response = await fetch(API_URL + '?action=get');
        const result = await response.json();
        console.log(result.data);
        if (result.success) {
            asistencias = result.data;
            renderAttendanceTable();
        } else {
            showMessage('Error al cargar asistencias: ' + result.message, 'error');
            console.error('Error fetching all attendance:', result.message);
        }
    } catch (error) {
        showMessage('Error de red al cargar asistencias. Asegúrate de que tu servidor XAMPP y api.php están funcionando correctamente.', 'error');
        console.error('Network error fetching all attendance:', error);
    }
}

/**
 * Renderiza la tabla de entrada de asistencia para una sección y fecha específica.
 * Carga datos existentes si los hay para esa fecha y estudiantes.
 */
async function renderStudentAttendanceInputTable() {
    const fecha = document.getElementById('fechaAsistencia').value;
    const seccion = document.getElementById('seccionSelect').value;
    const studentAttendanceInputTableBody = document.getElementById('studentAttendanceInputTableBody');
    const bulkAttendanceTableContainer = document.getElementById('bulkAttendanceTableContainer');
    const noStudentsMessage = document.getElementById('noStudentsInSectionMessage');
    studentAttendanceInputTableBody.innerHTML = '';

    if (!fecha || !seccion) {
        bulkAttendanceTableContainer.classList.add('hidden');
        noStudentsMessage.classList.add('hidden');
        return;
    }

    const studentsInSection = filterStudentsBySection(seccion);

    if (studentsInSection.length === 0) {
        bulkAttendanceTableContainer.classList.add('hidden');
        noStudentsMessage.classList.remove('hidden');
        return;
    } else {
        bulkAttendanceTableContainer.classList.remove('hidden');
        noStudentsMessage.classList.add('hidden');
    }

    let existingAttendanceMap = new Map();
    try {
        const response = await fetch(`${API_URL}?action=get&fecha=${fecha}`);
        const result = await response.json();
        if (result.success) {
            result.data.forEach(record => {
                if (studentsInSection.some(s => s.id_estudiante === parseInt(record.estudiante_id))) {
                    existingAttendanceMap.set(parseInt(record.estudiante_id), record);
                }
            });
        } else {
            console.error('Error fetching existing attendance for section:', result.message);
        }
    } catch (error) {
        console.error('Network error fetching existing attendance for section:', error);
    }

    studentsInSection.forEach(estudiante => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.dataset.studentId = estudiante.id_estudiante;

        const existingRecord = existingAttendanceMap.get(estudiante.id_estudiante);
        const isPresent = existingRecord ? (existingRecord.estado === 'Presente') : true;
        const estadoValue = existingRecord ? existingRecord.estado : 'Ausente';
        const comentariosValue = existingRecord ? existingRecord.comentarios : '';
        const commentsRequiredClass = (existingRecord && existingRecord.estado !== 'Presente' && !existingRecord.comentarios) ? 'required-textarea' : '';

        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${estudiante.nombre_estudiante} ${estudiante.apellido_estudiante}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <input type="checkbox" class="attendance-present-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${isPresent ? 'checked' : ''}>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <select class="attendance-estado-select block w-full p-2 border border-gray-300 rounded-md shadow-sm bg-white text-gray-900 appearance-none ${isPresent ? 'hidden' : ''}">
                    <option value="Ausente" ${estadoValue === 'Ausente' ? 'selected' : ''}>Ausente</option>
                    <option value="Retardo" ${estadoValue === 'Retardo' ? 'selected' : ''}>Retardo</option>
                    <option value="Justificada" ${estadoValue === 'Justificada' ? 'selected' : ''}>Justificada</option>
                </select>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
                <textarea class="attendance-comentarios-textarea block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 ${isPresent ? 'hidden' : commentsRequiredClass}" rows="1" placeholder="Comentarios"></textarea>
            </td>
        `;
        studentAttendanceInputTableBody.appendChild(row);

        const presentCheckbox = row.querySelector('.attendance-present-checkbox');
        const estadoSelect = row.querySelector('.attendance-estado-select');
        const comentariosTextarea = row.querySelector('.attendance-comentarios-textarea');

        comentariosTextarea.value = comentariosValue;

        presentCheckbox.addEventListener('change', () => {
            if (presentCheckbox.checked) {
                estadoSelect.classList.add('hidden');
                comentariosTextarea.classList.add('hidden');
                comentariosTextarea.classList.remove('required-textarea');
                estadoSelect.value = 'Presente';
            } else {
                estadoSelect.classList.remove('hidden');
                comentariosTextarea.classList.remove('hidden');
                comentariosTextarea.classList.add('required-textarea');
                estadoSelect.value = 'Ausente';
            }
        });

        estadoSelect.addEventListener('change', () => {
            if (estadoSelect.value === 'Presente') {
                presentCheckbox.checked = true;
                estadoSelect.classList.add('hidden');
                comentariosTextarea.classList.add('hidden');
                comentariosTextarea.classList.remove('required-textarea');
            } else {
                presentCheckbox.checked = false;
                estadoSelect.classList.remove('hidden');
                comentariosTextarea.classList.remove('hidden');
                comentariosTextarea.classList.add('required-textarea');
            }
        });
    });
}

/**
 * Renderiza la tabla principal de asistencia con los datos actuales.
 */
function renderAttendanceTable() {
    const attendanceTableBody = document.getElementById('attendanceTableBody');
    const noRecordsMessage = document.getElementById('noRecordsMessage');
    attendanceTableBody.innerHTML = '';

    if (asistencias.length === 0) {
        noRecordsMessage.classList.remove('hidden');
        return;
    } else {
        noRecordsMessage.classList.add('hidden');
    }

    const sortedAsistencias = [...asistencias].sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    sortedAsistencias.forEach(record => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';

        const estudiante = estudiantes.find(e => e.id_estudiante === parseInt(record.estudiante_id));
        const nombreCompleto = estudiante ? `${estudiante.nombre_estudiante} ${estudiante.apellido_estudiante}` : 'Desconocido';

        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${nombreCompleto}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${record.fecha}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm ${
                record.estado === 'Presente' ? 'text-green-600 font-semibold' :
                record.estado === 'Ausente' ? 'text-red-600 font-semibold' :
                record.estado === 'Retardo' ? 'text-orange-600 font-semibold' :
                'text-blue-600 font-semibold'
            }">${record.estado}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${record.comentarios || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button onclick="editSingleAttendance('${record.id}')" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</button>
                <button onclick="deleteAttendance('${record.id}')" class="text-red-600 hover:text-red-900">Eliminar</button>
            </td>
        `;
        attendanceTableBody.appendChild(row);
    });
}

/**
 * Pre-rellena el formulario de actualización individual con datos de un registro existente.
 * Oculta el formulario de asistencia masiva.
 * @param {string} recordId - El ID del registro de asistencia a editar.
 */
function editSingleAttendance(recordId) {
    const recordToEdit = asistencias.find(rec => rec.id == recordId);
    if (recordToEdit) {
        document.getElementById('sectionSelectionForm').classList.add('hidden');
        document.getElementById('bulkAttendanceTableContainer').classList.add('hidden');
        document.getElementById('noStudentsInSectionMessage').classList.add('hidden');

        document.getElementById('singleAttendanceUpdateForm').classList.remove('hidden');

        document.getElementById('updateRecordId').value = recordId;
        document.getElementById('updateEstudianteId').value = recordToEdit.estudiante_id;

        const estudiante = estudiantes.find(e => e.id_estudiante === parseInt(recordToEdit.estudiante_id));
        document.getElementById('updateStudentDisplay').textContent = estudiante ? `${estudiante.nombre_estudiante} ${estudiante.apellido_estudiante}` : 'Estudiante Desconocido';

        document.getElementById('updateFecha').value = recordToEdit.fecha;
        document.getElementById('updateEstado').value = recordToEdit.estado;
        document.getElementById('updateComentarios').value = recordToEdit.comentarios;
    }
}

/**
 * Elimina un registro de asistencia del backend.
 * @param {string} recordId - El ID del registro de asistencia a eliminar.
 */
async function deleteAttendance(recordId) {
    const confirmed = await showConfirmation('¿Está seguro de que desea eliminar este registro de asistencia?');
    if (confirmed) {
        try {
            const response = await fetch(`${API_URL}?action=delete&id=${recordId}`, {
                method: 'DELETE'
            });
            const result = await response.json();
            if (result.success) {
                showMessage('Registro de asistencia eliminado exitosamente.', 'success');
                fetchAllAttendance();
            } else {
                showMessage('Error al eliminar asistencia: ' + result.message, 'error');
                console.error('Error deleting attendance:', result.message);
            }
        } catch (error) {
            showMessage('Error de red al eliminar asistencia. Asegúrate de que tu servidor XAMPP y api.php están funcionando correctamente.', 'error');
            console.error('Network error deleting attendance:', error);
        }
    }
}

// Manejar el envío del formulario de asistencia masiva
document.getElementById('saveBulkAttendanceButton').addEventListener('click', async function() {
    const fecha = document.getElementById('fechaAsistencia').value;
    const seccion = document.getElementById('seccionSelect').value;

    if (!fecha || !seccion) {
        showMessage('Por favor, seleccione una fecha y una sección.', 'error');
        return;
    }

    const studentRows = document.querySelectorAll('#studentAttendanceInputTableBody tr');
    const attendanceDataToSave = [];
    const errors = [];

    const existingAttendanceMap = new Map();
    try {
        const response = await fetch(`${API_URL}?action=get&fecha=${fecha}`);
        const result = await response.json();
        if (result.success) {
            result.data.forEach(record => {
                existingAttendanceMap.set(parseInt(record.estudiante_id), record);
            });
        }
    } catch (error) {
        console.error('Error fetching existing attendance for bulk save:', error);
    }

    const recordsToUpdate = [];
    const recordsToCreate = [];

    studentRows.forEach(row => {
        const studentId = parseInt(row.dataset.studentId);
        const isPresentCheckbox = row.querySelector('.attendance-present-checkbox');
        const estadoSelect = row.querySelector('.attendance-estado-select');
        const comentariosTextarea = row.querySelector('.attendance-comentarios-textarea');

        const isPresent = isPresentCheckbox.checked;
        let estado = isPresent ? 'Presente' : estadoSelect.value;
        const comentarios = comentariosTextarea.value.trim();

        if (!isPresent && !comentarios) {
            errors.push(`El comentario es requerido para el estudiante ${estudiantes.find(e => e.id_estudiante === studentId)?.nombre_completo || 'Desconocido'} en estado ${estado}.`);
            comentariosTextarea.classList.add('required-textarea');
        } else {
            comentariosTextarea.classList.remove('required-textarea');
        }

        const record = {
            estudiante_id: studentId,
            fecha: fecha,
            estado: estado,
            comentarios: comentarios || null
        };

        if (existingAttendanceMap.has(studentId)) {
            const existingRecord = existingAttendanceMap.get(studentId);
            recordsToUpdate.push({ id: existingRecord.id, ...record });
        } else {
            recordsToCreate.push(record);
        }
    });

    if (errors.length > 0) {
        showMessage('Por favor, corrija los siguientes errores:\n' + errors.join('\n'), 'error');
        return;
    }

    try {
        let allSuccess = true;

        if (recordsToCreate.length > 0) {
            const response = await fetch(API_URL + '?action=create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(recordsToCreate)
            });
            const result = await response.json();
            if (!result.success) {
                allSuccess = false;
                showMessage('Error al crear asistencias: ' + result.message, 'error');
                console.error('Error creating attendance in bulk:', result.message);
            }
        }

        for (const record of recordsToUpdate) {
            const response = await fetch(`${API_URL}?action=update&id=${record.id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(record)
            });
            const result = await response.json();
            if (!result.success) {
                allSuccess = false;
                showMessage(`Error al actualizar asistencia para estudiante ${estudiantes.find(e => e.id_estudiante === record.estudiante_id)?.nombre_completo || record.estudiante_id}: ${result.message}`, 'error');
                console.error('Error updating attendance in bulk:', result.message);
            }
        }

        if (allSuccess) {
            showMessage('Asistencia(s) guardada(s) exitosamente.', 'success');
            await fetchAllAttendance();
            await renderStudentAttendanceInputTable();
        } else {
             showMessage('Hubo errores al guardar algunas asistencias.', 'error');
        }

    } catch (e) {
        showMessage('Error de red al guardar asistencia de la sección: ' + e.message, 'error');
        console.error("Network error saving bulk attendance: ", e);
    }
});

// Manejar el envío del formulario de actualización individual
document.getElementById('updateForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const recordId = document.getElementById('updateRecordId').value;
    const estudianteId = parseInt(document.getElementById('updateEstudianteId').value);
    const fecha = document.getElementById('updateFecha').value;
    const estado = document.getElementById('updateEstado').value;
    const comentarios = document.getElementById('updateComentarios').value;

    if (!fecha) {
        showMessage('Por favor, seleccione una fecha.', 'error');
        return;
    }

    const updatedRecord = {
        estudiante_id: estudianteId,
        fecha: fecha,
        estado: estado,
        comentarios: comentarios || null
    };

    try {
        const response = await fetch(`${API_URL}?action=update&id=${recordId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedRecord)
        });
        const result = await response.json();
        if (result.success) {
            showMessage('Asistencia actualizada exitosamente.', 'success');
            resetUpdateForm();
            fetchAllAttendance();
        } else {
            showMessage('Error al actualizar asistencia: ' + result.message, 'error');
            console.error('Error updating single attendance:', result.message);
        }
    } catch (error) {
        showMessage('Error de red al actualizar asistencia. Asegúrate de que tu servidor XAMPP y api.php están funcionando correctamente.', 'error');
        console.error('Network error updating single attendance:', error);
    }
});

// Manejar el botón de cancelar actualización
document.getElementById('cancelUpdateFormButton').addEventListener('click', function() {
    resetUpdateForm();
    showMessage('Edición cancelada.', 'info');
});

/**
 * Resetea el formulario de actualización individual y muestra el formulario masivo.
 */
function resetUpdateForm() {
    document.getElementById('singleAttendanceUpdateForm').classList.add('hidden');
    document.getElementById('updateForm').reset();
    document.getElementById('sectionSelectionForm').classList.remove('hidden');
    document.getElementById('bulkAttendanceTableContainer').classList.remove('hidden');
    renderStudentAttendanceInputTable();
}


// Event listeners para cambios de sección y fecha
document.getElementById('seccionSelect').addEventListener('change', renderStudentAttendanceInputTable);
document.getElementById('fechaAsistencia').addEventListener('change', renderStudentAttendanceInputTable);

// Inicializar la aplicación cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    loadSections();
    fetchAllAttendance();
});
