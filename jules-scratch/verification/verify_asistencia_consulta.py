from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Login
    page.goto("http://localhost:8080/liceo/index.php")
    page.click('button[data-bs-target="#modalId"]')
    page.wait_for_selector('#modalId.show')
    page.fill('input[name="usuario"]', 'administrador')
    page.fill('input[name="contrasena"]', 'Hola1234!')
    page.click('form[action="verificar_login.php"] button[type="submit"]')

    # Go to attendance page
    page.goto("http://localhost:8080/liceo/controladores/asistencia_controlador.php")

    # Click the "Consultar" button for the first attendance record
    page.click('button.btn-warning:first-of-type')

    # Wait for the modal to appear
    page.wait_for_selector('#consultarDetalleModal.show')

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/asistencia_consulta_verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
