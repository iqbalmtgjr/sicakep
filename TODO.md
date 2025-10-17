# TODO: Enable Comma as Decimal Separator for Percentage and Numeric Inputs

## Tasks:

-   [x] Update validation rules in PHP controllers for decimal support
-   [x] Change input type from "number" to "text" in Blade templates
-   [x] Add JavaScript to convert comma to dot on input/submit
-   [x] Update placeholders to indicate comma support
-   [x] Update database schema for bobot field to decimal
-   [x] Test the changes

## Files to Edit:

-   [x] app/Livewire/IndikatorKinerja/Index.php (validation for bobot)
-   [x] resources/views/livewire/indikator-kinerja/index.blade.php (input type and JS)
-   [x] resources/views/livewire/target-kinerja/index.blade.php (input type and JS)
-   [x] resources/views/livewire/realisasi-kinerja/index.blade.php (input type and JS)
-   [x] app/Models/IndikatorKinerja.php (cast bobot to decimal)
-   [x] database/migrations/2025_10_17_034424_change_bobot_to_decimal_in_indikator_kinerja_table.php (migration)
