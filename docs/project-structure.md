# Project structure

## Views

- `resources/views/layouts/app.blade.php`: shared HTML document and asset loading.
- `resources/views/partials/`: shared header and footer.
- `resources/views/pages/home/index.blade.php`: home page, composed of files in `sections/` (hero, features, platforms, pricing, how it works, FAQ, call to action, trust strip, and waitlist dialog).
- `resources/views/pages/notes/index.blade.php`: notes page, composed of heading, sidebar, and editor files in `sections/`.

Add a page under `pages/<name>/index.blade.php`, extend `layouts.app`, and include its section files using Blade dot notation.

## Application

- `routes/web.php`: URLs, route names, and middleware.
- `app/Http/Controllers/Pages/`: page rendering.
- `app/Http/Controllers/Waitlist/`: waitlist HTTP responses.
- `app/Http/Requests/Waitlist/`: waitlist input validation.
- `app/Services/Waitlist/`: append-only JSONL waitlist persistence.
- `app/Models/Users/`: user model. Authentication configuration, seeders, and the user factory reference this namespace.
- `database/factories/`, `database/seeders/`, `database/migrations/`: Laravel's conventional database tooling. The user model and factory are explicitly linked so nested model namespaces work in both directions.

Notes remain in browser local storage; the waitlist remains in `storage/app/waitlist.jsonl`. Neither feature uses an Eloquent model or requires a new database table.

## Frontend assets

- `resources/js/app.js`: Vite entry point importing shared and page modules.
- `resources/js/shared/`: navigation, motion, and toast behavior.
- `resources/js/pages/`: home interactions and the notes workspace.
- `resources/css/app.css`: ordered CSS imports and Tailwind source discovery.
- `resources/css/base/`, `layouts/`, `partials/`, `pages/`, `shared/`: base rules, navigation, reusable elements, page styles, responsive rules, and shared effects.

CSS import order preserves the existing cascade. Responsive and theme rules follow the base and page styles. Vite entry paths remain unchanged.

## Checks

Run `php artisan test`, `php artisan view:cache`, and `npm run build` after changing the structure or references.
