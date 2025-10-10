from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    # Go to the index page
    page.goto("http://localhost:8080/liceo/index.php")

    # Click the "Iniciar Sesión" button to open the modal
    page.click('button[data-bs-target="#modalId"]')

    # Wait for the modal to be visible
    page.wait_for_selector('#modalId.show')

    # Fill in the login form
    page.fill('input[name="usuario"]', 'administrador')
    page.fill('input[name="contrasena"]', 'Hola1234!')
    page.click('form[action="verificar_login.php"] button[type="submit"]')

    # Go to sections page
    page.goto("http://localhost:8080/liceo/controladores/seccion_controlador.php")

    # Click the "Consultar" button for the first section
    page.click('a.view-data:first-of-type')

    # Wait for the modal to appear
    page.wait_for_selector('#viewmodal.show')

    # Click the "Horario" button inside the modal
    page.click('#viewmodal .modal-body a.btn-primary:has-text("Horario")')

    # Wait for the schedule page to load
    page.wait_for_url("**/horario_controlador.php**")

    # Take a screenshot
    page.screenshot(path="jules-scratch/verification/horario_button_verification.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
