class RegistroForm {
    constructor() {
        this.form = document.getElementById('registroForm');
        this.init();
    }

    init() {
        if (this.form) {
            this.form.addEventListener('submit', this.handleSubmit.bind(this));
            this.setupValidation();
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateForm()) {
            return;
        }

        const formData = this.getFormData();

        try {
            await this.submitForm(formData);
        } catch (error) {
            this.showError('Error al procesar el registro');
        }
    }

    getFormData() {
        return {
            nombre: document.getElementById('nombre').value.trim(),
            correo: document.getElementById('correo').value.trim(),
            password: document.getElementById('password').value,
            tipoUsuario: document.getElementById('tipoUsuario').value
        };
    }

    async submitForm(data) {
        const response = await fetch('/registro', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.exito) {
            this.showSuccess(result.mensaje);
            this.form.reset();
        } else {
            this.showErrors(result.errores);
        }
    }

    validateForm() {
        // Lógica de validación aquí
        return true;
    }

    setupValidation() {
        // Configurar validación en tiempo real
    }

    showSuccess(message) {
        const successDiv = document.getElementById('successMessage');
        if (successDiv) {
            successDiv.textContent = message;
            successDiv.classList.remove('hidden');
        }
    }

    showError(message) {
        alert(message); // Simplificado para el ejemplo
    }

    showErrors(errors) {
        errors.forEach(error => {
            console.error(error);
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new RegistroForm();
});