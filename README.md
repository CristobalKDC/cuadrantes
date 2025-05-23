# Cuadrantres

**Cuadrantres** es una aplicación web desarrollada con Laravel que incluye un sistema completo de autenticación utilizando **Laravel Jetstream** con Livewire. El proyecto está diseñado para facilitar la gestión de usuarios y contenido a través de una interfaz moderna y segura.

---

## 🚀 Tecnologías utilizadas

- [Laravel 10](https://laravel.com/)
- [Jetstream (Livewire)](https://jetstream.laravel.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [MySQL / MariaDB](https://www.mysql.com/)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) + [Vite](https://vitejs.dev/)
- PHP 8.1+

---

## ⚙️ Requisitos previos

Antes de instalar este proyecto, asegúrate de tener instalados:

- PHP 8.1 o superior
- Composer
- Node.js y npm
- Servidor MySQL o MariaDB
- Git (opcional, para clonar el repositorio)

---

## 🛠️ Instalación

```bash
# Clona el repositorio
git clone https://github.com/tuusuario/cuadrantres.git

cd cuadrantres

# Instala dependencias PHP
composer install

# Copia archivo de entorno
cp .env.example .env

# Genera clave de aplicación
php artisan key:generate

# Configura la conexión a base de datos en el archivo .env

# Ejecuta las migraciones
php artisan migrate

# Instala y compila los assets frontend
npm install && npm run dev

# Inicia servidor local
php artisan serve
```

---

## 🔐 Sistema de autenticación

Este proyecto utiliza **Laravel Jetstream con Livewire** para proporcionar:

- Registro de usuarios
- Inicio de sesión
- Recuperación de contraseña
- Verificación de correo electrónico
- Gestión de perfil

---

## 🧪 Comandos útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir migraciones
php artisan migrate:rollback

# Compilar frontend para producción
npm run build

# Compilar frontend en desarrollo
npm run dev

# Servidor de desarrollo
php artisan serve
```

---

## 📁 Estructura básica del proyecto

- `app/` — Lógica de aplicación (controllers, models, etc.)
- `resources/views/` — Vistas Blade
- `routes/web.php` — Rutas web
- `database/migrations/` — Migraciones de base de datos

---

## 📝 Licencia

Este proyecto está bajo la licencia [MIT](https://opensource.org/licenses/MIT).

---

## 👤 Autor

Desarrollado por Cristóbal Pérez Castillo.
