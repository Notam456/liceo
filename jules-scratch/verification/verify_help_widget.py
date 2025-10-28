
from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        page.goto("http://localhost:8080/index.php")
        page.click("text=Iniciar Sesión")
        page.fill("input[name='usuario']", "administrador")
        page.fill("input[name='contrasena']", "Hola1234!")
        page.click("button[type='submit']")
        page.wait_for_url("http://localhost:8080/main.php")

        page.goto("http://localhost:8080/controladores/profesor_controlador.php")

        # Click the help button to make the panel visible
        page.click("#btnAyuda")

        # Wait for the panel to be visible
        page.wait_for_selector("#panelAyuda.show")

        page.screenshot(path="jules-scratch/verification/verification.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)
