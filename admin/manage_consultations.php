<?php
require '../functions.php';
check_role('admin');

// Fetch consultations with stagiaire and docteur names
$sql = "SELECT c.*, s.nom AS stagiaire_nom, s.prenom AS stagiaire_prenom, u.nom AS docteur_nom, u.prenom AS docteur_prenom
        FROM consultations c
        JOIN stagiaires s ON c.id_stagiaire = s.id
        JOIN users u ON c.id_docteur = u.id
        ORDER BY c.date_consultation DESC";
$consultations = $pdo->query($sql)->fetchAll();

$stagiaires = $pdo->query("SELECT id, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();
$docteurs = $pdo->query("SELECT id, nom, prenom FROM users WHERE role = 'docteur' ORDER BY nom")->fetchAll();

$csrf_token = generate_csrf_token();
$page_title = htmlspecialchars($translations['consultations']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_admin']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_consultation']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_liste_consultations']); ?></div>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConsultationModal"><?php echo htmlspecialchars($translations['add_consultation'] ?? 'إضافة استشارة'); ?></button>
</div>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control" placeholder="<?php echo htmlspecialchars($translations['search'] ?? 'ابحث...'); ?>">
    </div>
    <div class="col">
        <select id="filterStagiaire" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_trainees'] ?? 'كل المتدربين'); ?></option>
            <?php
            foreach ($stagiaires as $stagiaire) {
                echo "<option value=\"" . htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']) . "\">" . htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select id="filterDocteur" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_doctors'] ?? 'كل الأطباء'); ?></option>
            <?php
            foreach ($docteurs as $docteur) {
                echo "<option value=\"" . htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']) . "\">" . htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <input type="date" id="dateFrom" class="form-control" placeholder="<?php echo htmlspecialchars($translations['from_date'] ?? 'من تاريخ'); ?>">
    </div>
    <div class="col">
        <input type="date" id="dateTo" class="form-control" placeholder="<?php echo htmlspecialchars($translations['to_date'] ?? 'إلى تاريخ'); ?>">
    </div>
</div>

<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['id'] ?? 'المعرف'); ?></th>
            <th><?php echo htmlspecialchars($translations['trainee'] ?? 'المتدرب'); ?></th>
            <th><?php echo htmlspecialchars($translations['consultation_date'] ?? 'تاريخ الاستشارة'); ?></th>
            <th><?php echo htmlspecialchars($translations['consultation_file'] ?? 'ملف الاستشارة'); ?></th>
            <th><?php echo htmlspecialchars($translations['actions'] ?? 'إجراءات'); ?></th>
        </tr>
    </thead>
    <tbody id="consultationsTableBody">
        <?php foreach ($consultations as $consultation): ?>
        <tr data-stagiaire="<?php echo htmlspecialchars($consultation['stagiaire_nom'] . ' ' . $consultation['stagiaire_prenom']); ?>" data-docteur="<?php echo htmlspecialchars($consultation['docteur_nom'] . ' ' . $consultation['docteur_prenom']); ?>" data-date="<?php echo $consultation['date_consultation']; ?>">
            <td><?php echo $consultation['id']; ?></td>
            <td><?php echo htmlspecialchars($consultation['stagiaire_nom'] . ' ' . $consultation['stagiaire_prenom']); ?></td>
            <td><?php echo $consultation['date_consultation']; ?></td>
            <td>
                <?php if ($consultation['file'] != null): ?>
                <a href="../<?php echo $consultation['file']; ?>" class="btn btn-sm btn-primary"><?php echo htmlspecialchars($translations['download_file']); ?></a>
                <?php endif; ?>
            </td>
            <td>
                <a href="../view_consultation.php?id=<?php echo $consultation['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editConsultationModal<?php echo $consultation['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_consultation.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_consultation']); ?>');">
                    <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editConsultationModal<?php echo $consultation['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="edit-consultation-form" method="post" action="../actions/edit_consultation.php">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_consultation']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['stagiaire']); ?></label>
                                <select name="id_stagiaire" class="form-control" required>
                                    <?php foreach ($stagiaires as $stagiaire):
                                        $selected = ($consultation['id_stagiaire'] == $stagiaire['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $stagiaire['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['docteur']); ?></label>
                                <select name="id_docteur" class="form-control" required>
                                    <?php foreach ($docteurs as $docteur):
                                        $selected = ($consultation['id_docteur'] == $docteur['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $docteur['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['date_consultation']); ?></label>
                                <input type="date" name="date_consultation" class="form-control" value="<?php echo $consultation['date_consultation']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['diagnostic']); ?></label>
                                <textarea name="diagnostic" class="form-control"><?php echo htmlspecialchars($consultation['diagnostic']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['traitement']); ?></label>
                                <textarea name="traitement" class="form-control"><?php echo htmlspecialchars($consultation['traitement']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['remarques']); ?></label>
                                <textarea name="remarques" class="form-control"><?php echo htmlspecialchars($consultation['remarques']); ?></textarea>
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
    </tbody>
</table>

<!-- Add Consultation Modal -->
<div class="modal fade" id="addConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addConsultationForm" method="post" action="../actions/add_consultation.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_consultation'] ?? 'إضافة استشارة'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['stagiaire'] ?? 'المتدرب'); ?></label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['select'] ?? 'اختر'); ?></option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                            <option value="<?php echo $stagiaire['id']; ?>">
                                <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['docteur'] ?? 'الطبيب'); ?></label>
                        <select name="id_docteur" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['select'] ?? 'اختر'); ?></option>
                            <?php foreach ($docteurs as $docteur): ?>
                            <option value="<?php echo $docteur['id']; ?>">
                                <?php echo htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['date_consultation'] ?? 'تاريخ الاستشارة'); ?></label>
                        <input type="date" name="date_consultation" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['diagnostic'] ?? 'التشخيص'); ?></label>
                        <textarea name="diagnostic" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['traitement'] ?? 'العلاج'); ?></label>
                        <textarea name="traitement" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['remarques'] ?? 'ملاحظات'); ?></label>
                        <textarea name="remarques" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['upload_consultation_file'] ?? 'Upload Consultation File'); ?></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel'] ?? 'إلغاء'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars($translations['add'] ?? 'إضافة'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStagiaire = document.getElementById('filterStagiaire');
    const filterDocteur = document.getElementById('filterDocteur');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const tbody = document.getElementById('consultationsTableBody');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const stagiaireValue = filterStagiaire.value;
        const docteurValue = filterDocteur.value;
        const fromValue = dateFrom.value;
        const toValue = dateTo.value;

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const stagiaire = row.getAttribute('data-stagiaire');
            const docteur = row.getAttribute('data-docteur');
            const date = new Date(row.getAttribute('data-date'));

            const matchesSearch = text.includes(searchValue);
            const matchesStagiaire = !stagiaireValue || stagiaire === stagiaireValue;
            const matchesDocteur = !docteurValue || docteur === docteurValue;
            const matchesFrom = !fromValue || date >= new Date(fromValue);
            const matchesTo = !toValue || date <= new Date(toValue);

            if (matchesSearch && matchesStagiaire && matchesDocteur && matchesFrom && matchesTo) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterStagiaire.addEventListener('change', filterTable);
    filterDocteur.addEventListener('change', filterTable);
    dateFrom.addEventListener('change', filterTable);
    dateTo.addEventListener('change', filterTable);
});
</script>

<script src="../admin/js/add_consultation_ajax.js"></script>
<script src="../admin/js/edit_consultation_ajax.js"></script>

<?php include '../templates/footer.php'; ?>
