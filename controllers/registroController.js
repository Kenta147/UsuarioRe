const Usuario = require('../models/Usuario');
const fs = require('fs').promises;
const path = require('path');

class RegistroController {
    // Almacenamiento en memoria (para desarrollo)
    static usuarios = [];
    
    // Archivo para persistencia simple
    static archivoUsuarios = path.join(__dirname, '../data/usuarios.json');

    // Mostrar formulario de registro
    static mostrarFormulario(req, res) {
        try {
            // Si es una solicitud API, devolver JSON
            if (req.headers.accept && req.headers.accept.includes('application/json')) {
                res.json({ mensaje: 'Formulario de registro disponible' });
                return;
            }
            
            // Si usas EJS, renderizar la vista
            // res.render('registro', { titulo: 'Registro de Usuario' });
            
            // Para desarrollo, servir HTML estático
            res.sendFile(path.join(__dirname, '../public/index.html'));
        } catch (error) {
            console.error('Error al mostrar formulario:', error);
            res.status(500).json({ error: 'Error interno del servidor' });
        }
    }

    // Procesar registro de usuario
    static async registrarUsuario(req, res) {
        try {
            const { nombre, correo, password, tipoUsuario } = req.body;

            // Validar datos
            const errores = Usuario.validar({ nombre, correo, password, tipoUsuario });
            
            if (errores.length > 0) {
                return res.status(400).json({ 
                    exito: false,
                    errores: errores 
                });
            }

            // Verificar si el correo ya existe
            if (await RegistroController.existeCorreo(correo)) {
                return res.status(400).json({ 
                    exito: false,
                    errores: ['Este correo ya está registrado'] 
                });
            }

            // Crear nuevo usuario
            const nuevoUsuario = new Usuario(nombre, correo, password, tipoUsuario);
            
            // Guardar usuario
            await RegistroController.guardarUsuario(nuevoUsuario);

            // Respuesta exitosa (sin devolver la contraseña)
            res.status(201).json({
                exito: true,
                mensaje: 'Usuario registrado exitosamente',
                usuario: nuevoUsuario.toPublic()
            });

        } catch (error) {
            console.error('Error al registrar usuario:', error);
            res.status(500).json({ 
                exito: false,
                errores: ['Error interno del servidor'] 
            });
        }
    }

    // Verificar si un correo ya existe
    static async existeCorreo(correo) {
        try {
            const usuarios = await RegistroController.obtenerUsuarios();
            return usuarios.some(usuario => usuario.correo === correo);
        } catch (error) {
            console.error('Error al verificar correo:', error);
            return false;
        }
    }

    // Guardar usuario en archivo y memoria
    static async guardarUsuario(usuario) {
        try {
            // Agregar a memoria
            RegistroController.usuarios.push(usuario);

            // Guardar en archivo
            await RegistroController.guardarEnArchivo();
            
            console.log(`Usuario registrado: ${usuario.nombre} (${usuario.correo})`);
        } catch (error) {
            console.error('Error al guardar usuario:', error);
            throw error;
        }
    }

    // Obtener todos los usuarios
    static async obtenerUsuarios() {
        try {
            // Si no hay usuarios en memoria, cargar del archivo
            if (RegistroController.usuarios.length === 0) {
                await RegistroController.cargarDesdeArchivo();
            }
            
            return RegistroController.usuarios;
        } catch (error) {
            console.error('Error al obtener usuarios:', error);
            return [];
        }
    }

    // Cargar usuarios desde archivo
    static async cargarDesdeArchivo() {
        try {
            const data = await fs.readFile(RegistroController.archivoUsuarios, 'utf8');
            RegistroController.usuarios = JSON.parse(data);
        } catch (error) {
            // Si el archivo no existe, crear array vacío
            if (error.code === 'ENOENT') {
                RegistroController.usuarios = [];
                await RegistroController.crearDirectorioData();
            } else {
                console.error('Error al cargar usuarios:', error);
            }
        }
    }

    // Guardar usuarios en archivo
    static async guardarEnArchivo() {
        try {
            await RegistroController.crearDirectorioData();
            await fs.writeFile(
                RegistroController.archivoUsuarios, 
                JSON.stringify(RegistroController.usuarios, null, 2)
            );
        } catch (error) {
            console.error('Error al guardar en archivo:', error);
        }
    }

    // Crear directorio data si no existe
    static async crearDirectorioData() {
        try {
            const dirData = path.dirname(RegistroController.archivoUsuarios);
            await fs.mkdir(dirData, { recursive: true });
        } catch (error) {
            console.error('Error al crear directorio:', error);
        }
    }

    // Método para desarrollo - listar usuarios registrados
    static async listarUsuarios(req, res) {
        try {
            const usuarios = await RegistroController.obtenerUsuarios();
            const usuariosPublicos = usuarios.map(u => u.toPublic ? u.toPublic() : u);
            
            res.json({
                total: usuarios.length,
                usuarios: usuariosPublicos
            });
        } catch (error) {
            console.error('Error al listar usuarios:', error);
            res.status(500).json({ error: 'Error interno del servidor' });
        }
    }
}

module.exports = RegistroController;
