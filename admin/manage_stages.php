<?php
require '../functions.php';
check_role('admin');

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
<h2>إدارة الدورات</h2>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStageModal">إضافة دورة</button>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>المسمى</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>إجراءات</th>
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
                <button class="btn btn-sm btn-warning">تعديل</button>
                <button class="btn btn-sm btn-danger">حذف</button>
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
                    <h5 class="modal-title">إضافة دورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_stage" value="1">
                    <div class="mb-3">
                        <label class="form-label">المسمى</label>
                        <input type="text" name="intitule" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ البداية</label>
                        <input type="date" name="date_debut" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ النهاية</label>
                        <input type="date" name="date_fin" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
