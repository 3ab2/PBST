<?php
require '../functions.php';
check_role('secretaire');

$specialites = $pdo->query("SELECT * FROM specialites ORDER BY id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_specialite'])) {
    $nom_specialite = sanitize_input($_POST['nom_specialite']);
    $description = sanitize_input($_POST['description']);
    $csrf = $_POST['csrf_token'];

    if (validate_csrf_token($csrf)) {
        $stmt = $pdo->prepare("INSERT INTO specialites (nom_specialite, description) VALUES (?, ?)");
        $stmt->execute([$nom_specialite, $description]);
        header('Location: manage_specialites.php');
        exit;
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['specialities']); ?></h2>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecialiteModal"><?php echo htmlspecialchars($translations['add_specialty']); ?></button>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['id']); ?></th>
            <th><?php echo htmlspecialchars($translations['specialty_name']); ?></th>
            <th><?php echo htmlspecialchars($translations['description']); ?></th>
            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specialites as $spec): ?>
        <tr>
            <td><?php echo $spec['id']; ?></td>
            <td><?php echo $spec['nom_specialite']; ?></td>
            <td><?php echo $spec['description']; ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSpecialiteModal<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($translations['edit']); ?></button>
                <form method="post" action="../actions/delete_specialite.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_specialite'] ?? 'Are you sure you want to delete this specialite?'); ?>');">
                    <input type="hidden" name="id" value="<?php echo $spec['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><?php echo htmlspecialchars($translations['delete']); ?></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Specialite Modal -->
<div class="modal fade" id="addSpecialiteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_specialty']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_specialite" value="1">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['specialty_name']); ?></label>
                        <input type="text" name="nom_specialite" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['description']); ?></label>
                        <textarea name="description" class="form-control"></textarea>
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

<!-- Edit Specialite Modals -->
<?php foreach ($specialites as $spec): ?>
<div class="modal fade" id="editSpecialiteModal<?php echo $spec['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="../actions/edit_specialite.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_specialty'] ?? 'Edit Specialty'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $spec['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['specialty_name']); ?></label>
                        <input type="text" name="nom_specialite" class="form-control" value="<?php echo htmlspecialchars($spec['nom_specialite']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['description']); ?></label>
                        <textarea name="description" class="form-control"><?php echo htmlspecialchars($spec['description']); ?></textarea>
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

<?php include '../templates/footer.php'; ?>
