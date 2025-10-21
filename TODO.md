# TODO: Add heure_debut and heure_fin to observations

## Database Schema Update
- [x] ALTER TABLE observations CHANGE obs_time heure_debut TIME NOT NULL;
- [x] ALTER TABLE observations ADD COLUMN heure_fin TIME NOT NULL;

## Backend Actions
- [x] Update actions/add_observation.php: Change obs_time to heure_debut, add heure_fin, add validation (heure_fin > heure_debut)
- [x] Update actions/edit_observation.php: Change obs_time to heure_debut, add heure_fin, add validation
- [x] Update actions/get_observations.php: Change obs_time to heure_debut, add heure_fin in SELECT

## Frontend Updates
- [x] Update cellule_pedagogique/manage_observations.php: Change table column from "Time" to "Start Time", add "End Time" column. Update add/edit modals to have heure_debut and heure_fin inputs. Update JS for new fields and validation.
- [x] Update cellule_pedagogique/dashboard_cellule_pedagogique.php: Update add observation modal similarly.
- [x] Update profile_instructeur_pdf.php: Change obs_time references to heure_debut and heure_fin

## Validation & UX
- [x] Add client-side validation in JS to ensure heure_fin > heure_debut
- [x] Ensure time inputs use HH:MM format

## Followup steps
- [x] Run database migration to update schema
- [x] Test adding/editing observations with new time fields
- [x] Verify validation works (heure_fin > heure_debut)
- [x] Check display in tables and PDFs
- [x] Update translations for start_time and end_time in lang/ar.php and lang/fr.php
