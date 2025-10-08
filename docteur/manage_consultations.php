<?php
require '../functions.php';
check_role('docteur');

// Fetch consultations for this doctor
$sql = "SELECT c.*, s.nom, s.prenom, s.matricule 
        FROM consultations c
        JOIN stagiaires s ON c.id_stagiaire = s.id
        WHERE c.id_docteur = ?
        ORDER BY c.date_consultation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$consultations = $stmt->fetchAll();

// Fetch stagiaires for dropdown
$stagiaires = $pdo->query("SELECT id, matricule, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();

$csrf_token = generate_csrf_token();
$page_title = htmlspecialchars($translations['manage_consultations']) ;
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['manage_consultations']); ?></h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addConsultationModal"><?php echo htmlspecialchars($translations['add_consultation']); ?></button>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control" placeholder="<?php echo htmlspecialchars($translations['search']); ?>">
    </div>
    <div class="col">
        <select id="filterStagiaire" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_trainees']); ?></option>
            <?php
            foreach ($stagiaires as $stagiaire) {
                $fullName = htmlspecialchars($stagiaire['matricule'] . ' - ' . $stagiaire['nom'] . ' ' . $stagiaire['prenom']);
                echo "<option value=\"$fullName\">$fullName</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <input type="date" id="dateFrom" class="form-control" placeholder="<?php echo htmlspecialchars($translations['from_date']); ?>">
    </div>
    <div class="col">
        <input type="date" id="dateTo" class="form-control" placeholder="<?php echo htmlspecialchars($translations['to_date']); ?>">
    </div>
</div>

<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['trainee']); ?></th>
            <th><?php echo htmlspecialchars($translations['consultation_date']); ?></th>
            <th><?php echo htmlspecialchars($translations['consultation_file']); ?></th>
            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
        </tr>
    </thead>
    <tbody id="consultationsTableBody">
        <?php foreach ($consultations as $cons): ?>
        <tr data-stagiaire="<?php echo htmlspecialchars($cons['matricule'] . ' - ' . $cons['nom'] . ' ' . $cons['prenom']); ?>" data-date="<?php echo $cons['date_consultation']; ?>">
            <td><?php echo htmlspecialchars($cons['matricule'] . ' - ' . $cons['nom'] . ' ' . $cons['prenom']); ?></td>
            <td><?php echo $cons['date_consultation']; ?></td>
            <td>
                <?php if ($cons['file'] != null): ?>
                <a href="../<?php echo $cons['file']; ?>" class="btn btn-sm btn-primary"><?php echo htmlspecialchars($translations['download_file']); ?></a>
                <?php endif; ?>
            </td>
            <td>
                <a href="../view_consultation.php?id=<?php echo $cons['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editConsultationModal<?php echo $cons['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_consultation.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_consultation']); ?>');">
                    <input type="hidden" name="id" value="<?php echo $cons['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Consultation Modal -->
<div class="modal fade" id="addConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="../actions/add_consultation.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_consultation']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['stagiaire']); ?></label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['select'] . ' ' . $translations['stagiaire']); ?></option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                                <option value="<?php echo $stagiaire['id']; ?>"><?php echo htmlspecialchars($stagiaire['matricule'] . ' - ' . $stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['date_consultation']); ?></label>
                        <input type="date" name="date_consultation" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['diagnostic']); ?></label>
                        <textarea name="diagnostic" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['traitement']); ?></label>
                        <textarea name="traitement" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['remarques']); ?></label>
                        <textarea name="remarques" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['upload_consultation_file']); ?></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
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

<!-- Edit Consultation Modals -->
<?php foreach ($consultations as $cons): ?>
<div class="modal fade" id="editConsultationModal<?php echo $cons['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="../actions/edit_consultation.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_consultation']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $cons['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['stagiaire']); ?></label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['select'] . ' ' . $translations['stagiaire']); ?></option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                                <option value="<?php echo $stagiaire['id']; ?>" <?php echo ($stagiaire['id'] == $cons['id_stagiaire']) ? 'selected="selected"' : ''; ?>>
                                    <?php echo htmlspecialchars($stagiaire['matricule'] . ' - ' . $stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['date_consultation']); ?></label>
                        <input type="date" name="date_consultation" class="form-control" value="<?php echo $cons['date_consultation']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['diagnostic']); ?></label>
                        <textarea name="diagnostic" class="form-control" rows="3"><?php echo htmlspecialchars($cons['diagnostic']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['traitement']); ?></label>
                        <textarea name="traitement" class="form-control" rows="3"><?php echo htmlspecialchars($cons['traitement']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['remarques']); ?></label>
                        <textarea name="remarques" class="form-control" rows="3"><?php echo htmlspecialchars($cons['remarques']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['upload_consultation_file']); ?></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
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
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStagiaire = document.getElementById('filterStagiaire');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const tbody = document.getElementById('consultationsTableBody');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const stagiaireValue = filterStagiaire.value;
        const fromValue = dateFrom.value;
        const toValue = dateTo.value;

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const stagiaire = row.getAttribute('data-stagiaire');
            const date = new Date(row.getAttribute('data-date'));

            const matchesSearch = text.includes(searchValue);
            const matchesStagiaire = !stagiaireValue || stagiaire === stagiaireValue;
            const matchesFrom = !fromValue || date >= new Date(fromValue);
            const matchesTo = !toValue || date <= new Date(toValue);

            if (matchesSearch && matchesStagiaire && matchesFrom && matchesTo) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterStagiaire.addEventListener('change', filterTable);
    dateFrom.addEventListener('change', filterTable);
    dateTo.addEventListener('change', filterTable);
});
</script>

<?php include '../templates/footer.php'; ?>
