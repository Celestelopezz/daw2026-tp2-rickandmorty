# Rick & Morty App (Laravel) - DAW 2026 - TP2

Aplicación web en **Laravel 12** que permite explorar personajes, episodios y ubicaciones de Rick and Morty, y que los usuarios registrados armen sus propias listas.

Parte del modelo de datos del TP1, ahora con migraciones, modelos Eloquent y autenticación con Laravel Breeze.

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js (LTS) y npm
- MySQL / MariaDB (XAMPP sirve)
- Git

## Instalación

1. Clonar el repositorio y entrar a la carpeta:

```bash
git clone https://github.com/Celestelopezz/daw2026-tp2-rickandmorty.git
cd daw2026-tp2-rickandmorty
```

2. Instalar dependencias de PHP:

```bash
composer install
```

3. Crear el archivo de configuración y generar la clave de la app:

```bash
copy .env.example .env
php artisan key:generate
```

(En Linux/Mac: `cp .env.example .env`)

4. Crear la base de datos vacía `rick_and_morty_laravel` en phpMyAdmin, con cotejamiento `utf8mb4_unicode_ci`.

5. Revisar en el `.env` los datos de conexión. Los valores por defecto sirven para XAMPP:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rick_and_morty_laravel
DB_USERNAME=root
DB_PASSWORD=
```

Si tu MySQL usa otro puerto o contraseña, cambialos acá. **El `.env` nunca se sube a git.**

6. Crear las tablas:

```bash
php artisan migrate
```

7. Cargar los datos desde la API (tarda 1 o 2 minutos):

```bash
php artisan rickandmorty:import
```

Se puede ejecutar más de una vez sin duplicar datos. Si la API responde con error 429 (demasiados pedidos), esperar un minuto y volver a ejecutarlo.

8. Instalar y compilar los estilos:

```bash
npm install
npm run build
```

9. Levantar el servidor:

```bash
php artisan serve
```

Abrir http://127.0.0.1:8000

Resultado esperado de la importación: 826 personajes, 51 episodios, 126 ubicaciones.

## Problemas frecuentes (Windows)

- **`npm` bloqueado por PowerShell**: ejecutar `Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy RemoteSigned`.
- **`node` o `npm` no se reconocen**: cerrar y volver a abrir VS Code después de instalar Node.
- **Error de conexión a la base**: verificar que MySQL esté prendido en XAMPP y que el puerto del `.env` coincida.

## Modelo de datos

 Tabla /                             Descripción 

| `locations`                 | Ubicaciones (id de la API) |
| `episodes`                  | Episodios (id de la API) |
| `characters`                | Personajes; `location_id` apunta a `locations` |
| `character_episode`         | Pivote N:M entre personajes y episodios |
| `users`                     | Usuarios (Breeze) |
| `user_lists`                | Listas personalizadas: `user_id`, `name`, `type` (`characters` o `episodes`) |
| `character_user_list`       | Pivote: personajes de cada lista (clave primaria compuesta) |
| `episode_user_list`         | Pivote: episodios de cada lista (clave primaria compuesta) |

Los IDs de `locations`, `episodes` y `characters` vienen de la API, por eso no son autoincrementales.

### Reglas de las listas

- Cada lista pertenece a un usuario y es de un solo tipo (personajes o episodios).
- No se repiten elementos dentro de una lista: lo garantiza la clave primaria compuesta de la tabla pivote.
- Que una lista acepte solo su tipo, y que cada usuario toque solo sus listas, se valida en el código (controlador y Policy).

## Estructura relevante

```
app/Models/                       Character, Episode, Location, UserList, User
app/Console/Commands/             ImportRickAndMorty (php artisan rickandmorty:import)
database/migrations/              Esquema de la base
routes/web.php                    Rutas de la app
routes/auth.php                   Rutas de login y registro (Breeze)
resources/views/                  Vistas Blade
```

## Cómo trabajamos en grupo

- **Nadie pushea directo a `main`.** Cada funcionalidad va en su rama y se une con un Pull Request.
- Ramas sugeridas:
  - `feature/personajes`
  - `feature/episodios`
  - `feature/ubicaciones`
  - `feature/listas`
- Flujo:

```bash
git checkout main
git pull
git checkout -b feature/nombre
# trabajar, y después:
git add .
git commit -m "Descripción clara del cambio"
git push -u origin feature/nombre
```

Después abrir el Pull Request en GitHub.

- Cada Pull Request lo revisa otra persona, que debe hacerle al autor al menos una pregunta del tipo "¿por qué lo hiciste así?".
- Antes de empezar a trabajar: `git pull` en `main`.
- Si se agrega una migración nueva, avisar al grupo para que todos corran `php artisan migrate`.

### Reparto

 Integrante / Funcionalidad /

 A- Explorador de personajes: listado, filtros, paginación, detalle 
 B- Explorador de episodios: listado, detalle, personajes del episodio 
 C- Explorador de ubicaciones: listado, detalle, residentes, menú de navegación 
 D- Listas personalizadas: CRUD, agregar y quitar elementos, permisos 

El explorador es público. Las listas requieren sesión iniciada.

## Entrega

Cada integrante entrega de forma individual un ZIP llamado `DAW-2026-TP02-apellido-nombre.zip`, con el proyecto, el SQL/migraciones, el `.env.example`, este README y el informe final (incluyendo la declaración de uso de IA).
