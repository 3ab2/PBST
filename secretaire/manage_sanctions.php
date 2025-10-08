<?php
require '../functions.php';
check_role('secretaire');

$csrf_token = generate_csrf_token();

$sanctions = $pdo->query("
    SELECT pu.*, s.nom, s.prenom, u.nom AS auteur_nom, u.prenom AS auteur_prenom
    FROM punitions pu
    JOIN stagiaires s ON pu.id_stagiaire = s.id
    LEFT JOIN users u ON pu.auteur_id = u.id
    ORDER BY pu.date_punition DESC
")->fetchAll();

$stagiaires = $pdo->query("SELECT id, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();
$users = $pdo->query("SELECT id, nom, prenom FROM users ORDER BY nom")->fetchAll();
$page_title = htmlspecialchars($translations['sanctions']) ;
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_secretaire']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_sanction']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_liste_sanctions']); ?></div>
<button class="btn btn-primary mb-3" data-bs-toggle="modal"
    data-bs-target="#addSanctionModal"><?php echo htmlspecialchars($translations['add_sanction'] ?? 'إضافة عقوبة'); ?></button>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control"
            placeholder="<?php echo htmlspecialchars($translations['search'] ?? 'ابحث...'); ?>">
    </div>
    <div class="col">
        <input type="text" id="filterType" class="form-control"
            placeholder="<?php echo htmlspecialchars($translations['sanction_type'] ?? 'نوع العقوبة'); ?>">
    </div>
    <div class="col">
        <select id="filterAuteur" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_responsibles'] ?? 'كل المسؤولين'); ?>
            </option>
            <?php
            foreach ($users as $user) {
                echo "<option value=\"" . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . "\">" . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <input type="date" id="dateFrom" class="form-control"
            placeholder="<?php echo htmlspecialchars($translations['from_date'] ?? 'من تاريخ'); ?>">
    </div>
    <div class="col">
        <input type="date" id="dateTo" class="form-control"
            placeholder="<?php echo htmlspecialchars($translations['to_date'] ?? 'إلى تاريخ'); ?>">
    </div>
</div>

<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['trainee'] ?? 'المتدرب'); ?></th>
            <th><?php echo htmlspecialchars($translations['type'] ?? 'النوع'); ?></th>
            <th><?php echo htmlspecialchars($translations['description'] ?? 'الوصف'); ?></th>
            <th><?php echo htmlspecialchars($translations['sanction_date'] ?? 'تاريخ العقوبة'); ?></th>
            <th><?php echo htmlspecialchars($translations['responsible'] ?? 'المسؤول'); ?></th>
            <th><?php echo htmlspecialchars($translations['actions'] ?? 'إجراءات'); ?></th>
        </tr>
    </thead>
    <tbody id="sanctionsTableBody">
        <?php foreach ($sanctions as $sanction): ?>
            <tr data-stagiaire="<?php echo htmlspecialchars($sanction['nom'] . ' ' . $sanction['prenom']); ?>"
                data-type="<?php echo htmlspecialchars($sanction['type']); ?>"
                data-auteur="<?php echo htmlspecialchars($sanction['auteur_id'] ? $sanction['auteur_nom'] . ' ' . $sanction['auteur_prenom'] : ''); ?>"
                data-date="<?php echo $sanction['date_punition']; ?>">
                <td><?php echo htmlspecialchars($sanction['nom'] . ' ' . $sanction['prenom']); ?></td>
                <td><?php echo htmlspecialchars($sanction['type']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($sanction['description'])); ?></td>
                <td><?php echo $sanction['date_punition']; ?></td>
                <td><?php echo htmlspecialchars($sanction['auteur_id'] ? $sanction['auteur_nom'] . ' ' . $sanction['auteur_prenom'] : ''); ?>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#editSanctionModal<?php echo $sanction['id']; ?>"><i class="fas fa-edit"></i></button>
                    <form method="post" action="../actions/delete_sanction.php" style="display:inline-block;"
                        onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_sanction']); ?>');">
                        <input type="hidden" name="id" value="<?php echo $sanction['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <button type="submit"
                            class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>

            <!-- Edit Sanction Modal -->
            <div class="modal fade" id="editSanctionModal<?php echo $sanction['id']; ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="edit-sanction-form" method="post" action="../actions/edit_sanction.php">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_sanction']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $sanction['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <div class="mb-3">
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['trainee']); ?></label>
                                    <select name="id_stagiaire" class="form-control" required>
                                        <?php foreach ($stagiaires as $stagiaire):
                                            $selected = ($sanction['id_stagiaire'] == $stagiaire['id']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $stagiaire['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><?php echo htmlspecialchars($translations['type']); ?></label>
                                    <select name="type" class="form-control" required>
                                        <option value="samedi & dimanche" <?php echo ($sanction['type'] == 'samedi & dimanche') ? 'selected' : ''; ?>>samedi & dimanche</option>
                                        <option value="piquet" <?php echo ($sanction['type'] == 'piquet') ? 'selected' : ''; ?>>piquet</option>
                                        <option value="permanence" <?php echo ($sanction['type'] == 'permanence') ? 'selected' : ''; ?>>permanence</option>
                                        <option value="chef de poste" <?php echo ($sanction['type'] == 'chef de poste') ? 'selected' : ''; ?>>chef de poste</option>
                                        <option value="Garde" <?php echo ($sanction['type'] == 'Garde') ? 'selected' : ''; ?>>
                                            Garde</option>
                                        <option value="Corvet" <?php echo ($sanction['type'] == 'Corvet') ? 'selected' : ''; ?>>Corvet</option>
                                        <option value="LD 4 Jrs" <?php echo ($sanction['type'] == 'LD 4 Jrs') ? 'selected' : ''; ?>>LD 4 Jrs</option>
                                        <option value="LD 8 Jrs" <?php echo ($sanction['type'] == 'LD 8 Jrs') ? 'selected' : ''; ?>>LD 8 Jrs</option>
                                        <option value="LD 10 Jrs" <?php echo ($sanction['type'] == 'LD 10 Jrs') ? 'selected' : ''; ?>>LD 10 Jrs</option>
                                        <option value="LD 15 Jrs" <?php echo ($sanction['type'] == 'LD 15 Jrs') ? 'selected' : ''; ?>>LD 15 Jrs</option>
                                        <option value="LD 25 Jrs" <?php echo ($sanction['type'] == 'LD 25 Jrs') ? 'selected' : ''; ?>>LD 25 Jrs</option>
                                        <option value="LD 30 Jrs" <?php echo ($sanction['type'] == 'LD 30 Jrs') ? 'selected' : ''; ?>>LD 30 Jrs</option>
                                        <option value="LD 40 Jrs" <?php echo ($sanction['type'] == 'LD 40 Jrs') ? 'selected' : ''; ?>>LD 40 Jrs</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['description']); ?></label>
                                    <textarea name="description"
                                        class="form-control"><?php echo htmlspecialchars($sanction['description']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['sanction_date']); ?></label>
                                    <input type="date" name="date_punition" class="form-control"
                                        value="<?php echo $sanction['date_punition']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['responsible']); ?></label>
                                    <select name="auteur_id" class="form-control">
                                        <option value=""><?php echo htmlspecialchars($translations['select']); ?></option>
                                        <?php foreach ($users as $user):
                                            $selected = ($sanction['auteur_id'] == $user['id']) ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo $user['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                                <button type="submit"
                                    class="btn btn-primary"><?php echo htmlspecialchars($translations['save_changes']); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Sanction Modal -->
<div class="modal fade" id="addSanctionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSanctionForm" method="post" action="../actions/add_sanction.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_sanction']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['trainee']); ?></label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['select']); ?></option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                                <option value="<?php echo $stagiaire['id']; ?>">
                                    <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['type']); ?></label>
                        <select name="type" class="form-control" required>
                            <option value="samedi & dimanche" <?php echo ($sanction['type'] == 'samedi & dimanche') ? 'selected' : ''; ?>>samedi & dimanche</option>
                            <option value="piquet" <?php echo ($sanction['type'] == 'piquet') ? 'selected' : ''; ?>>piquet
                            </option>
                            <option value="permanence" <?php echo ($sanction['type'] == 'permanence') ? 'selected' : ''; ?>>permanence</option>
                            <option value="chef de poste" <?php echo ($sanction['type'] == 'chef de poste') ? 'selected' : ''; ?>>chef de poste</option>
                            <option value="Garde" <?php echo ($sanction['type'] == 'Garde') ? 'selected' : ''; ?>>
                                Garde</option>
                            <option value="Corvet" <?php echo ($sanction['type'] == 'Corvet') ? 'selected' : ''; ?>>Corvet
                            </option>
                            <option value="LD 4 Jrs" <?php echo ($sanction['type'] == 'LD 4 Jrs') ? 'selected' : ''; ?>>LD
                                4 Jrs</option>
                            <option value="LD 8 Jrs" <?php echo ($sanction['type'] == 'LD 8 Jrs') ? 'selected' : ''; ?>>LD
                                8 Jrs</option>
                            <option value="LD 10 Jrs" <?php echo ($sanction['type'] == 'LD 10 Jrs') ? 'selected' : ''; ?>>
                                LD 10 Jrs</option>
                            <option value="LD 15 Jrs" <?php echo ($sanction['type'] == 'LD 15 Jrs') ? 'selected' : ''; ?>>
                                LD 15 Jrs</option>
                            <option value="LD 25 Jrs" <?php echo ($sanction['type'] == 'LD 25 Jrs') ? 'selected' : ''; ?>>
                                LD 25 Jrs</option>
                            <option value="LD 30 Jrs" <?php echo ($sanction['type'] == 'LD 30 Jrs') ? 'selected' : ''; ?>>
                                LD 30 Jrs</option>
                            <option value="LD 40 Jrs" <?php echo ($sanction['type'] == 'LD 40 Jrs') ? 'selected' : ''; ?>>
                                LD 40 Jrs</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['description']); ?></label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label
                            class="form-label"><?php echo htmlspecialchars($translations['sanction_date']); ?></label>
                        <input type="date" name="date_punition" class="form-control"
                            value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['responsible']); ?></label>
                        <select name="auteur_id" class="form-control">
                            <option value=""><?php echo htmlspecialchars($translations['select']); ?></option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                    <button type="submit"
                        class="btn btn-primary"><?php echo htmlspecialchars($translations['add']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterType = document.getElementById('filterType');
        const filterAuteur = document.getElementById('filterAuteur');
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        const tbody = document.getElementById('sanctionsTableBody');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const typeValue = filterType.value.toLowerCase();
            const auteurValue = filterAuteur.value;
            const fromValue = dateFrom.value;
            const toValue = dateTo.value;

            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const type = row.getAttribute('data-type').toLowerCase();
                const auteur = row.getAttribute('data-auteur');
                const date = new Date(row.getAttribute('data-date'));

                const matchesSearch = text.includes(searchValue);
                const matchesType = !typeValue || type.includes(typeValue);
                const matchesAuteur = !auteurValue || auteur === auteurValue;
                const matchesFrom = !fromValue || date >= new Date(fromValue);
                const matchesTo = !toValue || date <= new Date(toValue);

                if (matchesSearch && matchesType && matchesAuteur && matchesFrom && matchesTo) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterType.addEventListener('input', filterTable);
        filterAuteur.addEventListener('change', filterTable);
        dateFrom.addEventListener('change', filterTable);
        dateTo.addEventListener('change', filterTable);
    });
</script>
<script src="js/edit_sanction_ajax.js"></script>
<script src="js/add_sanction_ajax.js"></script>

<?php include '../templates/footer.php'; ?>