<?php
require '../functions.php';
check_role('docteur');
$page_title = htmlspecialchars($translations['doctor_dashboard_title']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_docteur']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_dashboard']); ?></div>


<!-- Cards Row -->
<div class="row mb-4 mt-5">
    <div class="col-lg-6 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-clipboard-data fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['consultations'] ?? 'الاستشارات'); ?></h5>
                <p class="card-text fs-3" id="cardConsultationsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-people fs-1 text-success"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['patients'] ?? 'المرضى'); ?></h5>
                <p class="card-text fs-3" id="cardPatientsCount">0</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    updateCards();
});
</script>

<?php include '../templates/footer.php'; ?>
