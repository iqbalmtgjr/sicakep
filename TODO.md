# TODO for Changing Application Name to SICAKEP (E-Kinerja App)

## Information Gathered:

-   The application is a Laravel project with Breeze or similar auth setup.
-   Current app name defaults to 'Laravel' in config/app.php.
-   header.blade.php contains meta tags referencing 'DIPANGKAS' (a barber shop app), which needs replacement with SICAKEP e-kinerja description.
-   welcome.blade.php has a hardcoded 'Laravel' title.
-   No other major dependencies; changes are isolated to these files.
-   Description for SICAKEP: "SICAKEP adalah aplikasi e-kinerja yang digunakan untuk mengelola dan melaporkan kinerja pegawai secara digital. Aplikasi ini memudahkan pemantauan, evaluasi, dan pelaporan kinerja untuk meningkatkan produktivitas organisasi."
-   Keywords: "SICAKEP, e-kinerja, aplikasi, kinerja pegawai, laporan, evaluasi"

## Plan:

-   Update config/app.php to set default 'APP_NAME' to 'SICAKEP'.
-   Update resources/views/layouts/header.blade.php to replace DIPANGKAS references with SICAKEP, including title (uses env), description, keywords, og:title, and og:site_name.
-   Update resources/views/welcome.blade.php to change hardcoded title to 'SICAKEP'.
-   No dependent files need editing beyond these.

## Dependent Files to be Edited:

-   None additional; changes are self-contained.

## Followup Steps:

-   [ ] Test the application by running `php artisan serve` and checking the welcome page, login page, and meta tags in browser dev tools.
-   [ ] Clear any caches if needed: `php artisan config:clear` and `php artisan view:clear`.

## Steps:

-   [x] Create this TODO.md (done).
-   [x] Edit config/app.php.
-   [x] Edit resources/views/layouts/header.blade.php.
-   [x] Edit resources/views/welcome.blade.php.
-   [x] Update TODO.md with completions.
-   [ ] Perform followup testing.
