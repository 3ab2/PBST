<?php
require '../functions.php';
check_role('admin');

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
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
    } elseif (isset($_POST['edit_user'])) {
        $id = $_POST['id'];
        $username = sanitize_input($_POST['username']);
        $password = $_POST['password'];
        $role = $_POST['role'];
        $nom = sanitize_input($_POST['nom']);
        $prenom = sanitize_input($_POST['prenom']);
        $email = sanitize_input($_POST['email']);
        $csrf = $_POST['csrf_token'];

        if (validate_csrf_token($csrf)) {
            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ?, nom = ?, prenom = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $password, $role, $nom, $prenom, $email, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, nom = ?, prenom = ?, email = ? WHERE id = ?");
                $stmt->execute([$username, $role, $nom, $prenom, $email, $id]);
            }
            header('Location: manage_users.php');
            exit;
        }
    }
}

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2><?php echo htmlspecialchars($translations['manage_users']); ?></h2>
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><?php echo htmlspecialchars($translations['add_user']); ?></button>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['id']); ?></th>
            <th><?php echo htmlspecialchars($translations['username']); ?></th>
            <th><?php echo htmlspecialchars($translations['role']); ?></th>
            <th><?php echo htmlspecialchars($translations['name']); ?></th>
            <th><?php echo htmlspecialchars($translations['email']); ?></th>
            <th><?php echo htmlspecialchars($translations['actions']); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo htmlspecialchars($translations[$user['role']]); ?></td>
            <td><?php echo $user['nom'] . ' ' . $user['prenom']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>"><i class="fas fa-edit"></i></button>
                <form method="post" action="../actions/delete_user.php" style="display:inline-block;" onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_user'] ?? 'Are you sure you want to delete this user?'); ?>');">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Edit User Modals -->
<?php foreach ($users as $user): ?>
<div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_user']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="edit_user" value="1">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['username']); ?></label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['password']); ?> (<?php echo htmlspecialchars($translations['leave_blank'] ?? 'leave blank to keep current'); ?>)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['role']); ?></label>
                        <select name="role" class="form-control" required>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>><?php echo htmlspecialchars($translations['admin']); ?></option>
                            <option value="secretaire" <?php echo $user['role'] == 'secretaire' ? 'selected' : ''; ?>><?php echo htmlspecialchars($translations['secretaire']); ?></option>
                            <option value="docteur" <?php echo $user['role'] == 'docteur' ? 'selected' : ''; ?>><?php echo htmlspecialchars($translations['docteur']); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                        <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($user['nom']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                        <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($user['prenom']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_user']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <input type="hidden" name="add_user" value="1">
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['username']); ?></label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['password']); ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['role']); ?></label>
                        <select name="role" class="form-control" required>
                            <option value="admin"><?php echo htmlspecialchars($translations['admin']); ?></option>
                            <option value="secretaire"><?php echo htmlspecialchars($translations['secretaire']); ?></option>
                            <option value="docteur"><?php echo htmlspecialchars($translations['docteur']); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                        <input type="text" name="nom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                        <input type="text" name="prenom" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                        <input type="email" name="email" class="form-control">
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
<?php include '../templates/footer.php'; ?>
