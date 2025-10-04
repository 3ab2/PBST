<?php
require 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

include 'templates/header.php';

// Fetch statistics for charts
// Example: count of stagiaires, users, consultations, etc.
require 'config.php';

$stats = [];

// Count stagiaires
$stmt = $pdo->query("SELECT COUNT(*) as count FROM stagiaires");
$stats['stagiaires'] = $stmt->fetch()['count'];

// Count users by role
$stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
$users_by_role = $stmt->fetchAll();
foreach ($users_by_role as $row) {
    $stats['users'][$row['role']] = $row['count'];
}

// Count consultations last 7 days
$stmt = $pdo->prepare("SELECT date_consultation, COUNT(*) as count FROM consultations WHERE date_consultation >= CURDATE() - INTERVAL 7 DAY GROUP BY date_consultation ORDER BY date_consultation");
$stmt->execute();
$consultations_last_week = $stmt->fetchAll();

?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">

<div class="container mt-4">
    <h2>لوحة المتابعة</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center p-3 mb-3">
                <h5>عدد المتدربين</h5>
                <h3><?php echo $stats['stagiaires']; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-3 mb-3">
                <h5>عدد المستخدمين</h5>
                <ul class="list-unstyled">
                    <?php foreach ($stats['users'] as $role => $count): ?>
                        <li><?php echo htmlspecialchars($role); ?>: <?php echo $count; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <canvas id="consultationsChart"></canvas>
        </div>
    </div>

    <h3>آخر الاستشارات (7 أيام)</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>عدد الاستشارات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consultations_last_week as $row): ?>
            <tr>
                <td><?php echo $row['date_consultation']; ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('consultationsChart').getContext('2d');
const labels = <?php echo json_encode(array_column($consultations_last_week, 'date_consultation')); ?>;
const data = <?php echo json_encode(array_column($consultations_last_week, 'count')); ?>;

const isDark = localStorage.getItem('theme') === 'dark';
const borderColor = isDark ? 'rgb(100, 200, 255)' : 'rgb(54, 162, 235)';
const backgroundColor = isDark ? 'rgba(100, 200, 255, 0.2)' : 'rgba(54, 162, 235, 0.2)';

const consultationsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'عدد الاستشارات',
            data: data,
            borderColor: borderColor,
            backgroundColor: backgroundColor,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                precision: 0
            }
        }
    }
});
</script>

<?php include 'templates/footer.php'; ?>
