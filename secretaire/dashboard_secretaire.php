<?php
require '../functions.php';
check_role('secretaire');
$page_title = htmlspecialchars($translations['secretaire_dashboard_title']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include '../templates/header.php'; ?>
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_secretaire']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_dashboard']); ?></div>
<p><?php echo htmlspecialchars($translations['secretaire_dashboard_welcome'] ?? 'مرحبا بك في لوحة التحكم الخاصة بالسكرتري.'); ?></p>

<!-- Cards Row -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['trainees']); ?></h5>
                <p class="card-text fs-3" id="cardTraineesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['stages']); ?></h5>
                <p class="card-text fs-3" id="cardStagesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['specialities']); ?></h5>
                <p class="card-text fs-3" id="cardSpecialitiesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['notes']); ?></h5>
                <p class="card-text fs-3" id="cardNotesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['permissions']); ?></h5>
                <p class="card-text fs-3" id="cardPermissionsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <h5 class="card-title"><?php echo htmlspecialchars($translations['sanctions']); ?></h5>
                <p class="card-text fs-3" id="cardSanctionsCount">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['manage_stages'] ?? 'إدارة الدورات'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="stagesChart"></canvas>
            </div>
            <div class="card-footer">
                <a href="manage_stages.php" class="btn btn-primary btn-sm"><?php echo htmlspecialchars($translations['manage'] ?? 'إدارة'); ?></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['manage_specialites'] ?? 'إدارة التخصصات'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="specialitesChart"></canvas>
            </div>
            <div class="card-footer">
                <a href="manage_specialites.php" class="btn btn-primary btn-sm"><?php echo htmlspecialchars($translations['manage'] ?? 'إدارة'); ?></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['manage_permissions'] ?? 'إدارة الأذونات'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="permissionsChart"></canvas>
            </div>
            <div class="card-footer">
                <a href="manage_permissions.php" class="btn btn-primary btn-sm"><?php echo htmlspecialchars($translations['manage'] ?? 'إدارة'); ?></a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['manage_stagiaires'] ?? 'إدارة المتدربين'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="stagiairesChart"></canvas>
            </div>
            <div class="card-footer">
                <a href="manage_stagiaires.php" class="btn btn-primary btn-sm"><?php echo htmlspecialchars($translations['manage'] ?? 'إدارة'); ?></a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.body.classList.contains('dark');
    const textColor = isDark ? '#ffffff' : '#000000';
    const gridColor = isDark ? '#444444' : '#e0e0e0';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    function createChart(canvasId, type, data, options = {}) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        return new Chart(ctx, {
            type: type,
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    },
                    tooltip: {
                        bodyColor: textColor,
                        titleColor: textColor,
                        backgroundColor: isDark ? '#222' : '#fff',
                        borderColor: gridColor,
                        borderWidth: 1
                    },
                    datalabels: {
                        color: textColor,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: textColor },
                        grid: { color: gridColor }
                    }
                },
                ...options
            }
        });
    }

    let charts = {};

    function updateCards() {
        // Trainees
        fetch('chart_data.php?type=stagiaires')
            .then(response => response.json())
            .then(data => {
                const total = data.reduce((sum, item) => sum + parseInt(item.count), 0);
                document.getElementById('cardTraineesCount').textContent = total;
            });

        // Stages
        fetch('chart_data.php?type=stages')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cardStagesCount').textContent = data.total;
            });

        // Specialities
        fetch('chart_data.php?type=specialites')
            .then(response => response.json())
            .then(data => {
                const total = data.reduce((sum, item) => sum + parseInt(item.count), 0);
                document.getElementById('cardSpecialitiesCount').textContent = total;
            });

        // Notes
        fetch('chart_data.php?type=notes')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cardNotesCount').textContent = data.total;
            });

        // Permissions
        fetch('chart_data.php?type=permissions')
            .then(response => response.json())
            .then(data => {
                const total = data.reduce((sum, item) => sum + parseInt(item.count), 0);
                document.getElementById('cardPermissionsCount').textContent = total;
            });

        // Sanctions
        fetch('chart_data.php?type=sanctions')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cardSanctionsCount').textContent = data.total;
            });
    }

    function updateCharts() {
        // Stages (Courses) - Percentage of trainees in each course
        fetch('chart_data.php?type=stages')
            .then(response => response.json())
            .then(data => {
                // We need to get total trainees count to calculate percentage
                fetch('chart_data.php?type=stagiaires')
                    .then(resp => resp.json())
                    .then(stagiairesData => {
                        const totalTrainees = stagiairesData.reduce((sum, item) => sum + item.count, 0);
                        const labels = data.total ? ['Courses'] : [];
                        const counts = data.total ? [data.total] : [];
                        // Instead, we will use stagiairesData grouped by course (specialite) for percentage
                        // But since stages data is total courses count, we will use stagiairesData for percentages
                        // So we will use stagiairesData for labels and percentages
                        const labelsPerc = stagiairesData.map(item => item.nom_specialite);
                        const countsPerc = stagiairesData.map(item => ((item.count / totalTrainees) * 100).toFixed(2));
                        if (charts.stages) charts.stages.destroy();
                        charts.stages = createChart('stagesChart', 'bar', {
                            labels: labelsPerc,
                            datasets: [{
                                label: '% of Trainees',
                                data: countsPerc,
                                backgroundColor: '#4BC0C0'
                            }]
                        }, {
                            plugins: {
                                datalabels: {
                                    display: true,
                                    color: textColor,
                                    font: {
                                        weight: 'bold'
                                    },
                                    formatter: function(value) {
                                        return value + '%';
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            }
                        });
                    });
            });

        // Specialites - Percentage of trainees in each specialization
        fetch('chart_data.php?type=stagiaires')
            .then(response => response.json())
            .then(data => {
                const total = data.reduce((sum, item) => sum + item.count, 0);
                const labels = data.map(item => item.nom_specialite);
                const counts = data.map(item => ((item.count / total) * 100).toFixed(2));
                if (charts.specialites) charts.specialites.destroy();
                charts.specialites = createChart('specialitesChart', 'bar', {
                    labels: labels,
                    datasets: [{
                        label: '% of Trainees',
                        data: counts,
                        backgroundColor: '#FF6384'
                    }]
                }, {
                    plugins: {
                        datalabels: {
                            display: true,
                            color: textColor,
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                });
            });

        // Permissions - Number of permissions per month
        fetch('chart_data.php?type=permissions')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.statut);
                const counts = data.map(item => item.count);
                if (charts.permissions) charts.permissions.destroy();
                charts.permissions = createChart('permissionsChart', 'bar', {
                    labels: labels,
                    datasets: [{
                        label: 'Permissions',
                        data: counts,
                        backgroundColor: '#36A2EB'
                    }]
                }, {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                });
            });

        // Stagiaires - Number of trainees in each specialization
        fetch('chart_data.php?type=stagiaires')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nom_specialite);
                const counts = data.map(item => item.count);
                if (charts.stagiaires) charts.stagiaires.destroy();
                charts.stagiaires = createChart('stagiairesChart', 'bar', {
                    labels: labels,
                    datasets: [{
                        label: 'Trainees',
                        data: counts,
                        backgroundColor: '#FF9F40'
                    }]
                }, {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                });
            });
    }

    updateCharts();
    updateCards();
});
</script>

<?php include '../templates/footer.php'; ?>
