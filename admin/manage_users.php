<?php
require '../functions.php';
check_role('admin');

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = sanitize_input($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $nom = sanitize_input($_POST['nom']);
    $prenom = sanitize_input($_POST['prenom']);
    $email = sanitize_input($_POST['email']);
    $csrf = $_POST['csrf_token'];

    if (validate_csrf_token($csrf)) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, nom, prenom, email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $role, $nom, $prenom, $email]);
        header('Location: manage_users.php');
        exit;
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>إدارة المستخدمين</h2>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">إضافة مستخدم</button>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>اسم المستخدم</th>
            <th>الدور</th>
            <th>الاسم</th>
            <th>البريد الإلكتروني</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td><?php echo $user['nom'] . ' ' . $user['prenom']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
                <button class="btn btn-sm btn-warning">تعديل</button>
                <button class="btn btn-sm btn-danger">حذف</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة مستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_user" value="1">
                    <div class="mb-3">
                        <label class="form-label">اسم المستخدم</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">مدير</option>
                            <option value="secretaire">سكرتير</option>
                            <option value="docteur">طبيب</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="nom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اللقب</label>
                        <input type="text" name="prenom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control">
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
