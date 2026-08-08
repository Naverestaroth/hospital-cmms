# TODO

- [x] Fix procurement_year validation for assets (year-only input)

  - Update resources/views/assets/create.blade.php (optional: input type)
  - Update app/Http/Controllers/AssetController.php@store validation + convert year => YYYY-01-01 before saving
  - (Optional) Update app/Http/Controllers/AssetController.php@update with same logic for consistency

