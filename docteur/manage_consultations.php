<?php
require '../functions.php';
check_role('docteur');

// Fetch consultations for this doctor
$sql = "SELECT c.*, s.nom, s.prenom, s.matricule 
        FROM consultations c
        JOIN stagiaires s ON c.id_stagiaire = s.id
        WHERE c.id_docteur = ?
        ORDER BY c.date_consultation DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['user_id']]);
$consultations = $stmt->fetchAll();

// Fetch stagiaires for dropdown
$stagiaires = $pdo->query("SELECT id, matricule, nom, prenom FROM stagiaires ORDER BY nom")->fetchAll();

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>إدارة الاستشارات الطبية</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addConsultationModal">إضافة استشارة</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المتدرب</th>
            <th>تاريخ الاستشارة</th>
            <th>التشخيص</th>
            <th>العلاج</th>
            <th>الملاحظات</th>
            <th>ملف الاستشارة</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($consultations as $cons): ?>
        <tr>
            <td><?php echo htmlspecialchars($cons['matricule'] . ' - ' . $cons['nom'] . ' ' . $cons['prenom']); ?></td>
            <td><?php echo $cons['date_consultation']; ?></td>
            <td><?php echo htmlspecialchars($cons['diagnostic']); ?></td>
            <td><?php echo htmlspecialchars($cons['traitement']); ?></td>
            <td><?php echo htmlspecialchars($cons['remarques']); ?></td>
            <td>
                <?php if ($cons['file'] != null): ?>
                <a href="../files/<?php echo $cons['file']; ?>" class="btn btn-sm btn-primary">تحميل الملف</a>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-warning">تعديل</button>
                <button class="btn btn-sm btn-danger">حذف</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Consultation Modal -->
<div class="modal fade" id="addConsultationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="../actions/add_consultation.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة استشارة طبية</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="mb-3">
                        <label class="form-label">المتدرب</label>
                        <select name="id_stagiaire" class="form-control" required>
                            <option value="">اختر المتدرب</option>
                            <?php foreach ($stagiaires as $stagiaire): ?>
                                <option value="<?php echo $stagiaire['id']; ?>"><?php echo htmlspecialchars($stagiaire['matricule'] . ' - ' . $stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الاستشارة</label>
                        <input type="date" name="date_consultation" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التشخيص</label>
                        <textarea name="diagnostic" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العلاج</label>
                        <textarea name="traitement" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الملاحظات</label>
                        <textarea name="remarques" class="form-control" rows="3"></textarea>
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
