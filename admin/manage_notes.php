<?php
require '../functions.php';
check_role('admin');

$csrf_token = generate_csrf_token();

$notes = $pdo->query("
    SELECT r.*, s.nom, s.prenom, u.nom AS auteur_nom, u.prenom AS auteur_prenom
    FROM remarques r
    JOIN stagiaires s ON r.id_stagiaire = s.id
    LEFT JOIN users u ON r.auteur_id = u.id
    ORDER BY r.date_remarque DESC
")->fetchAll();

$stagiaires = $pdo->query("SELECT id, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();
$users = $pdo->query("SELECT id, nom, prenom FROM users ORDER BY nom")->fetchAll();
?>
<?php include '../templates/header.php'; ?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<h2><?php echo htmlspecialchars($translations['notes']); ?></h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNoteModal"><?php echo htmlspecialchars($translations['add_note']); ?></button>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control" placeholder="<?php echo htmlspecialchars($translations['search']); ?>">
    </div>
    <div class="col">
        <select id="filterAuteur" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_authors']); ?></option>
            <?php
            foreach ($users as $user) {
                echo "<option value=\"" . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . "\">" . htmlspecialchars($user['nom'] . ' ' . $user['prenom']) . "</option>";
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
            <th><?php echo htmlspecialchars($translations['note']); ?></th>
            <th><?php echo htmlspecialchars($translations['date']); ?></th>
            <th><?php echo htmlspecialchars($translations['author']); ?></th>
            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
        </tr>
    </thead>
    <tbody id="notesTableBody">
        <?php foreach ($notes as $note): ?>
        <tr data-stagiaire="<?php echo htmlspecialchars($note['nom'] . ' ' . $note['prenom']); ?>" data-auteur="<?php echo htmlspecialchars($note['auteur_id'] ? $note['auteur_nom'] . ' ' . $note['auteur_prenom'] : ''); ?>" data-date="<?php echo $note['date_remarque']; ?>">
            <td><?php echo htmlspecialchars($note['nom'] . ' ' . $note['prenom']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($note['remarque'])); ?></td>
            <td><?php echo $note['date_remarque']; ?></td>
            <td><?php echo htmlspecialchars($note['auteur_id'] ? $note['auteur_nom'] . ' ' . $note['auteur_prenom'] : ''); ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editNoteModal<?php echo $note['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_note.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_note']); ?>');">
                    <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>

        <!-- Edit Note Modal -->
        <div class="modal fade" id="editNoteModal<?php echo $note['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="edit-note-form" method="post" action="../actions/edit_note.php">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_note']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['trainee']); ?></label>
                                <select name="id_stagiaire" class="form-control" required>
                                    <?php foreach ($stagiaires as $stagiaire):
                                        $selected = ($note['id_stagiaire'] == $stagiaire['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $stagiaire['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['note']); ?></label>
                                <textarea name="remarque" class="form-control"><?php echo htmlspecialchars($note['remarque']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['date']); ?></label>
                                <input type="date" name="date_remarque" class="form-control" value="<?php echo $note['date_remarque']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><?php echo htmlspecialchars($translations['author']); ?></label>
                                <select name="auteur_id" class="form-control">
                                    <option value=""><?php echo htmlspecialchars($translations['select']); ?></option>
                                    <?php foreach ($users as $user):
                                        $selected = ($note['auteur_id'] == $user['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $user['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
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

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addNoteForm" method="post" action="../actions/add_note.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_note']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['trainee']); ?></label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value="" style="display:none;">
                                <?php echo htmlspecialchars($translations['search']); ?>
                            </option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                            <option value="<?php echo $stagiaire['id']; ?>">
                                <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['note']); ?></label>
                        <textarea name="remarque" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['date']); ?></label>
                        <input type="date" name="date_remarque" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['author']); ?></label>
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
    const filterAuteur = document.getElementById('filterAuteur');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const tbody = document.getElementById('notesTableBody');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const auteurValue = filterAuteur.value;
        const fromValue = dateFrom.value;
        const toValue = dateTo.value;

        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const auteur = row.getAttribute('data-auteur');
            const date = new Date(row.getAttribute('data-date'));

            const matchesSearch = text.includes(searchValue);
            const matchesAuteur = !auteurValue || auteur === auteurValue;
            const matchesFrom = !fromValue || date >= new Date(fromValue);
            const matchesTo = !toValue || date <= new Date(toValue);

            if (matchesSearch && matchesAuteur && matchesFrom && matchesTo) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterAuteur.addEventListener('change', filterTable);
    dateFrom.addEventListener('change', filterTable);
    dateTo.addEventListener('change', filterTable);
});
</script>
<script src="js/edit_note_ajax.js"></script>
<script src="js/add_note_ajax.js"></script>

<?php include '../templates/footer.php'; ?>
