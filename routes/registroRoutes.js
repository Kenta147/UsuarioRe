const express = require('express');
const RegistroController = require('../controllers/registroController');

const router = express.Router();

// GET /registro - Mostrar formulario
router.get('/registro', RegistroController.mostrarFormulario);

// POST /registro - Procesar registro
router.post('/registro', RegistroController.registrarUsuario);

// GET /usuarios - Listar usuarios (para desarrollo)
router.get('/usuarios', RegistroController.listarUsuarios);

// Ruta principal
router.get('/', (req, res) => {
    res.redirect('/registro');
});

module.exports = router;
