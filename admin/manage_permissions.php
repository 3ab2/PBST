<?php
require '../functions.php';
check_role('admin');

$csrf_token = generate_csrf_token();

$permissions = $pdo->query("
    SELECT p.*, s.nom, s.prenom 
    FROM permissions p 
    JOIN stagiaires s ON p.id_stagiaire = s.id
    ORDER BY p.date_debut DESC
")->fetchAll();

$stagiaires = $pdo->query("SELECT id, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['permissions']); ?></h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPermissionModal"><?php echo htmlspecialchars($translations['add_permission']); ?></button>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control" placeholder="<?php echo htmlspecialchars($translations['search']); ?>">
    </div>
    <div class="col">
        <select id="filterType" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_types']); ?></option>
            <option value="samedi & dimanche">samedi & dimanche</option>
            <option value="exceptionnelle">exceptionnelle</option>
            <option value="vacance">vacance</option>
        </select>
    </div>
    <div class="col">
        <select id="filterStatut" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_statuses']); ?></option>
            <option value="en_attente">en_attente</option>
            <option value="acceptee">acceptee</option>
            <option value="refusee">refusee</option>
        </select>
    </div>
    <div class="col">
        <input type="date" id="dateFrom" class="form-control" placeholder="<?php echo htmlspecialchars($translations['from_start_date']); ?>">
    </div>
    <div class="col">
        <input type="date" id="dateTo" class="form-control" placeholder="<?php echo htmlspecialchars($translations['to_start_date']); ?>">
    </div>
</div>

<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['trainee']); ?></th>
            <th><?php echo htmlspecialchars($translations['type']); ?></th>
            <th><?php echo htmlspecialchars($translations['start_date']); ?></th>
            <th><?php echo htmlspecialchars($translations['end_date']); ?></th>
            <th><?php echo htmlspecialchars($translations['subject']); ?></th>
            <th><?php echo htmlspecialchars($translations['status']); ?></th>
            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
        </tr>
    </thead>
    <tbody id="permissionsTableBody">
        <?php foreach ($permissions as $perm): ?>
        <tr data-stagiaire="<?php echo htmlspecialchars($perm['nom'] . ' ' . $perm['prenom']); ?>" data-type="<?php echo htmlspecialchars($perm['type']); ?>" data-statut="<?php echo htmlspecialchars($perm['statut']); ?>" data-date="<?php echo $perm['date_debut']; ?>">
            <td><?php echo htmlspecialchars($perm['nom'] . ' ' . $perm['prenom']); ?></td>
            <td><?php echo htmlspecialchars($perm['type']); ?></td>
            <td><?php echo $perm['date_debut']; ?></td>
            <td><?php echo $perm['date_fin']; ?></td>
            <td><?php echo htmlspecialchars($perm['motif']); ?></td>
            <td><?php echo htmlspecialchars($perm['statut']); ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPermissionModal<?php echo $perm['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_permission.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_permission']); ?>');">
                    <input type="hidden" name="id" value="<?php echo $perm['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>

        <!-- Edit Permission Modal -->
        <div class="modal fade" id="editPermissionModal<?php echo $perm['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="edit-permission-form" method="post" action="../actions/edit_permission.php">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_permission']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $perm['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['trainee']); ?></label>
                                <select name="id_stagiaire" class="form-control" required>
                                    <?php foreach ($stagiaires as $stagiaire):
                                        $selected = ($perm['id_stagiaire'] == $stagiaire['id']) ? 'selected' : '';
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
                                    <?php
                                    $types = ['samedi & dimanche','exceptionnelle','vacance'];
                                    foreach ($types as $type) {
                                        $selected = ($perm['type'] == $type) ? 'selected' : '';
                                        echo "<option value=\"$type\" $selected>$type</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['start_date']); ?></label>
                                <input type="date" name="date_debut" class="form-control" value="<?php echo $perm['date_debut']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['end_date']); ?></label>
                                <input type="date" name="date_fin" class="form-control" value="<?php echo $perm['date_fin']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['subject']); ?></label>
                                <textarea name="motif" class="form-control"><?php echo htmlspecialchars($perm['motif']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['status']); ?></label>
                                <select name="statut" class="form-control" required>
                                    <?php
                                    $statuses = ['acceptee','refusee','en_attente'];
                                    foreach ($statuses as $status) {
                                        $selected = ($perm['statut'] == $status) ? 'selected' : '';
                                        echo "<option value=\"$status\" $selected>$status</option>";
                                    }
                                    ?>
                                </select>
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

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addPermissionForm" method="post" action="../actions/add_permission.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_permission']); ?></h5>
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
                            <option value=""><?php echo htmlspecialchars($translations['select']); ?></option>
                            <option value="samedi & dimanche">samedi & dimanche</option>
                            <option value="exceptionnelle">exceptionnelle</option>
                            <option value="vacance">vacance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['start_date']); ?></label>
                        <input type="date" name="date_debut" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['end_date']); ?></label>
                        <input type="date" name="date_fin" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['subject']); ?></label>
                        <textarea name="motif" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['status']); ?></label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">en_attente</option>
                            <option value="acceptee">acceptee</option>
                            <option value="refusee">refusee</option>
                        </select>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    const filterStatut = document.getElementById('filterStatut');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const tbody = document.getElementById('permissionsTableBody');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const typeValue = filterType.value;
        const statutValue = filterStatut.value;
        const fromValue = dateFrom.value;
        const toValue = dateTo.value;

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const type = row.getAttribute('data-type');
            const statut = row.getAttribute('data-statut');
            const date = new Date(row.getAttribute('data-date'));

            const matchesSearch = text.includes(searchValue);
            const matchesType = !typeValue || type === typeValue;
            const matchesStatut = !statutValue || statut === statutValue;
            const matchesFrom = !fromValue || date >= new Date(fromValue);
            const matchesTo = !toValue || date <= new Date(toValue);

            if (matchesSearch && matchesType && matchesStatut && matchesFrom && matchesTo) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterType.addEventListener('change', filterTable);
    filterStatut.addEventListener('change', filterTable);
    dateFrom.addEventListener('change', filterTable);
    dateTo.addEventListener('change', filterTable);
});
</script>
<script src="js/edit_permission_ajax.js"></script>
<script src="js/add_permission_ajax.js"></script>

<?php include '../templates/footer.php'; ?>
