<?php
require '../functions.php';
check_role('secretaire');
$page_title = htmlspecialchars($translations['secretaire_dashboard_title']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_secretaire']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_dashboard']); ?></div>


<!-- Cards Row -->
<div class="row mb-4 mt-5">
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-people fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['trainees']); ?></h5>
                <p class="card-text fs-3" id="cardTraineesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-calendar-event fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['stages']); ?></h5>
                <p class="card-text fs-3" id="cardStagesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-tags fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['specialities']); ?></h5>
                <p class="card-text fs-3" id="cardSpecialitiesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-journal-text fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['notes']); ?></h5>
                <p class="card-text fs-3" id="cardNotesCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-shield-check fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['permissions']); ?></h5>
                <p class="card-text fs-3" id="cardPermissionsCount">0</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12 mb-3">
        <div class="card text-center" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-body" style="min-height: 120px;">
                <i class="bi bi-exclamation-triangle fs-1 text-primary"></i>
                <h5 class="card-title"><?php echo htmlspecialchars($translations['sanctions']); ?></h5>
                <p class="card-text fs-3" id="cardSanctionsCount">0</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

    updateCards();
});
</script>

<?php include '../templates/footer.php'; ?>
