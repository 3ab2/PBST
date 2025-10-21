# TODO: Implement Multiple File Uploads for Subjects

## Database Updates
- [x] Add stage_id column to subjects table in pbst.sql
- [x] Create new subject_files table in pbst.sql

## Backend Updates
- [x] Update actions/add_subject.php to handle multiple file uploads and insert into subject_files
- [x] Update actions/edit_subject.php to allow adding/removing files
- [x] Update actions/get_subjects.php to return files as array for each subject

## Frontend Updates
- [ ] Update cellule_pedagogique/manage_subjects.php: change file input to multiple
- [ ] Add preview area with thumbnails in manage_subjects.php
- [ ] Update table display to show multiple file thumbnails/icons
- [ ] Add Bootstrap modals for viewing files (PDF.js, lightbox, video player)

## Client-side Enhancements
- [ ] Add JavaScript for file previews and validation
- [ ] Implement remove buttons in previews
- [ ] Add drag-drop support

## Testing and Followup
- [ ] Test multiple file uploads and downloads
- [ ] Verify modals display correctly for different file types
- [ ] Ensure backward compatibility with existing single files
