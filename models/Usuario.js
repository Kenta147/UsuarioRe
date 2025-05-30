class Usuario {
    constructor(nombre, correo, password, tipoUsuario) {
        this.id = Date.now().toString();
        this.nombre = nombre;
        this.correo = correo;
        this.password = password;
        this.tipoUsuario = tipoUsuario;
        this.fechaRegistro = new Date().toISOString();
    }

    // Validaciones
    static validar(datos) {
        const errores = [];

        if (!datos.nombre || datos.nombre.trim().length < 2) {
            errores.push('El nombre debe tener al menos 2 caracteres');
        }

        if (!datos.correo || !this.validarEmail(datos.correo)) {
            errores.push('Debe proporcionar un correo válido');
        }

        if (!datos.password || datos.password.length < 8) {
            errores.push('La contraseña debe tener al menos 8 caracteres');
        }

        if (!datos.tipoUsuario || !['cliente', 'proveedor', 'administrador'].includes(datos.tipoUsuario)) {
            errores.push('Debe seleccionar un tipo de usuario válido');
        }

        return errores;
    }

    static validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Método para obtener usuario sin contraseña
    toPublic() {
        const { password, ...publicUser } = this;
        return publicUser;
    }
}

module.exports = Usuario;