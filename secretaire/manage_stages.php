<?php
require '../functions.php';
check_role('secretaire');

$stages = $pdo->query("SELECT * FROM stages ORDER BY id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_stage'])) {
    $intitule = sanitize_input($_POST['intitule']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $csrf = $_POST['csrf_token'];

    if (validate_csrf_token($csrf)) {
        $stmt = $pdo->prepare("INSERT INTO stages (intitule, date_debut, date_fin) VALUES (?, ?, ?)");
        $stmt->execute([$intitule, $date_debut, $date_fin]);
        header('Location: manage_stages.php');
        exit;
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['courses']); ?></h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStageModal"><?php echo htmlspecialchars($translations['add_course'] ?? 'إضافة دورة'); ?></button>
<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['id'] ?? 'ID'); ?></th>
            <th><?php echo htmlspecialchars($translations['title'] ?? 'المسمى'); ?></th>
            <th><?php echo htmlspecialchars($translations['start_date'] ?? 'تاريخ البداية'); ?></th>
            <th><?php echo htmlspecialchars($translations['end_date'] ?? 'تاريخ النهاية'); ?></th>
            <th><?php echo htmlspecialchars($translations['actions'] ?? 'إجراءات'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stages as $stage): ?>
        <tr>
            <td><?php echo $stage['id']; ?></td>
            <td><?php echo $stage['intitule']; ?></td>
            <td><?php echo $stage['date_debut']; ?></td>
            <td><?php echo $stage['date_fin']; ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStageModal<?php echo $stage['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_stage.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_stage'] ?? 'Are you sure you want to delete this stage?'); ?>');">
                    <input type="hidden" name="id" value="<?php echo $stage['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Stage Modal -->
<div class="modal fade" id="addStageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_course']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_stage" value="1">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['title']); ?></label>
                        <input type="text" name="intitule" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['start_date']); ?></label>
                        <input type="date" name="date_debut" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['end_date']); ?></label>
                        <input type="date" name="date_fin" class="form-control">
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

<!-- Edit Stage Modals -->
<?php foreach ($stages as $stage): ?>
<div class="modal fade" id="editStageModal<?php echo $stage['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="../actions/edit_stage.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_course'] ?? 'Edit Course'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $stage['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['title']); ?></label>
                        <input type="text" name="intitule" class="form-control" value="<?php echo htmlspecialchars($stage['intitule']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['start_date']); ?></label>
                        <input type="date" name="date_debut" class="form-control" value="<?php echo $stage['date_debut']; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['end_date']); ?></label>
                        <input type="date" name="date_fin" class="form-control" value="<?php echo $stage['date_fin']; ?>">
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
