    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $titulo ?? 'Registro de Usuario' ?></title>
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome para iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            
            .registro-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                padding: 20px 0;
            }
            
            .registro-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                overflow: hidden;
                transition: transform 0.3s ease;
            }
            
            .registro-card:hover {
                transform: translateY(-5px);
            }
            
            .card-header {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
                padding: 2rem;
                text-align: center;
                border: none;
            }
            
            .card-header h1 {
                margin: 0;
                font-size: 2rem;
                font-weight: 300;
            }
            
            .card-header p {
                margin: 0.5rem 0 0 0;
                opacity: 0.9;
            }
            
            .form-floating {
                margin-bottom: 1rem;
            }
            
            .form-control {
                border: 2px solid #e1e5e9;
                border-radius: 12px;
                padding: 1rem;
                transition: all 0.3s ease;
                background: rgba(255, 255, 255, 0.8);
            }
            
            .form-control:focus {
                border-color: #4f46e5;
                box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
                background: white;
            }
            
            .form-select {
                border: 2px solid #e1e5e9;
                border-radius: 12px;
                padding: 1rem;
                background: rgba(255, 255, 255, 0.8);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                border: none;
                border-radius: 12px;
                padding: 1rem 2rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                width: 100%;
            }
            
            .btn-primary:hover {
                background: linear-gradient(135deg, #3730a3, #5b21b6);
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
            }
            
            .alert {
                border-radius: 12px;
                border: none;
                margin-bottom: 1.5rem;
            }
            
            .alert-success {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
            }
            
            .alert-danger {
                background: linear-gradient(135deg, #ef4444, #dc2626);
                color: white;
            }
            
            .input-group-text {
                background: linear-gradient(135deg, #4f46e5, #7c3aed);
                color: white;
                border: none;
                border-radius: 12px 0 0 12px;
            }
            
            .password-toggle {
                cursor: pointer;
                background: #f8f9fa;
                border: 2px solid #e1e5e9;
                border-left: none;
                border-radius: 0 12px 12px 0;
            }
            
            .password-toggle:hover {
                background: #e9ecef;
            }
            
            .footer {
                text-align: center;
                color: rgba(255, 255, 255, 0.8);
                padding: 2rem 0;
            }
            
            .loading {
                display: none;
            }
            
            .fade-in {
                animation: fadeIn 0.5s ease-in;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .is-invalid {
                border-color: #dc3545 !important;
            }
            
            .is-valid {
                border-color: #198754 !important;
            }
        </style>
    </head>
    <body>
        <div class="registro-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="registro-card fade-in">
                            <div class="card-header">
                                <h1><i class="fas fa-user-plus"></i> Registro</h1>
                                <p>Crea tu cuenta para comenzar</p>
                            </div>
                            
                            <div class="card-body p-4">
                                <!-- Mensajes de éxito -->
                                <?php if (!empty($mensaje_exito)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= htmlspecialchars($mensaje_exito) ?>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Mensajes de error -->
                                <?php if (!empty($mensaje_error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?= htmlspecialchars($mensaje_error) ?>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Lista de errores de validación -->
                                <?php if (!empty($errores)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Por favor, corrige los siguientes errores:</strong>
                                        <ul class="mb-0 mt-2">
                                            <?php foreach ($errores as $error): ?>
                                                <li><?= htmlspecialchars($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <form id="registroForm" method="POST" action="/registro" novalidate>
                                    <!-- Nombre -->
                                    <div class="form-floating mb-3">
                                        <input type="text" 
                                            class="form-control" 
                                            id="nombre" 
                                            name="nombre" 
                                            placeholder="Tu nombre completo"
                                            value="<?= htmlspecialchars($datos_previos['nombre'] ?? '') ?>"
                                            required>
                                        <label for="nombre"><i class="fas fa-user me-2"></i>Nombre completo</label>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <!-- Correo -->
                                    <div class="form-floating mb-3">
                                        <input type="email" 
                                            class="form-control" 
                                            id="correo" 
                                            name="correo" 
                                            placeholder="tu@email.com"
                                            value="<?= htmlspecialchars($datos_previos['correo'] ?? '') ?>"
                                            required>
                                        <label for="correo"><i class="fas fa-envelope me-2"></i>Correo electrónico</label>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <!-- Contraseña -->
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input type="password" 
                                                class="form-control" 
                                                id="password" 
                                                name="password" 
                                                placeholder="Contraseña"
                                                required>
                                            <label for="password">Contraseña</label>
                                        </div>
                                        <button type="button" class="btn password-toggle" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                        <div class="invalid-feedback w-100"></div>
                                    </div>
                                    
                                    <!-- Confirmar contraseña -->
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input type="password" 
                                                class="form-control" 
                                                id="confirmar_password" 
                                                name="confirmar_password" 
                                                placeholder="Confirmar contraseña"
                                                required>
                                            <label for="confirmar_password">Confirmar contraseña</label>
                                        </div>
                                        <button type="button" class="btn password-toggle" onclick="togglePassword('confirmar_password')">
                                            <i class="fas fa-eye" id="confirmar_password-icon"></i>
                                        </button>
                                        <div class="invalid-feedback w-100"></div>
                                    </div>
                                    
                                    <!-- Tipo de usuario -->
                                    <div class="form-floating mb-4">
                                        <select class="form-select" id="tipo_usuario" name="tipo_usuario" required>
                                            <option value="">Selecciona tipo de usuario</option>
                                            <option value="cliente" <?= ($datos_previos['tipo_usuario'] ?? '') === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                                            <option value="vendedor" <?= ($datos_previos['tipo_usuario'] ?? '') === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                                            <option value="administrador" <?= ($datos_previos['tipo_usuario'] ?? '') === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                        </select>
                                        <label for="tipo_usuario"><i class="fas fa-users me-2"></i>Tipo de usuario</label>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    
                                    <!-- Botón de envío -->
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <span class="submit-text">
                                            <i class="fas fa-user-plus me-2"></i>Crear cuenta
                                        </span>
                                        <span class="loading">
                                            <i class="fas fa-spinner fa-spin me-2"></i>Procesando...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="footer">
            <div class="container">
                <p>&copy; 2024 Sistema de Registro. Desarrollado con ❤️</p>
            </div>
        </footer>
        
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
            // Variables globales
            const form = document.getElementById('registroForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = submitBtn.querySelector('.submit-text');
            const loadingText = submitBtn.querySelector('.loading');
            
            // Inicializar cuando la página esté lista
            document.addEventListener('DOMContentLoaded', function() {
                initializeValidation();
                initializeFormSubmission();
            });
            
            // Función para mostrar/ocultar contraseñas
            function togglePassword(fieldId) {
                const field = document.getElementById(fieldId);
                const icon = document.getElementById(fieldId + '-icon');
                
                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
            
            // Inicializar validación en tiempo real
            function initializeValidation() {
                const fields = {
                    nombre: {
                        element: document.getElementById('nombre'),
                        validators: [
                            { check: (val) => val.trim().length >= 2, message: 'El nombre debe tener al menos 2 caracteres' },
                            { check: (val) => val.trim().length <= 50, message: 'El nombre no puede exceder 50 caracteres' }
                        ]
                    },
                    correo: {
                        element: document.getElementById('correo'),
                        validators: [
                            { check: (val) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val), message: 'Ingresa un correo electrónico válido' }
                        ]
                    },
                    password: {
                        element: document.getElementById('password'),
                        validators: [
                            { check: (val) => val.length >= 6, message: 'La contraseña debe tener al menos 6 caracteres' },
                            { check: (val) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(val), message: 'Debe contener al menos: 1 mayúscula, 1 minúscula y 1 número' }
                        ]
                    },
                    confirmar_password: {
                        element: document.getElementById('confirmar_password'),
                        validators: [
                            { check: (val) => val === document.getElementById('password').value, message: 'Las contraseñas no coinciden' }
                        ]
                    },
                    tipo_usuario: {
                        element: document.getElementById('tipo_usuario'),
                        validators: [
                            { check: (val) => ['cliente', 'vendedor', 'administrador'].includes(val), message: 'Selecciona un tipo de usuario válido' }
                        ]
                    }
                };
                
                // Agregar event listeners
                Object.keys(fields).forEach(fieldName => {
                    const field = fields[fieldName];
                    
                    field.element.addEventListener('blur', () => validateField(fieldName, fields));
                    field.element.addEventListener('input', () => {
                        // Validación en tiempo real con debounce
                        clearTimeout(field.timeout);
                        field.timeout = setTimeout(() => validateField(fieldName, fields), 300);
                    });
                });
                
                // Validación especial para confirmar contraseña
                document.getElementById('password').addEventListener('input', () => {
                    const confirmField = document.getElementById('confirmar_password');
                    if (confirmField.value) {
                        setTimeout(() => validateField('confirmar_password', fields), 100);
                    }
                });
            }
            
            // Validar campo individual
            function validateField(fieldName, fields) {
                const field = fields[fieldName];
                const element = field.element;
                const value = element.value.trim();
                const feedback = element.parentNode.querySelector('.invalid-feedback') || 
                            element.closest('.input-group')?.querySelector('.invalid-feedback');
                
                let isValid = true;
                let errorMessage = '';
                
                // Verificar si el campo es obligatorio y está vacío
                if (element.hasAttribute('required') && !value) {
                    isValid = false;
                    errorMessage = 'Este campo es obligatorio';
                } else if (value) {
                    // Ejecutar validadores específicos
                    for (const validator of field.validators) {
                        if (!validator.check(value)) {
                            isValid = false;
                            errorMessage = validator.message;
                            break;
                        }
                    }
                }
                
                // Aplicar estilos y mostrar mensajes
                if (isValid) {
                    element.classList.remove('is-invalid');
                    element.classList.add('is-valid');
                    if (feedback) feedback.textContent = '';
                } else {
                    element.classList.remove('is-valid');
                    element.classList.add('is-invalid');
                    if (feedback) feedback.textContent = errorMessage;
                }
                
                return isValid;
            }
            
            // Inicializar envío del formulario
        // Inicializar envío del formulario
    function initializeFormSubmission() {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validar todo el formulario
            const fields = ['nombre', 'correo', 'password', 'confirmar_password', 'tipo_usuario'];
            let formValid = true;

            fields.forEach(fieldName => {
                const fieldElement = document.getElementById(fieldName);
                if (!fieldElement.classList.contains('is-valid')) {
                    formValid = false;
                    fieldElement.focus();
                }
            });

            if (!formValid) {
                showMessage('Por favor, corrige los errores antes de continuar', 'error');
                return;
            }

            // Mostrar estado de carga
            setLoadingState(true);

            try {
                const formData = new FormData(form);

                const response = await fetch('/registro', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const contentType = response.headers.get('content-type') || '';

                if (contentType.includes('application/json')) {
                    const result = await response.json();

                    if (result.success) {
                        showMessage(result.message, 'success');
                        form.reset();

                        // Limpiar clases de validación
                        form.querySelectorAll('.form-control, .form-select').forEach(el => {
                            el.classList.remove('is-valid', 'is-invalid');
                        });

                        // Simular redirección después de 2 segundos
                        setTimeout(() => {
                            showMessage('¡Bienvenido! Serás redirigido al dashboard...', 'info');
                            // Puedes hacer window.location.href = '/dashboard'; aquí si lo deseas
                        }, 2000);
                    } else {
                        showMessage(result.message, 'error');
                    }

                } else {
                    const rawText = await response.text();
                    console.error('Respuesta no es JSON:', rawText);
                    showMessage('Error inesperado del servidor. Intenta más tarde.', 'error');
                }

            } catch (error) {
                console.error('Error:', error);
                showMessage('Error de conexión. Por favor, inténtalo de nuevo.', 'error');
            } finally {
                setLoadingState(false);
            }
        });
    }

            
            // Controlar estado de carga del botón
            function setLoadingState(loading) {
                if (loading) {
                    submitBtn.disabled = true;
                    submitText.style.display = 'none';
                    loadingText.style.display = 'inline';
                } else {
                    submitBtn.disabled = false;
                    submitText.style.display = 'inline';
                    loadingText.style.display = 'none';
                }
            }
            
            // Mostrar mensajes dinámicos
            function showMessage(message, type) {
                // Remover alertas existentes
                const existingAlerts = document.querySelectorAll('.dynamic-alert');
                existingAlerts.forEach(alert => alert.remove());
                
                // Crear nueva alerta
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${getBootstrapClass(type)} alert-dismissible fade show dynamic-alert`;
                alertDiv.setAttribute('role', 'alert');
                
                const icon = getIcon(type);
                alertDiv.innerHTML = `
                    <i class="${icon} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                `;
                
                // Insertar antes del formulario
                form.parentNode.insertBefore(alertDiv, form);
                
                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
            
            // Obtener clase de Bootstrap según el tipo
            function getBootstrapClass(type) {
                const classes = {
                    'success': 'success',
                    'error': 'danger',
                    'info': 'info',
                    'warning': 'warning'
                };
                return classes[type] || 'info';
            }
            
            // Obtener icono según el tipo
            function getIcon(type) {
                const icons = {
                    'success': 'fas fa-check-circle',
                    'error': 'fas fa-exclamation-circle',
                    'info': 'fas fa-info-circle',
                    'warning': 'fas fa-exclamation-triangle'
                };
                return icons[type] || 'fas fa-info-circle';
            }
            
            // Efectos adicionales de UI
            document.addEventListener('DOMContentLoaded', function() {
                // Animación de entrada para los campos
                const formFields = document.querySelectorAll('.form-floating, .input-group');
                formFields.forEach((field, index) => {
                    field.style.opacity = '0';
                    field.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        field.style.transition = 'all 0.5s ease';
                        field.style.opacity = '1';
                        field.style.transform = 'translateY(0)';
                    }, index * 100);
                });
                
                // Efecto de focus en los campos
                document.querySelectorAll('.form-control, .form-select').forEach(field => {
                    field.addEventListener('focus', function() {
                        this.parentNode.style.transform = 'scale(1.02)';
                        this.parentNode.style.transition = 'transform 0.2s ease';
                    });
                    
                    field.addEventListener('blur', function() {
                        this.parentNode.style.transform = 'scale(1)';
                    });
                });
            });
            
            // Validación de fortaleza de contraseña visual
            document.getElementById('password').addEventListener('input', function() {
                const password = this.value;
                const strengthBar = document.getElementById('password-strength');
                
                if (!strengthBar) {
                    // Crear barra de fortaleza si no existe
                    const strengthContainer = document.createElement('div');
                    strengthContainer.className = 'password-strength-container mt-2';
                    strengthContainer.innerHTML = `
                        <div class="password-strength-bar">
                            <div id="password-strength" class="password-strength-fill"></div>
                        </div>
                        <small id="password-strength-text" class="text-muted"></small>
                    `;
                    
                    this.parentNode.parentNode.appendChild(strengthContainer);
                    
                    // Agregar estilos CSS
                    const style = document.createElement('style');
                    style.textContent = `
                        .password-strength-bar {
                            height: 4px;
                            background: #e9ecef;
                            border-radius: 2px;
                            overflow: hidden;
                        }
                        .password-strength-fill {
                            height: 100%;
                            transition: all 0.3s ease;
                            border-radius: 2px;
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                const strength = calculatePasswordStrength(password);
                updatePasswordStrengthUI(strength);
            });
            
            // Calcular fortaleza de contraseña
            function calculatePasswordStrength(password) {
                let score = 0;
                let feedback = [];
                
                if (password.length >= 8) score += 25;
                else feedback.push('Al menos 8 caracteres');
                
                if (/[a-z]/.test(password)) score += 25;
                else feedback.push('Letra minúscula');
                
                if (/[A-Z]/.test(password)) score += 25;
                else feedback.push('Letra mayúscula');
                
                if (/\d/.test(password)) score += 25;
                else feedback.push('Número');
                
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score += 10;
                
                return { score, feedback };
            }
            
            // Actualizar UI de fortaleza de contraseña
            function updatePasswordStrengthUI(strength) {
                const strengthBar = document.getElementById('password-strength');
                const strengthText = document.getElementById('password-strength-text');
                
                if (!strengthBar || !strengthText) return;
                
                const { score, feedback } = strength;
                
                let color, text;
                if (score < 25) {
                    color = '#dc3545';
                    text = 'Muy débil';
                } else if (score < 50) {
                    color = '#fd7e14';
                    text = 'Débil';
                } else if (score < 75) {
                    color = '#ffc107';
                    text = 'Regular';
                } else if (score < 100) {
                    color = '#20c997';
                    text = 'Fuerte';
                } else {
                    color = '#198754';
                    text = 'Muy fuerte';
                }
                
                strengthBar.style.width = score + '%';
                strengthBar.style.backgroundColor = color;
                strengthText.textContent = text;
                
                if (feedback.length > 0 && score < 75) {
                    strengthText.textContent += ` (Falta: ${feedback.join(', ')})`;
                }
            }
        </script>
    </body>
    </html>