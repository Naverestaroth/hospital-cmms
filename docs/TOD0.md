# TODO - Preventive Maintenance Refactor (Snapshot + Dependent Asset Dropdown)

- [ ] Fix PreventiveAssetController (routing + JSON endpoints)
- [ ] Add JSON routes in routes/web.php
- [ ] Update PreventiveController:
  - [ ] create(): remove Asset::all()
  - [ ] store(): update validation to nullable/optional for required fields
- [ ] Add procurement_year column to preventives migration (nullable date)
- [ ] Update Preventive model fillable to include procurement_year
- [ ] Update preventives/create.blade.php:
  - [ ] asset dropdown dependent by room (Fetch)
  - [ ] auto-fill asset snapshot fields including procurement_year
  - [ ] add missing procurement_year input field (editable)
  - [ ] vanilla JS fetch logic
- [ ] Run php artisan route:list and php artisan test (if any) / quick syntax check

