# TODO: Implement Independent Filters for Military and Academic Subjects Tables

## Steps to Complete

1. **Update actions/get_subjects.php**
   - Add 'category' parameter to filter subjects by s.type (militaire or universitaire). ❌ (Reverted to original)

2. **Add Filter UI Above Military Subjects Table**
   - Insert a Bootstrap row with search input (for subject name) and stage dropdown (with "All" option) above the military subjects card-body. ❌ (Removed)

3. **Add Filter UI Above Academic Subjects Table**
   - Insert a Bootstrap row with search input (for subject name) and stage dropdown (with "All" option) above the academic subjects card-body. ❌ (Removed)

4. **Modify JavaScript Logic**
   - Replace loadSubjects() with separate loadMilitarySubjects() and loadAcademicSubjects() functions.
   - Each function calls get_subjects.php with category, search, and stage_id parameters. ❌ (Reverted to single loadSubjects function)

5. **Add Event Listeners for Filters**
   - Attach oninput to search inputs and onchange to stage dropdowns to trigger respective load functions. ❌ (Removed)

6. **Update Load Calls in Handlers**
   - In add/edit/delete success handlers, call both loadMilitarySubjects() and loadAcademicSubjects() with default filters. ❌ (Reverted to single loadSubjects call)

7. **Test Implementation**
   - Verify filters work independently, update only their table, and persist after operations.
   - Ensure Bootstrap styling and responsiveness.
