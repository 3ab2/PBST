<?php
require '../functions.php';
check_role('cellule_pedagogique');

$csrf_token = generate_csrf_token();
$page_title = htmlspecialchars($translations['manage_instructors']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?> <span>></span> <?php echo htmlspecialchars($translations['manage_instructors']); ?></div>
<button class="breadcrumb-header" data-bs-toggle="modal" data-bs-target="#addInstructorModal"><?php echo htmlspecialchars($translations['add_instructor'] ?? 'Add Instructor'); ?></button>
</div>

<div class="container mt-3">
    <div class="card" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-body">
            <div class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" id="filterName" class="form-control" placeholder="Filter by Name">
                    </div>
                    <div class="col-md-4">
                        <select id="filterSpecialty" class="form-select">
                            <option value="">All Specialties</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" id="filterScore" class="form-control" placeholder="Min Score" min="0" max="20" step="0.1">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo htmlspecialchars($translations['name']); ?></th>
                            <th><?php echo htmlspecialchars($translations['email']); ?></th>
                            <th>CINE</th>
                            <th>MLE</th>
                            <th><?php echo htmlspecialchars($translations['specialty'] ?? 'Spécialité'); ?></th>
                            <th><?php echo htmlspecialchars($translations['average_score'] ?? 'Score Moyen'); ?></th>
                            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
                        </tr>
                    </thead>
                    <tbody id="instructorsTableBody">
                        <!-- Instructors will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Instructor Modal -->
<div class="modal fade" id="addInstructorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addInstructorForm">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_instructor'] ?? 'Add Instructor'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CINE</label>
                                <input type="text" name="cine" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">MLE</label>
                                <input type="text" name="mle" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['username']); ?></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['password']); ?></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['phone']); ?></label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['specialty'] ?? 'Spécialité'); ?></label>
                        <select name="speciality_id" class="form-control" id="addSpecialitySelect">
                            <option value=""><?php echo htmlspecialchars($translations['select_specialty'] ?? 'Sélectionner une spécialité'); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['bio']); ?></label>
                        <textarea name="bio" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars($translations['add']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Instructor Modal -->
<div class="modal fade" id="editInstructorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editInstructorForm">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_instructor'] ?? 'Edit Instructor'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editInstructorId">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                                <input type="text" name="first_name" id="editFirstName" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                                <input type="text" name="last_name" id="editLastName" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CINE</label>
                                <input type="text" name="cine" id="editCine" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">MLE</label>
                                <input type="text" name="mle" id="editMle" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['username']); ?></label>
                                <input type="text" name="username" id="editUsername" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['password']); ?> (<?php echo htmlspecialchars($translations['leave_blank'] ?? 'leave blank to keep current'); ?>)</label>
                                <input type="password" name="password" id="editPassword" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                                <input type="email" name="email" id="editEmail" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['phone']); ?></label>
                                <input type="text" name="phone" id="editPhone" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['specialty'] ?? 'Spécialité'); ?></label>
                        <select name="speciality_id" id="editSpecialite" class="form-control">
                            <option value=""><?php echo htmlspecialchars($translations['select_specialty'] ?? 'Sélectionner une spécialité'); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['bio']); ?></label>
                        <textarea name="bio" id="editBio" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars($translations['save_changes']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let allInstructors = [];

document.addEventListener('DOMContentLoaded', function() {
    loadInstructors();
    loadSpecialities();

    // Add event listeners for filters
    document.getElementById('filterName').addEventListener('input', filterInstructors);
    document.getElementById('filterSpecialty').addEventListener('change', filterInstructors);
    document.getElementById('filterScore').addEventListener('input', filterInstructors);

    // Add instructor form submission
    document.getElementById('addInstructorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        // Real-time validation
        const firstName = document.querySelector('[name="first_name"]').value.trim();
        const lastName = document.querySelector('[name="last_name"]').value.trim();
        const username = document.querySelector('[name="username"]').value.trim();
        const password = document.querySelector('[name="password"]').value.trim();
        if (!firstName || !lastName || !username || !password) {
            alert('Please fill all required fields.');
            return;
        }

        fetch('../actions/add_instructor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalElement = document.getElementById('addInstructorModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
                this.reset();
                loadInstructors();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    // Edit instructor form submission
    document.getElementById('editInstructorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../actions/edit_instructor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalElement = document.getElementById('editInstructorModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
                loadInstructors();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});

function loadInstructors() {
    fetch('../actions/get_instructors.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error loading instructors: ' + data.error);
                return;
            }
            if (!Array.isArray(data)) {
                console.error('Expected array of instructors, got:', data);
                return;
            }
            allInstructors = data;
            filterInstructors();
        });
}

function filterInstructors() {
    const nameFilter = document.getElementById('filterName').value.toLowerCase();
    const specialtyFilter = document.getElementById('filterSpecialty').value;
    const scoreFilter = parseFloat(document.getElementById('filterScore').value) || 0;

    const filtered = allInstructors.filter(instructor => {
        const fullName = `${instructor.first_name} ${instructor.last_name}`.toLowerCase();
        const matchesName = fullName.includes(nameFilter);
        const matchesSpecialty = !specialtyFilter || instructor.speciality_id == specialtyFilter;
        const matchesScore = !scoreFilter || (instructor.average_score && parseFloat(instructor.average_score) >= scoreFilter);
        return matchesName && matchesSpecialty && matchesScore;
    });

    const tbody = document.getElementById('instructorsTableBody');
    tbody.innerHTML = '';

    filtered.forEach(instructor => {
        const subjects = instructor.subjects ? instructor.subjects.split(', ').slice(0, 3).join(', ') + (instructor.subjects.split(', ').length > 3 ? '...' : '') : '-';

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${instructor.first_name} ${instructor.last_name}</td>
            <td>${instructor.email || '-'}</td>
            <td>${instructor.cine}</td>
            <td>${instructor.mle}</td>
            <td>${instructor.nom_specialite || '-'}</td>
            <td>${instructor.average_score ? parseFloat(instructor.average_score).toFixed(1) : '-'}</td>
            <td>
                <button class="btn btn-sm btn-warning me-1" onclick="editInstructor(${instructor.id_instructor})">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteInstructor(${instructor.id_instructor})">
                    <i class="bi bi-trash"></i>
                </button>
                <button class="btn btn-sm btn-warning me-1" onclick="viewInstructor(${instructor.id_instructor})">
                    <i class="bi bi-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function editInstructor(id) {
    fetch(`../actions/get_instructors.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error loading instructor: ' + data.error);
                return;
            }
            const instructor = data;
            if (instructor) {
                document.getElementById('editInstructorId').value = instructor.id_instructor;
                document.getElementById('editFirstName').value = instructor.first_name;
                document.getElementById('editLastName').value = instructor.last_name;
                document.getElementById('editCine').value = instructor.cine;
                document.getElementById('editMle').value = instructor.mle;
                document.getElementById('editUsername').value = instructor.username;
                document.getElementById('editEmail').value = instructor.email || '';
                document.getElementById('editPhone').value = instructor.phone || '';
                document.getElementById('editSpecialite').value = instructor.speciality_id || '';
                document.getElementById('editBio').value = instructor.bio;

                const modalElement = document.getElementById('editInstructorModal');
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modal.show();
            }
        });
}

function deleteInstructor(id) {
    if (confirm('<?php echo htmlspecialchars($translations['confirm_delete'] ?? 'Are you sure you want to delete this instructor?'); ?>')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('../actions/delete_instructor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadInstructors();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function viewInstructor(id) {
    window.location.href = 'profile_instructeur.php?id=' + id;
}

function loadSpecialities() {
    fetch('../actions/get_specialities.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error loading specialities:', data.error);
                return;
            }
            const addSelect = document.getElementById('addSpecialitySelect');
            const editSelect = document.getElementById('editSpecialite');
            const filterSelect = document.getElementById('filterSpecialty');

            // Clear existing options except the first one
            addSelect.innerHTML = '<option value=""><?php echo htmlspecialchars($translations['select_specialty'] ?? 'Sélectionner une spécialité'); ?></option>';
            editSelect.innerHTML = '<option value=""><?php echo htmlspecialchars($translations['select_specialty'] ?? 'Sélectionner une spécialité'); ?></option>';
            filterSelect.innerHTML = '<option value="">All Specialties</option>';

            data.forEach(speciality => {
                const option = document.createElement('option');
                option.value = speciality.id;
                option.textContent = speciality.nom_specialite;
                addSelect.appendChild(option.cloneNode(true));
                editSelect.appendChild(option.cloneNode(true));
                filterSelect.appendChild(option.cloneNode(true));
            });
        })
        .catch(error => console.error('Error fetching specialities:', error));
}
</script>

<?php include '../templates/footer.php'; ?>
