<?php
require '../functions.php';
check_role('secretaire');

// Fetch stagiaires with related stage and specialite names
$sql = "SELECT s.*, st.intitule AS stage_name, sp.nom_specialite AS specialite_name 
        FROM stagiaires s
        JOIN stages st ON s.id_stage = st.id
        JOIN specialites sp ON s.id_specialite = sp.id
        ORDER BY s.id DESC";
$stagiaires = $pdo->query($sql)->fetchAll();

$csrf_token = generate_csrf_token();
?>
<link rel="icon" type="image/svg+xml" href="../images/army.png">
<?php include '../templates/header.php'; ?>
<h2>إدارة المتدربين</h2>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStagiaireModal">إضافة متدرب</button>
<table class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>المعرف</th>
            <th>المسجل</th>
            <th>الاسم</th>
            <th>اللقب</th>
            <th>تاريخ الميلاد</th>
            <th>الهاتف</th>
            <th>البريد الإلكتروني</th>
            <th>المجموعة الدموية</th>
            <th>الدورة</th>
            <th>التخصص</th>
            <th>الصورة</th>
            <th>إجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stagiaires as $stagiaire): ?>
        <tr>
            <td><?php echo $stagiaire['matricule']; ?></td>
            <td><?php echo $stagiaire['date_inscription']; ?></td>
            <td><?php echo htmlspecialchars($stagiaire['nom']); ?></td>
            <td><?php echo htmlspecialchars($stagiaire['prenom']); ?></td>
            <td><?php echo $stagiaire['date_naissance']; ?></td>
            <td><?php echo htmlspecialchars($stagiaire['telephone']); ?></td>
            <td><?php echo htmlspecialchars($stagiaire['email']); ?></td>
            <td><?php echo $stagiaire['groupe_sanguin']; ?></td>
            <td><?php echo htmlspecialchars($stagiaire['stage_name']); ?></td>
            <td><?php echo htmlspecialchars($stagiaire['specialite_name']); ?></td>
            <td>
                <?php if ($stagiaire['photo'] && file_exists("../uploads/stagiaires/" . $stagiaire['photo'])): ?>
                    <img src="../uploads/stagiaires/<?php echo $stagiaire['photo']; ?>" alt="Photo" style="max-width: 50px; max-height: 50px;">
                <?php else: ?>
                    لا توجد صورة
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editStagiaireModal<?php echo $stagiaire['id']; ?>">تعديل</button>
               
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editStagiaireModal<?php echo $stagiaire['id']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="post" action="../actions/edit_stagiaire.php" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">تعديل متدرب</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $stagiaire['id']; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المسجل</label>
                                    <input type="date" name="date_inscription" class="form-control" value="<?php echo $stagiaire['date_inscription']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المجموعة الدموية</label>
                                    <select name="groupe_sanguin" class="form-control" required>
                                        <?php
                                        $blood_groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                                        foreach ($blood_groups as $bg) {
                                            $selected = ($stagiaire['groupe_sanguin'] == $bg) ? 'selected' : '';
                                            echo "<option value=\"$bg\" $selected>$bg</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">المعرف (matricule)</label>
                                    <input type="text" name="matricule" class="form-control" value="<?php echo htmlspecialchars($stagiaire['matricule']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الاسم</label>
                                    <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($stagiaire['nom']); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اللقب</label>
                                    <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($stagiaire['prenom']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" name="date_naissance" class="form-control" value="<?php echo $stagiaire['date_naissance']; ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">العنوان</label>
                                <textarea name="adresse" class="form-control"><?php echo htmlspecialchars($stagiaire['adresse']); ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الهاتف</label>
                                    <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($stagiaire['telephone']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($stagiaire['email']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدرجة</label>
                                    <input type="text" name="grade" class="form-control" value="<?php echo htmlspecialchars($stagiaire['grade']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدورة</label>
                                    <select name="id_stage" class="form-control" required>
                                        <?php
                                        $stages = $pdo->query("SELECT * FROM stages ORDER BY intitule")->fetchAll();
                                        foreach ($stages as $stage) {
                                            $selected = ($stagiaire['id_stage'] == $stage['id']) ? 'selected' : '';
                                            echo "<option value=\"{$stage['id']}\" $selected>" . htmlspecialchars($stage['intitule']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التخصص</label>
                                <select name="id_specialite" class="form-control" required>
                                    <?php
                                    $specialites = $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
                                    foreach ($specialites as $spec) {
                                        $selected = ($stagiaire['id_specialite'] == $spec['id']) ? 'selected' : '';
                                        echo "<option value=\"{$spec['id']}\" $selected>" . htmlspecialchars($spec['nom_specialite']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الصورة (اختياري)</label>
                                <input type="file" name="photo" accept="image/jpeg,image/png,image/gif" class="form-control">
                                <?php if ($stagiaire['photo'] && file_exists("../uploads/stagiaires/" . $stagiaire['photo'])): ?>
                                    <img src="../uploads/stagiaires/<?php echo $stagiaire['photo']; ?>" alt="Photo" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                                <?php endif; ?>
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

<!-- Add Stagiaire Modal -->
<div class="modal fade" id="addStagiaireModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" action="../actions/add_stagiaire.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة متدرب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المسجل</label>
                            <input type="date" name="date_inscription" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                                    <label class="form-label">المجموعة الدموية</label>
                                    <select name="groupe_sanguin" class="form-control" required>
                                        <?php
                                        $blood_groups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
                                        foreach ($blood_groups as $bg) {
                                            $selected = ($stagiaire['groupe_sanguin'] == $bg) ? 'selected' : '';
                                            echo "<option value=\"$bg\" $selected>$bg</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">المعرف (matricule)</label>
                            <input type="text" name="matricule" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اللقب</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الميلاد</label>
                            <input type="date" name="date_naissance" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <textarea name="adresse" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الهاتف</label>
                            <input type="text" name="telephone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الدرجة</label>
                            <input type="text" name="grade" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الدورة</label>
                            <select name="id_stage" class="form-control" required>
                                <option value="">اختر</option>
                                <?php
                                $stages = $pdo->query("SELECT * FROM stages ORDER BY intitule")->fetchAll();
                                foreach ($stages as $stage) {
                                    echo "<option value=\"{$stage['id']}\">" . htmlspecialchars($stage['intitule']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">التخصص</label>
                        <select name="id_specialite" class="form-control" required>
                            <option value="">اختر</option>
                            <?php
                            $specialites = $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
                            foreach ($specialites as $spec) {
                                echo "<option value=\"{$spec['id']}\">" . htmlspecialchars($spec['nom_specialite']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الصورة</label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/gif" class="form-control">
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
