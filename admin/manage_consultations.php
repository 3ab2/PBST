<?php
require '../functions.php';
check_role('admin');

// Fetch consultations with stagiaire and docteur names
$sql = "SELECT c.*, s.nom AS stagiaire_nom, s.prenom AS stagiaire_prenom, u.nom AS docteur_nom, u.prenom AS docteur_prenom
        FROM consultations c
        JOIN stagiaires s ON c.id_stagiaire = s.id
        JOIN users u ON c.id_docteur = u.id
        ORDER BY c.date_consultation DESC";
$consultations = $pdo->query($sql)->fetchAll();

$stagiaires = $pdo->query("SELECT id, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();
$docteurs = $pdo->query("SELECT id, nom, prenom FROM users WHERE role = 'docteur' ORDER BY nom")->fetchAll();

$csrf_token = generate_csrf_token();
?>
<?php include '../templates/header.php'; ?>
<h2>إدارة الاستشارات</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addConsultationModal">إضافة استشارة</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المعرف</th>
            <th>المتدرب</th>
            <th>الطبيب</th>
            <th>تاريخ الاستشارة</th>
            <th>التشخيص</th>
            <th>العلاج</th>
            <th>ملاحظات</th>
            <th>ملف الاستشارة</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($consultations as $consultation): ?>
        <tr>
            <td><?php echo $consultation['id']; ?></td>
            <td><?php echo htmlspecialchars($consultation['stagiaire_nom'] . ' ' . $consultation['stagiaire_prenom']); ?></td>
            <td><?php echo htmlspecialchars($consultation['docteur_nom'] . ' ' . $consultation['docteur_prenom']); ?></td>
            <td><?php echo $consultation['date_consultation']; ?></td>
            <td><?php echo nl2br(htmlspecialchars($consultation['diagnostic'])); ?></td>
            <td><?php echo nl2br(htmlspecialchars($consultation['traitement'])); ?></td>
            <td><?php echo nl2br(htmlspecialchars($consultation['remarques'])); ?></td>
            <td>
                <?php if ($consultation['file'] != null): ?>
                <a href="../files/<?php echo $consultation['file']; ?>" class="btn btn-sm btn-primary">تحميل الملف</a>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editConsultationModal<?php echo $consultation['id']; ?>">تعديل</button>
                <form method="post" action="../actions/delete_consultation.php" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف الاستشارة؟');">
                    <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editConsultationModal<?php echo $consultation['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="../actions/edit_consultation.php">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل استشارة</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $consultation['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">المتدرب</label>
                                <select name="id_stagiaire" class="form-control" required>
                                    <?php foreach ($stagiaires as $stagiaire): 
                                        $selected = ($consultation['id_stagiaire'] == $stagiaire['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $stagiaire['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الطبيب</label>
                                <select name="id_docteur" class="form-control" required>
                                    <?php foreach ($docteurs as $docteur): 
                                        $selected = ($consultation['id_docteur'] == $docteur['id']) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $docteur['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاريخ الاستشارة</label>
                                <input type="date" name="date_consultation" class="form-control" value="<?php echo $consultation['date_consultation']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التشخيص</label>
                                <textarea name="diagnostic" class="form-control"><?php echo htmlspecialchars($consultation['diagnostic']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">العلاج</label>
                                <textarea name="traitement" class="form-control"><?php echo htmlspecialchars($consultation['traitement']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="remarques" class="form-control"><?php echo htmlspecialchars($consultation['remarques']); ?></textarea>
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

<!-- Add Consultation Modal -->
<div class="modal fade" id="addConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="../actions/add_consultation.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة استشارة</h5>
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
                        <label class="form-label">الطبيب</label>
                        <select name="id_docteur" class="form-control" required>
                            <option value="">اختر</option>
                            <?php foreach ($docteurs as $docteur): ?>
                            <option value="<?php echo $docteur['id']; ?>">
                                <?php echo htmlspecialchars($docteur['nom'] . ' ' . $docteur['prenom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الاستشارة</label>
                        <input type="date" name="date_consultation" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التشخيص</label>
                        <textarea name="diagnostic" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العلاج</label>
                        <textarea name="traitement" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="remarques" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Consultation File</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
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
