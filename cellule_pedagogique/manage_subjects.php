<?php
require '../functions.php';
check_role('cellule_pedagogique');

$csrf_token = generate_csrf_token();
$page_title = htmlspecialchars($translations['manage_subjects']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?> <span>></span> <?php echo htmlspecialchars($translations['manage_subjects']); ?></div>
<button class="breadcrumb-header" data-bs-toggle="modal" data-bs-target="#addSubjectModal"><?php echo htmlspecialchars($translations['add_subject'] ?? 'Add Subject'); ?></button>
</div>

<div class="container mt-3">
    <!-- Military Subjects Section -->
    <div class="card mb-4" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
            <h5 class="mb-0"><i class="bi bi-shield"></i> <?php echo htmlspecialchars($translations['military_subjects'] ?? 'Military Subjects'); ?></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo htmlspecialchars($translations['name']); ?></th>
                            <th><?php echo htmlspecialchars($translations['file']); ?></th>
                            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
                        </tr>
                    </thead>
                    <tbody id="militarySubjectsTableBody">
                        <!-- Military subjects will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Academic Subjects Section -->
    <div class="card" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
            <h5 class="mb-0"><i class="bi bi-book"></i> <?php echo htmlspecialchars($translations['academic_subjects'] ?? 'Academic Subjects'); ?></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo htmlspecialchars($translations['name']); ?></th>
                            <th><?php echo htmlspecialchars($translations['file']); ?></th>
                            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
                        </tr>
                    </thead>
                    <tbody id="academicSubjectsTableBody">
                        <!-- Academic subjects will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSubjectForm">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_subject'] ?? 'Add Subject'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['subject_name']); ?></label>
                        <input type="text" name="subject_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['category']); ?></label>
                        <select name="category" class="form-control" required>
                            <option value="militaire"><?php echo htmlspecialchars($translations['military'] ?? 'Military'); ?></option>
                            <option value="universitaire"><?php echo htmlspecialchars($translations['academic'] ?? 'Academic'); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['file']); ?></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov">
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

<!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSubjectForm">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_subject'] ?? 'Edit Subject'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_subject_id">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['subject_name']); ?></label>
                        <input type="text" name="subject_name" id="edit_subject_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['category']); ?></label>
                        <select name="category" id="edit_category" class="form-control" required>
                            <option value="militaire"><?php echo htmlspecialchars($translations['military'] ?? 'Military'); ?></option>
                            <option value="universitaire"><?php echo htmlspecialchars($translations['academic'] ?? 'Academic'); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['file']); ?></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.avi,.mov">
                        <small class="form-text text-muted">Leave empty to keep current file</small>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    loadSubjects();

    // Add subject form submission
    document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../actions/add_subject.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();
                this.reset();
                loadSubjects();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });

    // Edit subject button click
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '../actions/get_subjects.php',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
                $('#edit_subject_id').val(data.id_subject);
                $('#edit_subject_name').val(data.name);
                $('#edit_category').val(data.type);
                $('#editSubjectModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error loading subject data: ' + error);
            }
        });
    });

    // Edit subject form submission
    document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('../actions/edit_subject.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editSubjectModal')).hide();
                loadSubjects();
            } else {
                alert('Error: ' + data.message);
            }
        });
    });
});

function loadSubjects() {
    fetch('../actions/get_subjects.php')
        .then(response => response.json())
        .then(data => {
            const militaryTbody = document.getElementById('militarySubjectsTableBody');
            const academicTbody = document.getElementById('academicSubjectsTableBody');

            militaryTbody.innerHTML = '';
            academicTbody.innerHTML = '';

            data.forEach(subject => {
                const fileLink = subject.file ? `<a href="../${subject.file}" target="_blank" class="btn btn-sm btn-info"><i class="bi bi-file-earmark"></i> View</a>` : 'No file';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${subject.name}</td>
                    <td>${fileLink}</td>
                    <td>
                        <button class="btn btn-sm btn-warning me-1 btn-edit" data-id="${subject.id_subject}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSubject(${subject.id_subject})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;

                if (subject.type === 'militaire') {
                    militaryTbody.appendChild(row);
                } else {
                    academicTbody.appendChild(row);
                }
            });
        });
}



function deleteSubject(id) {
    if (confirm('<?php echo htmlspecialchars($translations['confirm_delete_subject'] ?? 'Are you sure you want to delete this subject?'); ?>')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('../actions/delete_subject.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSubjects();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
</script>

<?php include '../templates/footer.php'; ?>
