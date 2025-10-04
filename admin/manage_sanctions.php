<?php
require '../functions.php';
check_role('admin');

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
?>
<?php include '../templates/header.php'; ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<h2>إدارة العقوبات</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSanctionModal">إضافة عقوبة</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المتدرب</th>
            <th>النوع</th>
            <th>الوصف</th>
            <th>تاريخ العقوبة</th>
            <th>المسؤول</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sanctions as $sanction): ?>
        <tr>
            <td><?php echo htmlspecialchars($sanction['nom'] . ' ' . $sanction['prenom']); ?></td>
            <td><?php echo htmlspecialchars($sanction['type']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($sanction['description'])); ?></td>
            <td><?php echo $sanction['date_punition']; ?></td>
            <td><?php echo htmlspecialchars($sanction['auteur_id'] ? $sanction['auteur_nom'] . ' ' . $sanction['auteur_prenom'] : ''); ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSanctionModal<?php echo $sanction['id']; ?>">تعديل</button>
                <form method="post" action="../actions/delete_sanction.php" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف العقوبة؟');">
                    <input type="hidden" name="id" value="<?php echo $sanction['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                </form>
            </td>
        </tr>

        <!-- Edit Sanction Modal -->
        <div class="modal fade" id="editSanctionModal<?php echo $sanction['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="../actions/edit_sanction.php">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل عقوبة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $sanction['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">المتدرب</label>
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
                                <label class="form-label">النوع</label>
                                <input type="text" name="type" class="form-control" value="<?php echo htmlspecialchars($sanction['type']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الوصف</label>
                                <textarea name="description" class="form-control"><?php echo htmlspecialchars($sanction['description']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاريخ العقوبة</label>
                                <input type="date" name="date_punition" class="form-control" value="<?php echo $sanction['date_punition']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">المسؤول</label>
                                <select name="auteur_id" class="form-control">
                                    <option value="">اختر</option>
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

<!-- Add Sanction Modal -->
<div class="modal fade" id="addSanctionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="../actions/add_sanction.php">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة عقوبة</h5>
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
                        <input type="text" name="type" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ العقوبة</label>
                        <input type="date" name="date_punition" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المسؤول</label>
                        <select name="auteur_id" class="form-control">
                            <option value="">اختر</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>">
                                <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
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
