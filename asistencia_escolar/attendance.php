<?php
session_start();
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Asistencia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/head.php'); ?>
</head>


<body>

    <nav>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/navbar.php') ?>
    </nav>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/sidebar.php') ?>
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Gestión de Asistencia Estudiantil</h1>


        <div class="mb-8 p-6 bg-blue-50 rounded-lg shadow-md border border-blue-200">
            <h2 class="text-2xl font-semibold text-blue-700 mb-5">Registrar Asistencia por Sección</h2>
            <div id="sectionSelectionForm" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="mb-4">
                    <label for="fechaAsistencia" class="block text-gray-700 text-sm font-medium mb-2">Fecha de Asistencia:</label>
                    <input type="date" id="fechaAsistencia" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                </div>
                <div class="mb-4">
                    <label for="seccionSelect" class="block text-gray-700 text-sm font-medium mb-2">Seleccionar Sección:</label>
                    <select id="seccionSelect" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 appearance-none">
                        <option value="">-- Seleccionar Sección --</option>

                    </select>
                </div>
            </div>


            <div id="bulkAttendanceTableContainer" class="hidden">
                <div class="overflow-x-auto rounded-lg border border-gray-300 mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presente</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comentarios</th>
                            </tr>
                        </thead>
                        <tbody id="studentAttendanceInputTableBody" class="bg-white divide-y divide-gray-200">

                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="saveBulkAttendanceButton" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Guardar Asistencia de la Sección
                    </button>
                </div>
            </div>

            <p id="noStudentsInSectionMessage" class="text-center text-gray-500 py-4 hidden">No hay estudiantes en esta sección para la fecha seleccionada.</p>

        </div>


        <div id="singleAttendanceUpdateForm" class="mb-8 p-6 bg-green-50 rounded-lg shadow-md border border-green-200 hidden">
            <h2 class="text-2xl font-semibold text-green-700 mb-5">Actualizar Registro de Asistencia</h2>
            <form id="updateForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4 md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Estudiante:</label>
                    <p id="updateStudentDisplay" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-900"></p>
                    <input type="hidden" id="updateRecordId" value="">
                    <input type="hidden" id="updateEstudianteId" value="">
                </div>
                <div class="mb-4">
                    <label for="updateFecha" class="block text-gray-700 text-sm font-medium mb-2">Fecha:</label>
                    <input type="date" id="updateFecha" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900" required>
                </div>
                <div class="mb-4">
                    <label for="updateEstado" class="block text-gray-700 text-sm font-medium mb-2">Estado:</label>
                    <select id="updateEstado" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 bg-white text-gray-900 appearance-none" required>
                        <option value="Presente">Presente</option>
                        <option value="Ausente">Ausente</option>
                        <option value="Retardo">Retardo</option>
                        <option value="Justificada">Justificada</option>
                    </select>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="updateComentarios" class="block text-gray-700 text-sm font-medium mb-2">Comentarios (Opcional):</label>
                    <textarea id="updateComentarios" rows="3" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 text-gray-900"></textarea>
                </div>
                <div class="md:col-span-2 flex justify-end space-x-4">
                    <button type="button" id="cancelUpdateFormButton" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>



        <div class="p-6 bg-white rounded-lg shadow-md border border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-700 mb-5">Asistencia Registrada</h2>
            <div class="overflow-x-auto rounded-lg border border-gray-300">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comentarios</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody" class="bg-white divide-y divide-gray-200">

                    </tbody>
                </table>
            </div>

            <p id="noRecordsMessage" class="text-center text-gray-500 py-4 hidden">No hay registros de asistencia para mostrar.</p>
        </div>
    </div>


    <div id="messageBox" class="message-box"></div>


    <div id="confirmationModal" class="modal-overlay hidden">
        <div class="modal-content">
            <p class="text-lg font-semibold text-gray-800 mb-4" id="confirmationMessage"></p>
            <div class="flex justify-end space-x-4">
                <button id="confirmCancel" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancelar</button>
                <button id="confirmOk" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">Confirmar</button>
            </div>
        </div>
    </div>


    <script src="script.js"></script>


    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/liceo/includes/footer.php') ?>
    </footer>

</body>

</html>