<?php
require '../functions.php';
check_role('admin');

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
<h2>إدارة التخصصات</h2>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecialiteModal">إضافة تخصص</button>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>اسم التخصص</th>
            <th>الوصف</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specialites as $spec): ?>
        <tr>
            <td><?php echo $spec['id']; ?></td>
            <td><?php echo $spec['nom_specialite']; ?></td>
            <td><?php echo $spec['description']; ?></td>
            <td>
                <button class="btn btn-sm btn-warning">تعديل</button>
                <button class="btn btn-sm btn-danger">حذف</button>
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
                    <h5 class="modal-title">إضافة تخصص</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_specialite" value="1">
                    <div class="mb-3">
                        <label class="form-label">اسم التخصص</label>
                        <input type="text" name="nom_specialite" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control"></textarea>
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
