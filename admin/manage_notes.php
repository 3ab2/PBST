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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<h2>إدارة الملاحظات</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addNoteModal">إضافة ملاحظة</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المتدرب</th>
            <th>الملاحظة</th>
            <th>التاريخ</th>
            <th>المؤلف</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($notes as $note): ?>
        <tr>
            <td><?php echo htmlspecialchars($note['nom'] . ' ' . $note['prenom']); ?></td>
            <td><?php echo nl2br(htmlspecialchars($note['remarque'])); ?></td>
            <td><?php echo $note['date_remarque']; ?></td>
            <td><?php echo htmlspecialchars($note['auteur_id'] ? $note['auteur_nom'] . ' ' . $note['auteur_prenom'] : ''); ?></td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editNoteModal<?php echo $note['id']; ?>">تعديل</button>
                <form method="post" action="../actions/delete_note.php" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف الملاحظة؟');">
                    <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                </form>
            </td>
        </tr>

        <!-- Edit Note Modal -->
        <div class="modal fade" id="editNoteModal<?php echo $note['id']; ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="../actions/edit_note.php">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل ملاحظة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">المتدرب</label>
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
                                <label class="form-label">الملاحظة</label>
                                <textarea name="remarque" class="form-control"><?php echo htmlspecialchars($note['remarque']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التاريخ</label>
                                <input type="date" name="date_remarque" class="form-control" value="<?php echo $note['date_remarque']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">المؤلف</label>
                                <select name="auteur_id" class="form-control">
                                    <option value="">اختر</option>
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

<!-- Add Note Modal -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="../actions/add_note.php">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة ملاحظة</h5>
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
                        <label class="form-label">الملاحظة</label>
                        <textarea name="remarque" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date_remarque" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المؤلف</label>
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
