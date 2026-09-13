# effortlessNote

A responsive, reference-inspired note-taking website built with Laravel/PHP, Blade HTML, plain CSS and JavaScript. Node.js and Vite build the frontend assets.

## Run locally

```sh
npm install
npm run build
php artisan serve
```

Open http://127.0.0.1:8000. For frontend development, run `npm run dev` in another terminal.

## Features

- Monochrome landing page with responsive layouts, editor preview, platform cards, pricing, FAQs and footer.
- Scroll reveal, scroll-driven mountain movement, progress indicator and section snapping. Reduced-motion preferences disable movement and snapping.
- Native cross-page view transitions with a JavaScript fallback.
- Free notes workspace at `/notes`: automatic local saving, search, favorites, deletion and text export.
- Yearly/monthly price toggle and validated, rate-limited waitlist form for future paid plans.

Notes are stored in this browser's local storage, not in a cloud account. Export a backup before clearing browser data. Paid plans are previews; no checkout or synchronization is implemented. Waitlist entries are saved privately to `storage/app/waitlist.jsonl`; no email notification is sent automatically.

The hero photograph loads from Unsplash and the font from Bunny Fonts; system fonts provide a fallback.

## Validation

```sh
npm run build
php artisan test
php artisan view:cache
```
