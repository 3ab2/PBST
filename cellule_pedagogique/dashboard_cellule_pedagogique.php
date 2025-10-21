<?php
require '../functions.php';
check_role('cellule_pedagogique');
$csrf_token = generate_csrf_token();
$page_title = 'Cellule Pédagogique Dashboard';
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?>
    <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_dashboard']); ?></div>

<div class="container mt-5">
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-4">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addObservationModal">
                <i class="bi bi-plus-circle"></i> <?php echo htmlspecialchars($translations['add_observation']); ?>
            </button>
        </div>
        <div class="col-md-4">
            <a href="manage_observations.php" class="btn btn-secondary w-100">
                <i class="bi bi-list"></i> <?php echo htmlspecialchars($translations['view_observations']); ?>
            </a>
        </div>
        <div class="col-md-4">
            <a href="stats.php" class="btn btn-info w-100">
                <i class="bi bi-bar-chart"></i> <?php echo htmlspecialchars($translations['view_stats']); ?>
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="manage_instructors.php" class="btn btn-success w-100">
                <i class="bi bi-person-gear"></i> <?php echo htmlspecialchars($translations['manage_instructors']); ?>
            </a>
        </div>
        <div class="col-md-6">
            <a href="manage_subjects.php" class="btn btn-warning w-100">
                <i class="bi bi-book-half"></i> <?php echo htmlspecialchars($translations['manage_subjects']); ?>
            </a>
        </div>
    </div>

  

<!-- Add Observation Modal -->
<div class="modal fade" id="addObservationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_observation']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="observation-form">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="instructor-select"
                                class="form-label"><?php echo htmlspecialchars($translations['instructor']); ?></label>
                            <select class="form-select" id="instructor-select" name="instructor_id" required>
                                <option value=""><?php echo htmlspecialchars($translations['select_instructor']); ?>
                                </option>
                                <!-- AJAX loaded -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="subject-select"
                                class="form-label"><?php echo htmlspecialchars($translations['subject']); ?></label>
                            <select class="form-select" id="subject-select" name="subject_id" required>
                                <option value=""><?php echo htmlspecialchars($translations['select_subject']); ?>
                                </option>
                                <!-- AJAX loaded based on instructor -->
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="obs-date"
                                class="form-label"><?php echo htmlspecialchars($translations['date']); ?></label>
                            <input type="date" class="form-control" id="obs-date" name="obs_date" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="heure-debut"
                                class="form-label"><?php echo htmlspecialchars($translations['start_time']); ?></label>
                            <input type="time" class="form-control" id="heure-debut" name="heure_debut" required>
                        </div>
                        <div class="col-md-6">
                            <label for="heure-fin"
                                class="form-label"><?php echo htmlspecialchars($translations['end_time']); ?></label>
                            <input type="time" class="form-control" id="heure-fin" name="heure_fin" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="rating"
                                class="form-label"><?php echo htmlspecialchars($translations['rating']); ?></label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="positive"><?php echo htmlspecialchars($translations['positive']); ?>
                                </option>
                                <option value="negative"><?php echo htmlspecialchars($translations['negative']); ?>
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="score"
                                class="form-label"><?php echo htmlspecialchars($translations['score']); ?>
                                (1-10)</label>
                            <input type="number" class="form-control" id="score" name="score" min="1" max="10">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="comment"
                            class="form-label"><?php echo htmlspecialchars($translations['comment']); ?></label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                    <button type="submit"
                        class="btn btn-primary mt-3"><?php echo htmlspecialchars($translations['save_observation']); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card text-center"
            style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 220px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <i class="bi bi-person-badge fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['instructors']); ?></h5>
                <p class="card-text fs-3" id="cardInstructorsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card text-center"
            style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 220px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <i class="bi bi-book fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['subjects']); ?></h5>
                <p class="card-text fs-3" id="cardSubjectsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card text-center"
            style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 220px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <i class="bi bi-eye fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['observations']); ?></h5>
                <p class="card-text fs-3" id="cardObservationsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12 mb-3">
        <div class="card text-center"
            style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 220px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                <i class="bi bi-trophy fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['best_instructor']); ?></h5>
                <p class="card-text fs-5" id="cardTopInstructor">-</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Observations Section -->
<div class="row mb-4">
    <div class="col-12">
        <h4><?php echo htmlspecialchars($translations['recent_observations']); ?></h4>
        <div id="recent-observations" class="row">
            <!-- Recent observations will be loaded here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update dashboard cards
        updateDashboardCards();

        // Load instructors
        fetch('../actions/get_instructors.php')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('instructor-select');
                data.forEach(instructor => {
                    const option = document.createElement('option');
                    option.value = instructor.id_instructor;
                    option.textContent = instructor.first_name + ' ' + instructor.last_name;
                    select.appendChild(option);
                });
            });

        // Load all subjects (not filtered by instructor)
        function loadAllSubjects() {
            const subjectSelect = document.getElementById('subject-select');
            subjectSelect.innerHTML = '<option value=""><?php echo htmlspecialchars($translations['select_subject']); ?></option>';
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

        // Submit observation
        document.getElementById('observation-form').addEventListener('submit', function (e) {
            e.preventDefault();

            // Validate time: heure_fin must be later than heure_debut
            const heureDebut = document.getElementById('heure-debut').value;
            const heureFin = document.getElementById('heure-fin').value;
            if (heureFin <= heureDebut) {
                alert('End time must be later than start time');
                return;
            }

            const formData = new FormData(this);
            fetch('../actions/add_observation.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addObservationModal')).hide();
                        this.reset();
                        loadRecentObservations();
                        updateDashboardCards();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });

        // Load recent observations
        loadRecentObservations();

        function loadRecentObservations() {
            fetch('../actions/get_recent_observations.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('recent-observations');
                    container.innerHTML = '';
                    if (data.length === 0) {
                        container.innerHTML = '<p>No recent observations found.</p>';
                        return;
                    }
                    data.forEach(obs => {
                        const div = document.createElement('div');
                        div.className = 'col-md-6 mb-3';
                        div.innerHTML = `
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">${obs.instructor_name} - ${obs.subject_name}</h6>
                                <p class="card-text">${obs.obs_date} ${obs.heure_debut} - ${obs.heure_fin} - ${obs.rating}</p>
                                <p class="card-text">${obs.comment}</p>
                            </div>
                        </div>
                    `;
                        container.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error loading recent observations:', error);
                    const container = document.getElementById('recent-observations');
                    container.innerHTML = '<p>Error loading recent observations.</p>';
                });
        }

        function updateDashboardCards() {
            // Instructors count
            fetch('../actions/get_instructors.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cardInstructorsCount').textContent = data.length;
                });

            // Subjects count
            fetch('../actions/get_subjects.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cardSubjectsCount').textContent = data.length;
                });

            // Observations count
            fetch('../actions/get_total_observations.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cardObservationsCount').textContent = data.total;
                });

            // Top instructor
            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1; // JS months are 0-based
            fetch(`../actions/get_best_instructor.php?year=${currentYear}&month=${currentMonth}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.name) {
                        let html = data.name + '<br><small>' + data.positive_count + ' positive';
                        if (data.avg_score) {
                            html += ', Avg score: ' + parseFloat(data.avg_score).toFixed(1);
                        }
                        html += '</small>';
                        document.getElementById('cardTopInstructor').innerHTML = html;
                    } else {
                        document.getElementById('cardTopInstructor').textContent = 'No observations yet';
                    }
                });
        }
    });
</script>

<?php include '../templates/footer.php'; ?>