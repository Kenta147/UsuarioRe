# Registro de Usuarios - Proyecto Node.js MVC

Este proyecto es un sistema de registro de usuarios desarrollado con Node.js, Express y arquitectura MVC. Permite registrar usuarios, validar datos, almacenar la información en un archivo JSON y visualizar los usuarios registrados.

## Características

- Registro de usuarios con validación de datos en frontend y backend.
- Persistencia de usuarios en archivo JSON (`data/usuarios.json`).
- Arquitectura MVC (Model-View-Controller).
- Interfaz moderna y responsiva con TailwindCSS.
- Listado de usuarios para desarrollo.

## Estructura del Proyecto

```
app.js
package.json
controllers/
    registroController.js
data/
    usuarios.json
models/
    Usuario.js
public/
    index.html
    css/
        styles.css
    js/
        registro.js
routes/
    registroRoutes.js
views/
    registro.ejs
```

## Instalación

1. Clona el repositorio o descarga el código.
2. Instala las dependencias:

   ```sh
   npm install
   npm init -y 
   npm express
   ```

3. Inicia el servidor:

   ```sh
   node app.js
   ```

4. Abre tu navegador en [http://localhost:3000](http://localhost:3000)

## Uso

- Accede a la ruta principal `/` para ver el formulario de registro.
- Completa el formulario y envía los datos.
- Los usuarios registrados se almacenan en `data/usuarios.json`.
- Para ver todos los usuarios registrados (modo desarrollo), visita `/usuarios`.

## Validaciones

- **Nombre:** mínimo 2 caracteres.
- **Correo:** formato válido y único.
- **Contraseña:** mínimo 8 caracteres.
- **Tipo de usuario:** debe ser uno de los valores permitidos.

## Tecnologías

- Node.js
- Express
- EJS (para vistas, opcional)
- TailwindCSS (en el frontend)
- JavaScript (frontend y backend)

