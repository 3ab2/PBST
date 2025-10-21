<?php
require '../functions.php';
check_role('cellule_pedagogique');
$page_title = 'Manage Observations';

// Add missing translations if not defined
if (!isset($translations['start_time'])) $translations['start_time'] = 'Heure de début';
if (!isset($translations['end_time'])) $translations['end_time'] = 'Heure de fin';
if (!isset($translations['month'])) $translations['month'] = 'Month';
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?> <span>></span> <?php echo htmlspecialchars($translations['manage_observations']); ?></div>
<button class="breadcrumb-header" id="add-observation-btn"><?php echo htmlspecialchars($translations['add_observation']); ?></button>
</div>

<div class="container mt-3">
    <div class="card" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
            <h5 class="mb-0"><i class="bi bi-eye"></i> <?php echo htmlspecialchars($translations['manage_observations']); ?></h5>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="instructor-filter" class="form-label"><?php echo htmlspecialchars($translations['instructor']); ?></label>
                    <select class="form-select" id="instructor-filter">
                        <option value=""><?php echo htmlspecialchars($translations['all_instructors']); ?></option>
                        <!-- AJAX loaded -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="month-filter" class="form-label"><?php echo htmlspecialchars($translations['month']); ?></label>
                    <input type="month" class="form-control" id="month-filter">
                </div>
                <div class="col-md-4">
                    <label for="rating-filter" class="form-label"><?php echo htmlspecialchars($translations['rating']); ?></label>
                    <select class="form-select" id="rating-filter">
                        <option value=""><?php echo htmlspecialchars($translations['all_ratings']); ?></option>
                        <option value="positive"><?php echo htmlspecialchars($translations['positive']); ?></option>
                        <option value="negative"><?php echo htmlspecialchars($translations['negative']); ?></option>
                    </select>
                </div>
            </div>

            <!-- Observations Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="observations-table">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo htmlspecialchars($translations['instructor']); ?></th>
                            <th><?php echo htmlspecialchars($translations['subject']); ?></th>
                            <th><?php echo htmlspecialchars($translations['date']); ?></th>
                            <th><?php echo htmlspecialchars($translations['start_time']); ?></th>
                            <th><?php echo htmlspecialchars($translations['end_time']); ?></th>
                            <th><?php echo htmlspecialchars($translations['rating']); ?></th>
                            <th><?php echo htmlspecialchars($translations['score']); ?></th>
                            <th><?php echo htmlspecialchars($translations['comment']); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX loaded -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Observation Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit']); ?> <?php echo htmlspecialchars($translations['manage_observations']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editObservationForm" method="POST" action="../actions/edit_observation.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit-heure-debut" class="form-label"><?php echo htmlspecialchars($translations['start_time']); ?></label>
                            <input type="time" class="form-control" id="edit-heure-debut" name="heure_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit-heure-fin" class="form-label"><?php echo htmlspecialchars($translations['end_time']); ?></label>
                            <input type="time" class="form-control" id="edit-heure-fin" name="heure_fin" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="edit-rating" class="form-label"><?php echo htmlspecialchars($translations['rating']); ?></label>
                            <select class="form-select" id="edit-rating" name="rating" required>
                                <option value="positive"><?php echo htmlspecialchars($translations['positive']); ?></option>
                                <option value="negative"><?php echo htmlspecialchars($translations['negative']); ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit-score" class="form-label"><?php echo htmlspecialchars($translations['score']); ?> (1-10)</label>
                            <input type="number" class="form-control" id="edit-score" name="score" min="1" max="10">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="edit-comment" class="form-label"><?php echo htmlspecialchars($translations['comment']); ?></label>
                        <textarea class="form-control" id="edit-comment" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3"><?php echo htmlspecialchars($translations['save_changes']); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Observation Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_observation']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-form" method="POST" action="../actions/add_observation.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="add-instructor" class="form-label"><?php echo htmlspecialchars($translations['instructor']); ?></label>
                            <select class="form-select" id="add-instructor" name="instructor_id" required>
                                <option value=""><?php echo htmlspecialchars($translations['select_instructor']); ?></option>
                                <!-- AJAX loaded -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add-subject" class="form-label"><?php echo htmlspecialchars($translations['subject']); ?></label>
                            <select class="form-select" id="add-subject" name="subject_id" required>
                                <option value=""><?php echo htmlspecialchars($translations['select_subject']); ?></option>
                                <!-- AJAX loaded -->
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="add-date" class="form-label"><?php echo htmlspecialchars($translations['date']); ?></label>
                            <input type="date" class="form-control" id="add-date" name="obs_date" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="add-heure-debut" class="form-label"><?php echo htmlspecialchars($translations['start_time']); ?></label>
                            <input type="time" class="form-control" id="add-heure-debut" name="heure_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label for="add-heure-fin" class="form-label"><?php echo htmlspecialchars($translations['end_time']); ?></label>
                            <input type="time" class="form-control" id="add-heure-fin" name="heure_fin" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="add-rating" class="form-label"><?php echo htmlspecialchars($translations['rating']); ?></label>
                            <select class="form-select" id="add-rating" name="rating" required>
                                <option value="positive"><?php echo htmlspecialchars($translations['positive']); ?></option>
                                <option value="negative"><?php echo htmlspecialchars($translations['negative']); ?></option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="add-score" class="form-label"><?php echo htmlspecialchars($translations['score']); ?> (1-10)</label>
                            <input type="number" class="form-control" id="add-score" name="score" min="1" max="10">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="add-comment" class="form-label"><?php echo htmlspecialchars($translations['comment']); ?></label>
                        <textarea class="form-control" id="add-comment" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3"><?php echo htmlspecialchars($translations['add']); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const translations = {
    edit: '<i class="fas fa-edit"></i>',
    delete: '<i class="fas fa-trash"></i>',
    delete_confirmation: '<?php echo htmlspecialchars($translations['delete_confirmation']); ?>'
};

// Check for success or error messages in URL parameters
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    if (urlParams.get('success') === 'edit') {
        alert('Observation updated successfully!');
    } else {
        alert('Observation added successfully!');
    }
    window.history.replaceState(null, null, window.location.pathname);
} else if (urlParams.has('error')) {
    alert('Error: ' + decodeURIComponent(urlParams.get('error')));
    window.history.replaceState(null, null, window.location.pathname);
}

document.addEventListener('DOMContentLoaded', function() {
    let currentFilters = {};

    // Load instructors for filter and add modal
    fetch('../actions/get_instructors.php')
        .then(response => response.json())
        .then(data => {
            const filterSelect = document.getElementById('instructor-filter');
            const addSelect = document.getElementById('add-instructor');
            data.forEach(instructor => {
                const option1 = document.createElement('option');
                option1.value = instructor.id_instructor;
                option1.textContent = instructor.first_name + ' ' + instructor.last_name;
                filterSelect.appendChild(option1);

                const option2 = document.createElement('option');
                option2.value = instructor.id_instructor;
                option2.textContent = instructor.first_name + ' ' + instructor.last_name;
                addSelect.appendChild(option2);
            });
        });

    // Function to load all subjects
    function loadAllSubjects() {
        const subjectSelect = document.getElementById('add-subject');
        subjectSelect.innerHTML = '<option value=""><?php echo htmlspecialchars($translations['select_subject']); ?></option>';
        subjectSelect.disabled = false;
        fetch('../actions/get_subjects.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id_subject;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectSelect.disabled = true;
            });
    }

    // Initially load all subjects
    loadAllSubjects();

    // Load observations
    loadObservations();

    function loadObservations() {
        let url = '../actions/get_observations.php?';
        Object.keys(currentFilters).forEach(key => {
            if (currentFilters[key]) url += `${key}=${currentFilters[key]}&`;
        });

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#observations-table tbody');
                tbody.innerHTML = '';
                data.forEach(obs => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${obs.instructor_name}</td>
                        <td>${obs.subject_name}</td>
                        <td>${obs.obs_date}</td>
                        <td>${obs.heure_debut}</td>
                        <td>${obs.heure_fin}</td>
                        <td>${obs.rating}</td>
                        <td>${obs.score || '-'}</td>
                        <td>${obs.comment}</td>
                      
                    `;
                    tbody.appendChild(row);
                });

                // Attach events
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        document.getElementById('edit-id').value = id;
                        document.getElementById('edit-rating').value = this.dataset.rating;
                        document.getElementById('edit-score').value = this.dataset.score;
                        document.getElementById('edit-comment').value = this.dataset.comment;
                        document.getElementById('edit-heure-debut').value = this.dataset.heureDebut;
                        document.getElementById('edit-heure-fin').value = this.dataset.heureFin;
                        new bootstrap.Modal(document.getElementById('editModal')).show();
                    });
                });

                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (confirm(translations.delete_confirmation)) {
                            fetch('../actions/delete_observation.php', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json'},
                                body: JSON.stringify({id: this.dataset.id})
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) loadObservations();
                                else alert('Error: ' + data.message);
                            });
                        }
                    });
                });
            });
    }

    // Auto filter on change
    document.getElementById('instructor-filter').addEventListener('change', function() {
        currentFilters.instructor_id = this.value;
        loadObservations();
    });

    document.getElementById('month-filter').addEventListener('change', function() {
        currentFilters.month = this.value;
        loadObservations();
    });

    document.getElementById('rating-filter').addEventListener('change', function() {
        currentFilters.rating = this.value;
        loadObservations();
    });

    // Add observation button
    document.getElementById('add-observation-btn').addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('addModal')).show();
    });

    // Add form submission via AJAX
    document.getElementById('add-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('../actions/add_observation.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                loadObservations();
                // Reset form
                this.reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the observation.');
        });
    });

    // Edit form - removed since now using form submission
});
</script>

<?php include '../templates/footer.php'; ?>
