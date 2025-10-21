<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);
$page_title = 'Statistics';
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?> <span>></span> <?php echo htmlspecialchars($translations['statistics']); ?></div>
</div>

<div class="container mt-3">

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="year-select" class="form-label"><?php echo htmlspecialchars($translations['year']); ?></label>
            <select class="form-select" id="year-select">
                <?php
                $current_year = date('Y');
                for ($i = $current_year; $i >= $current_year - 5; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="month-select" class="form-label"><?php echo htmlspecialchars($translations['month']); ?></label>
            <select class="form-select" id="month-select">
                <option value=""><?php echo htmlspecialchars($translations['all_months']); ?></option>
                <?php
                $months = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                ];
                foreach ($months as $num => $name) {
                    echo "<option value='$num'>$name</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Rankings Table -->
    <h3><?php echo htmlspecialchars($translations['monthly_rankings']); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover" id="rankings-table">
            <thead>
                <tr>
                    <th><?php echo htmlspecialchars($translations['rank']); ?></th>
                    <th><?php echo htmlspecialchars($translations['instructor']); ?></th>
                    <th><?php echo htmlspecialchars($translations['positive']); ?></th>
                    <th><?php echo htmlspecialchars($translations['negative']); ?></th>
                    <th><?php echo htmlspecialchars($translations['total']); ?></th>
                    <th><?php echo htmlspecialchars($translations['average_score']); ?></th>
                    <th><?php echo htmlspecialchars($translations['actions'] ?? 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- AJAX loaded -->
            </tbody>
        </table>
    </div>

    <!-- Charts -->
    <div class="row mt-5">
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-body" style="min-height: 360px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                    <h4 class="card-title"><?php echo htmlspecialchars($translations['positive_vs_negative_observations']); ?></h4>
                    <div style="width:100%; max-width:360px; aspect-ratio:1/1; display:flex; justify-content:center; align-items:center;">
                        <canvas id="observationsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-4">
            <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div class="card-body" style="min-height: 360px; display:flex; flex-direction:column; justify-content:center; align-items:center;">
                    <h4 class="card-title"><?php echo htmlspecialchars($translations['top_instructor_performance']); ?></h4>
                    <div style="width:100%; max-width:360px; aspect-ratio:1/1; display:flex; justify-content:center; align-items:center;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const translations = {
    generate_certificate: '<?php echo htmlspecialchars($translations['generate_certificate']); ?>',
    generate_certificate_confirmation: '<?php echo htmlspecialchars($translations['generate_certificate_confirmation']); ?>',
    positive: '<?php echo htmlspecialchars($translations['positive']); ?>',
    negative: '<?php echo htmlspecialchars($translations['negative']); ?>',
    average_score: '<?php echo htmlspecialchars($translations['average_score']); ?>',
    view_profile: '<?php echo htmlspecialchars($translations['view_profile'] ?? "View Profile"); ?>',
    download_certificate: '<?php echo htmlspecialchars($translations['download_certificate'] ?? "Download Certificate"); ?>',
    select_month_first: '<?php echo htmlspecialchars($translations['select_month_first'] ?? "Select a month first"); ?>'
};

document.addEventListener('DOMContentLoaded', function() {
    let observationsChart, performanceChart;

    // Load data on filter change
    document.getElementById('year-select').addEventListener('change', loadStats);
    document.getElementById('month-select').addEventListener('change', loadStats);

    loadStats();

    function loadStats() {
        const year = document.getElementById('year-select').value;
        const month = document.getElementById('month-select').value;

        // Load rankings
        fetch(`../actions/get_monthly_stats.php?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                // Sort data by avg_score descending for correct ranking
                data.sort((a, b) => (b.avg_score || 0) - (a.avg_score || 0));
                // Determine top 3 instructors based on avg_score
                const top3Ids = data.slice(0, 3).map(stat => stat.instructor_id);

                const tbody = document.querySelector('#rankings-table tbody');
                tbody.innerHTML = '';
                data.forEach((stat, index) => {
                    const row = document.createElement('tr');
                    const avgScore = stat.avg_score ? parseFloat(stat.avg_score).toFixed(1) : 'N/A';
                    const place = index + 1;
                    const viewBtn = `<button class="btn btn-sm btn-info view-details" data-id="${stat.instructor_id}" data-bs-toggle="tooltip" title="${translations.view_profile}"><i class="fa-solid fa-eye"></i></button>`;
                    let downloadBtn = '';
                    const isTop3 = top3Ids.includes(stat.instructor_id);
                    if (isTop3) {
                        if (month) {
                            downloadBtn = `<button class="btn btn-sm btn-success ms-2 download-cert" data-id="${stat.instructor_id}" data-year="${year}" data-month="${month}" data-place="${place}" data-bs-toggle="tooltip" title="${translations.download_certificate}"><i class="fa-solid fa-download"></i></button>`;
                        } else {
                            downloadBtn = `<span class="ms-2" data-bs-toggle="tooltip" title="${translations.select_month_first}"><button class="btn btn-sm btn-secondary disabled" tabindex="-1" aria-disabled="true"><i class="fa-solid fa-download"></i></button></span>`;
                        }
                    } else {
                        downloadBtn = `<span class=\"ms-2\" data-bs-toggle=\"tooltip\" title=\"${translations.download_certificate}\"><button class=\"btn btn-sm btn-secondary disabled\" tabindex=\"-1\" aria-disabled=\"true\"><i class=\"fa-solid fa-download\"></i></button></span>`;
                    }

                    row.innerHTML = `
                        <td>${place}</td>
                        <td>
                            <div>${stat.first_name} ${stat.last_name}</div>
                        </td>
                        <td>${stat.positive_count}</td>
                        <td>${stat.negative_count}</td>
                        <td>${stat.total}</td>
                        <td>${avgScore}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-2">${viewBtn} ${downloadBtn}</div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                // Initialize tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl); });

                // Attach view details
                document.querySelectorAll('.view-details').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const instructorId = this.dataset.id;
                        window.location.href = `profile_instructeur.php?id=${instructorId}`;
                    });
                });

                // Attach certificate generation for top 3
                document.querySelectorAll('.download-cert').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const instructorId = this.dataset.id;
                        const year = this.dataset.year;
                        const month = this.dataset.month;
                        const place = this.dataset.place;
                        if (confirm(translations.generate_certificate_confirmation)) {
                            window.open(`../generate_certificate.php?instructor_id=${instructorId}&year=${year}&month=${month}&position=${place}`, '_blank');
                        }
                    });
                });

                // Update charts
                updateCharts(data);
            });
    }

    function updateCharts(data) {
        // Destroy existing charts
        if (observationsChart) observationsChart.destroy();
        if (performanceChart) performanceChart.destroy();

        // Observations donut (total positive vs negative)
        const ctx1 = document.getElementById('observationsChart').getContext('2d');
        const totalPositive = data.reduce((sum, stat) => sum + (parseInt(stat.positive_count) || 0), 0);
        const totalNegative = data.reduce((sum, stat) => sum + (parseInt(stat.negative_count) || 0), 0);

        observationsChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: [translations.positive, translations.negative],
                datasets: [{
                    data: [totalPositive, totalNegative],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                cutout: '60%',
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Performance donut (top 5 by avg_score)
        const ctx2 = document.getElementById('performanceChart').getContext('2d');
        const top5 = data.slice(0, 5);
        const labels = top5.map(stat => stat.first_name + ' ' + stat.last_name);
        const scores = top5.map(stat => stat.avg_score ? parseFloat(stat.avg_score) : 0);

        performanceChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: scores,
                    backgroundColor: ['#007bff', '#17a2b8', '#6f42c1', '#20c997', '#ffc107'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                cutout: '60%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>

<?php include '../templates/footer.php'; ?>
