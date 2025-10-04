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
<?php include '../templates/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<h2>إدارة الأذونات</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPermissionModal">إضافة إذن</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المتدرب</th>
            <th>النوع</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>الموضوع</th>
            <th>الحالة</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $perm): ?>
        <tr>
            <td><?php echo htmlspecialchars($perm['nom'] . ' ' . $perm['prenom']); ?></td>
            <td><?php echo htmlspecialchars($perm['type']); ?></td>
            <td><?php echo $perm['date_debut']; ?></td>
            <td><?php echo $perm['date_fin']; ?></td>
            <td><?php echo htmlspecialchars($perm['motif']); ?></td>
            <td><?php echo htmlspecialchars($perm['statut']); ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPermissionModal<?php echo $perm['id']; ?>">تعديل</button>
                <form method="post" action="../actions/delete_permission.php" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف الإذن؟');">
                    <input type="hidden" name="id" value="<?php echo $perm['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                </form>
            </td>
        </tr>

        <!-- Edit Permission Modal -->
        <div class="modal fade" id="editPermissionModal<?php echo $perm['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="../actions/edit_permission.php">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل إذن</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $perm['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">المتدرب</label>
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
                                <label class="form-label">النوع</label>
                                <select name="type" class="form-control" required>
                                    <?php
                                    $types = ['samedi','dimanche','exceptionnelle','vacance'];
                                    foreach ($types as $type) {
                                        $selected = ($perm['type'] == $type) ? 'selected' : '';
                                        echo "<option value=\"$type\" $selected>$type</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاريخ البداية</label>
                                <input type="date" name="date_debut" class="form-control" value="<?php echo $perm['date_debut']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاريخ النهاية</label>
                                <input type="date" name="date_fin" class="form-control" value="<?php echo $perm['date_fin']; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الموضوع</label>
                                <textarea name="motif" class="form-control"><?php echo htmlspecialchars($perm['motif']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الحالة</label>
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
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
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
            <form method="post" action="../actions/add_permission.php">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة إذن</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label">المتدرب</label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value="">اختر</option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                            <option value="<?php echo $stagiaire['id']; ?>">
                                <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">النوع</label>
                        <select name="type" class="form-control" required>
                            <option value="">اختر</option>
                            <option value="samedi">samedi</option>
                            <option value="dimanche">dimanche</option>
                            <option value="exceptionnelle">exceptionnelle</option>
                            <option value="vacance">vacance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ البداية</label>
                        <input type="date" name="date_debut" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ النهاية</label>
                        <input type="date" name="date_fin" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الموضوع</label>
                        <textarea name="motif" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select name="statut" class="form-control" required>
                            <option value="en_attente">en_attente</option>
                            <option value="acceptee">acceptee</option>
                            <option value="refusee">refusee</option>
                        </select>
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
