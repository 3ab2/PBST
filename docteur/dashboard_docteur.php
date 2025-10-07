<?php
require '../functions.php';
check_role('docteur');
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['doctor_dashboard_title'] ?? 'لوحة تحكم الطبيب'); ?></h2>
<p><?php echo htmlspecialchars($translations['doctor_dashboard_welcome'] ?? 'مرحبا بك في لوحة تحكم الطبيب.'); ?></p>

<!-- Cards Row -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-clipboard-data fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['consultations'] ?? 'الاستشارات'); ?></h5>
                <p class="card-text fs-3" id="cardConsultationsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-12 mb-3">
        <div class="card text-center">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-people fs-1 text-success"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['patients'] ?? 'المرضى'); ?></h5>
                <p class="card-text fs-3" id="cardPatientsCount">0</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['consultations_per_month'] ?? 'الاستشارات شهريا'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="consultationsChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header"><?php echo htmlspecialchars($translations['consultations_per_specialty'] ?? 'الاستشارات حسب التخصص'); ?></div>
            <div class="card-body" style="height: 300px;">
                <canvas id="consultationsSpecialtyChart"></canvas>
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
        // Total Consultations
        fetch('../admin/chart_data.php?type=consultations')
            .then(response => response.json())
            .then(data => {
                const total = data.reduce((sum, item) => sum + parseInt(item.count), 0);
                document.getElementById('cardConsultationsCount').textContent = total;
            });

        // Patients
        fetch('../admin/chart_data.php?type=patients')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cardPatientsCount').textContent = data.total;
            });
    }

    function updateCharts() {
        // Consultations per month
        fetch('../admin/chart_data.php?type=consultations')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.month);
                const counts = data.map(item => item.count);
                if (charts.consultations) charts.consultations.destroy();
                charts.consultations = createChart('consultationsChart', 'bar', {
                    labels: labels,
                    datasets: [{
                        label: '<?php echo htmlspecialchars($translations['consultations'] ?? 'الاستشارات'); ?>',
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

        // Consultations per specialty
        fetch('../admin/chart_data.php?type=consultations_specialty')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.nom_specialite);
                const counts = data.map(item => item.count);
                if (charts.consultationsSpecialty) charts.consultationsSpecialty.destroy();
                charts.consultationsSpecialty = createChart('consultationsSpecialtyChart', 'pie', {
                    labels: labels,
                    datasets: [{
                        label: '<?php echo htmlspecialchars($translations['consultations'] ?? 'الاستشارات'); ?>',
                        data: counts,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                    }]
                });
            });
    }

    updateCharts();
    updateCards();
});
</script>

<?php include '../templates/footer.php'; ?>
