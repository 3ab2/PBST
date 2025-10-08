<?php
require '../functions.php';
check_role('admin');

// Fetch stagiaires with related stage and specialite names
$sql = "SELECT s.*, st.intitule AS stage_name, sp.nom_specialite AS specialite_name 
        FROM stagiaires s
        JOIN stages st ON s.id_stage = st.id
        JOIN specialites sp ON s.id_specialite = sp.id
        ORDER BY s.id DESC";
$stagiaires = $pdo->query($sql)->fetchAll();

$csrf_token = generate_csrf_token();
$page_title = htmlspecialchars($translations['trainees']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>
<div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_admin']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_stagiaire']); ?> <span>></span> <?php echo htmlspecialchars($translations['breadcrumb_liste_stagiaires']); ?></div>
<button class="btn btn-primary mb-3" data-bs-toggle="modal"
    data-bs-target="#addStagiaireModal"><?php echo htmlspecialchars($translations['add_trainee'] ?? 'إضافة متدرب'); ?></button>

<!-- Search and Filter -->
<div class="mb-3 row g-3 align-items-center">
    <div class="col">
        <input type="text" id="searchInput" class="form-control"
            placeholder="<?php echo htmlspecialchars($translations['search'] ?? 'ابحث...'); ?>">
    </div>
    <div class="col">
        <select id="filterStage" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_stages'] ?? 'كل الدورات'); ?></option>
            <?php
            $stages = $pdo->query("SELECT * FROM stages ORDER BY intitule")->fetchAll();
            foreach ($stages as $stage) {
                echo "<option value=\"" . htmlspecialchars($stage['intitule']) . "\">" . htmlspecialchars($stage['intitule']) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select id="filterSpecialite" class="form-select">
            <option value=""><?php echo htmlspecialchars($translations['all_specialities'] ?? 'كل التخصصات'); ?>
            </option>
            <?php
            $specialites = $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
            foreach ($specialites as $spec) {
                echo "<option value=\"" . htmlspecialchars($spec['nom_specialite']) . "\">" . htmlspecialchars($spec['nom_specialite']) . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="col">
        <select id="filterBloodGroup" class="form-select">
            <option value="">
                <?php echo htmlspecialchars($translations['all_blood_groups'] ?? 'كل المجموعات الدموية'); ?></option>
            <?php
            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            foreach ($blood_groups as $bg) {
                echo "<option value=\"$bg\">$bg</option>";
            }
            ?>
        </select>
    </div>
</div>

<table class="table table-striped table-responsive" style="border-radius: 0.5rem; overflow: hidden;">
    <thead>
        <tr>
            <th><?php echo htmlspecialchars($translations['last_name'] ?? 'الاسم'); ?></th>
            <th><?php echo htmlspecialchars($translations['first_name'] ?? 'اللقب'); ?></th>
            <th><?php echo htmlspecialchars($translations['id'] ?? 'المعرف'); ?></th>
            <th><?php echo htmlspecialchars($translations['blood_group'] ?? 'المجموعة الدموية'); ?></th>
            <th><?php echo htmlspecialchars($translations['stage'] ?? 'الدورة'); ?></th>
            <th><?php echo htmlspecialchars($translations['speciality'] ?? 'التخصص'); ?></th>
            <th><?php echo htmlspecialchars($translations['actions'] ?? 'إجراءات'); ?></th>
        </tr>
    </thead>
    <tbody id="stagiairesTableBody">
        <?php foreach ($stagiaires as $stagiaire): ?>
            <tr data-stage="<?php echo htmlspecialchars($stagiaire['stage_name']); ?>"
                data-specialite="<?php echo htmlspecialchars($stagiaire['specialite_name']); ?>"
                data-bloodgroup="<?php echo $stagiaire['groupe_sanguin']; ?>"
                id="stagiaireRow<?php echo $stagiaire['id']; ?>">
                <td><?php echo htmlspecialchars($stagiaire['nom']); ?></td>
                <td><?php echo htmlspecialchars($stagiaire['prenom']); ?></td>
                <td><?php echo $stagiaire['matricule']; ?></td>
                <td><?php echo $stagiaire['groupe_sanguin']; ?></td>
                <td><?php echo htmlspecialchars($stagiaire['stage_name']); ?></td>
                <td><?php echo htmlspecialchars($stagiaire['specialite_name']); ?></td>
                <td class="text-center">
                    <a href="profile_stagiaire.php?id=<?php echo $stagiaire['id']; ?>"
                        class="btn btn-sm btn-info me-1"><i class="fas fa-eye"></i></a>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#editStagiaireModal<?php echo $stagiaire['id']; ?>"><i class="fas fa-edit"></i></button>
                    <form method="post" action="../actions/delete_stagiaire.php" style="display:inline-block;"
                        onsubmit="return confirm('<?php echo htmlspecialchars($translations['confirm_delete_trainee']); ?>');">
                        <input type="hidden" name="id" value="<?php echo $stagiaire['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <button type="submit"
                            class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editStagiaireModal<?php echo $stagiaire['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form class="edit-stagiaire-form" method="post" action="../actions/edit_stagiaire.php"
                            enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo htmlspecialchars($translations['edit_trainee']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?php echo $stagiaire['id']; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['registered']); ?></label>
                                        <input type="date" name="date_inscription" class="form-control"
                                            value="<?php echo $stagiaire['date_inscription']; ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['blood_group']); ?></label>
                                        <select name="groupe_sanguin" class="form-control" required>
                                            <?php
                                            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
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
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['matricule_label']); ?></label>
                                        <input type="text" name="matricule" class="form-control"
                                            value="<?php echo htmlspecialchars($stagiaire['matricule']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                                        <input type="text" name="nom" class="form-control"
                                            value="<?php echo htmlspecialchars($stagiaire['nom']); ?>" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                                        <input type="text" name="prenom" class="form-control"
                                            value="<?php echo htmlspecialchars($stagiaire['prenom']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['birth_date']); ?></label>
                                        <input type="date" name="date_naissance" class="form-control"
                                            value="<?php echo $stagiaire['date_naissance']; ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['address']); ?></label>
                                    <textarea name="adresse"
                                        class="form-control"><?php echo htmlspecialchars($stagiaire['adresse']); ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['phone']); ?></label>
                                        <input type="text" name="telephone" class="form-control"
                                            value="<?php echo htmlspecialchars($stagiaire['telephone']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo htmlspecialchars($stagiaire['email']); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['grade']); ?></label>
                                        <select name="grade" class="form-control">
                                            <option value=""><?php echo htmlspecialchars($translations['choose']); ?>
                                            </option>
                                            <option value="Lieutenant">Lieutenant</option>
                                            <option value="Sous-Lieutenant">Sous-Lieutenant</option>
                                            <option value="Adjudant Chef">Adjudant Chef</option>
                                            <option value="Adjudant">Adjudant</option>
                                            <option value="Sergent Chef">Sergent Chef</option>
                                            <option value="Sergent">Sergent</option>
                                            <option value="Caporal Chef">Caporal Chef</option>
                                            <option value="Caporal">Caporal</option>
                                            <option value="2 eme Classe">2 eme Classe</option>
                                            <option value="1er Classe">1er Classe</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label"><?php echo htmlspecialchars($translations['stage']); ?></label>
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
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['speciality']); ?></label>
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
                                    <label
                                        class="form-label"><?php echo htmlspecialchars($translations['photo_optional']); ?></label>
                                    <input type="file" name="photo" accept="image/jpeg,image/png,image/gif"
                                        class="form-control">
                                    <?php if ($stagiaire['photo'] && file_exists("../uploads/stagiaires/" . $stagiaire['photo'])): ?>
                                        <img src="../uploads/stagiaires/<?php echo $stagiaire['photo']; ?>" alt="Photo"
                                            style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                                <button type="submit"
                                    class="btn btn-primary"><?php echo htmlspecialchars($translations['save_changes']); ?></button>
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
            <form id="addStagiaireForm" method="post" action="../actions/add_stagiaire.php"
                enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo htmlspecialchars($translations['add_trainee']); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['registered']); ?></label>
                            <input type="date" name="date_inscription" class="form-control"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['blood_group']); ?></label>
                            <select name="groupe_sanguin" class="form-control" required>
                                <option value=""><?php echo htmlspecialchars($translations['choose']); ?></option>
                                <?php
                                $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                foreach ($blood_groups as $bg) {
                                    echo "<option value=\"$bg\">$bg</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['matricule_label']); ?></label>
                            <input type="text" name="matricule" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['last_name']); ?></label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['first_name']); ?></label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label
                                class="form-label"><?php echo htmlspecialchars($translations['birth_date']); ?></label>
                            <input type="date" name="date_naissance" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['address']); ?></label>
                        <textarea name="adresse" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($translations['phone']); ?></label>
                            <input type="text" name="telephone" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($translations['email']); ?></label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($translations['grade']); ?></label>
                            <select name="grade" class="form-control">
                                <option value=""><?php echo htmlspecialchars($translations['choose']); ?></option>
                                <option value="Lieutenant">Lieutenant</option>
                                <option value="Sous-Lieutenant">Sous-Lieutenant</option>
                                <option value="Adjudant Chef">Adjudant Chef</option>
                                <option value="Adjudant">Adjudant</option>
                                <option value="Sergent Chef">Sergent Chef</option>
                                <option value="Sergent">Sergent</option>
                                <option value="Caporal Chef">Caporal Chef</option>
                                <option value="Caporal">Caporal</option>
                                <option value="2 eme Classe">2 eme Classe</option>
                                <option value="1er Classe">1er Classe</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?php echo htmlspecialchars($translations['stage']); ?></label>
                            <select name="id_stage" class="form-control" required>
                                <option value=""><?php echo htmlspecialchars($translations['choose']); ?></option>
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
                        <label class="form-label"><?php echo htmlspecialchars($translations['speciality']); ?></label>
                        <select name="id_specialite" class="form-control" required>
                            <option value=""><?php echo htmlspecialchars($translations['choose']); ?></option>
                            <?php
                            $specialites = $pdo->query("SELECT * FROM specialites ORDER BY nom_specialite")->fetchAll();
                            foreach ($specialites as $spec) {
                                echo "<option value=\"{$spec['id']}\">" . htmlspecialchars($spec['nom_specialite']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?php echo htmlspecialchars($translations['photo']); ?></label>
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/gif" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?php echo htmlspecialchars($translations['cancel']); ?></button>
                    <button type="submit"
                        class="btn btn-primary"><?php echo htmlspecialchars($translations['add']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterStage = document.getElementById('filterStage');
        const filterSpecialite = document.getElementById('filterSpecialite');
        const filterBloodGroup = document.getElementById('filterBloodGroup');
        const tbody = document.getElementById('stagiairesTableBody');

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase();
            const stageValue = filterStage.value;
            const specialiteValue = filterSpecialite.value;
            const bloodGroupValue = filterBloodGroup.value;

            const rows = tbody.querySelectorAll('tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const stage = row.getAttribute('data-stage');
                const specialite = row.getAttribute('data-specialite');
                const bloodgroup = row.getAttribute('data-bloodgroup');

                const matchesSearch = text.includes(searchValue);
                const matchesStage = !stageValue || stage === stageValue;
                const matchesSpecialite = !specialiteValue || specialite === specialiteValue;
                const matchesBloodGroup = !bloodGroupValue || bloodgroup === bloodGroupValue;

                if (matchesSearch && matchesStage && matchesSpecialite && matchesBloodGroup) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterTable);
        filterStage.addEventListener('change', filterTable);
        filterSpecialite.addEventListener('change', filterTable);
        filterBloodGroup.addEventListener('change', filterTable);
    });
</script>
<script src="js/edit_stagiaire_ajax.js"></script>

<?php include '../templates/footer.php'; ?>