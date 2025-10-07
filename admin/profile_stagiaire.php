<?php
require '../functions.php';
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'secretaire'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_stagiaires.php');
    exit;
}

$id = (int)$_GET['id'];

// Fetch stagiaire with stage and specialite
$sql = "SELECT s.*, st.intitule AS stage_name, st.date_debut, st.date_fin, sp.nom_specialite AS specialite_name, sp.description AS specialite_description
        FROM stagiaires s
        JOIN stages st ON s.id_stage = st.id
        JOIN specialites sp ON s.id_specialite = sp.id
        WHERE s.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$stagiaire = $stmt->fetch();

if (!$stagiaire) {
    header('Location: manage_stagiaires.php');
    exit;
}

// Fetch consultations with docteur
$consultations = $pdo->prepare("SELECT c.*, u.nom AS docteur_nom, u.prenom AS docteur_prenom
                                FROM consultations c
                                JOIN users u ON c.id_docteur = u.id
                                WHERE c.id_stagiaire = ?
                                ORDER BY c.date_consultation DESC");
$consultations->execute([$id]);
$consultations = $consultations->fetchAll();

// Fetch permissions
$permissions = $pdo->prepare("SELECT * FROM permissions WHERE id_stagiaire = ? ORDER BY date_debut DESC");
$permissions->execute([$id]);
$permissions = $permissions->fetchAll();

// Fetch remarques
$remarques = $pdo->prepare("SELECT r.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
                            FROM remarques r
                            LEFT JOIN users u ON r.auteur_id = u.id
                            WHERE r.id_stagiaire = ?
                            ORDER BY r.date_remarque DESC");
$remarques->execute([$id]);
$remarques = $remarques->fetchAll();

// Fetch punitions
$punitions = $pdo->prepare("SELECT p.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
                            FROM punitions p
                            LEFT JOIN users u ON p.auteur_id = u.id
                            WHERE p.id_stagiaire = ?
                            ORDER BY p.date_punition DESC");
$punitions->execute([$id]);
$punitions = $punitions->fetchAll();

// Assigned Supervisor: latest consultation's docteur
$assigned_supervisor = null;
if (!empty($consultations)) {
    $latest_consult = $consultations[0];
    $assigned_supervisor = $latest_consult['docteur_nom'] . ' ' . $latest_consult['docteur_prenom'];
}

?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12 mb-4">
             <a href="manage_stagiaires.php" class="btn btn-secondary mb-3"><?php echo htmlspecialchars($translations['back'] ?? 'Back'); ?></a>
            <h2 class="text-center"><?php echo htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']); ?></h2>
        </div>
    </div>
   <!-- Profile Picture -->
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <?php if ($stagiaire['photo'] && file_exists("../uploads/stagiaires/" . $stagiaire['photo'])): ?>
                <img src="../uploads/stagiaires/<?php echo $stagiaire['photo']; ?>" alt="Profile Photo" class="img-fluid rounded-circle" style="width: 200px; height: 200px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                    <span class="text-muted"><?php echo htmlspecialchars($translations['no_photo']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['personal_information'] ?? 'Personal Information'); ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['matricule_label']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['matricule']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['last_name']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['nom']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['first_name']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['prenom']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['birth_date']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['date_naissance']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['blood_group']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['groupe_sanguin']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['grade']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['grade'] ?: 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['contact_information'] ?? 'Contact Information'); ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['phone']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['telephone'] ?: 'N/A'); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['email']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['email'] ?: 'N/A'); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['address']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['adresse'] ?: 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Internship / Training Details -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['internship_training_details'] ?? 'Internship / Training Details'); ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['stage']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['stage_name']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['speciality']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['specialite_name']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['registered']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['date_inscription']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Course / Program Info -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['course_program_info'] ?? 'Course / Program Info'); ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['stage']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['stage_name']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['start_date'] ?? 'Start Date'); ?>:</strong> <?php echo htmlspecialchars($stagiaire['date_debut'] ?: 'N/A'); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><?php echo htmlspecialchars($translations['end_date'] ?? 'End Date'); ?>:</strong> <?php echo htmlspecialchars($stagiaire['date_fin'] ?: 'N/A'); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['speciality']); ?>:</strong> <?php echo htmlspecialchars($stagiaire['specialite_name']); ?></p>
                    <p><strong><?php echo htmlspecialchars($translations['description'] ?? 'Description'); ?>:</strong> <?php echo htmlspecialchars($stagiaire['specialite_description'] ?: 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['permissions'] ?? 'Permissions'); ?></h5>
        </div>
        <div class="card-body">
            <?php if (!empty($permissions)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th><?php echo htmlspecialchars($translations['type'] ?? 'Type'); ?></th>
                            <th><?php echo htmlspecialchars($translations['start_date'] ?? 'Start Date'); ?></th>
                            <th><?php echo htmlspecialchars($translations['end_date'] ?? 'End Date'); ?></th>
                            <th><?php echo htmlspecialchars($translations['status'] ?? 'Status'); ?></th>
                            <th><?php echo htmlspecialchars($translations['reason'] ?? 'Reason'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($permissions as $perm): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($perm['type']); ?></td>
                            <td><?php echo htmlspecialchars($perm['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($perm['date_fin']); ?></td>
                            <td><?php echo htmlspecialchars($perm['statut']); ?></td>
                            <td><?php echo htmlspecialchars($perm['motif']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo htmlspecialchars($translations['no_permissions'] ?? 'No permissions recorded.'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notes -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['notes'] ?? 'Notes'); ?></h5>
        </div>
        <div class="card-body">
            <?php if (!empty($remarques)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th><?php echo htmlspecialchars($translations['date'] ?? 'Date'); ?></th>
                            <th><?php echo htmlspecialchars($translations['author'] ?? 'Author'); ?></th>
                            <th><?php echo htmlspecialchars($translations['note'] ?? 'Note'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($remarques as $rem): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rem['date_remarque']); ?></td>
                            <td><?php echo htmlspecialchars($rem['auteur_nom'] . ' ' . $rem['auteur_prenom']); ?></td>
                            <td><?php echo htmlspecialchars($rem['remarque']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo htmlspecialchars($translations['no_notes'] ?? 'No notes recorded.'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sanctions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['sanctions'] ?? 'Sanctions'); ?></h5>
        </div>
        <div class="card-body">
            <?php if (!empty($punitions)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th><?php echo htmlspecialchars($translations['date'] ?? 'Date'); ?></th>
                            <th><?php echo htmlspecialchars($translations['type'] ?? 'Type'); ?></th>
                            <th><?php echo htmlspecialchars($translations['description'] ?? 'Description'); ?></th>
                            <th><?php echo htmlspecialchars($translations['responsible'] ?? 'Responsible'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($punitions as $pun): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pun['date_punition']); ?></td>
                            <td><?php echo htmlspecialchars($pun['type']); ?></td>
                            <td><?php echo htmlspecialchars($pun['description']); ?></td>
                            <td><?php echo htmlspecialchars($pun['auteur_nom'] . ' ' . $pun['auteur_prenom']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo htmlspecialchars($translations['no_sanctions'] ?? 'No sanctions recorded.'); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Consultations -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?php echo htmlspecialchars($translations['consultations'] ?? 'Consultations'); ?></h5>
        </div>
        <div class="card-body">
            <?php if (!empty($consultations)): ?>
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th><?php echo htmlspecialchars($translations['date'] ?? 'Date'); ?></th>
                            <th><?php echo htmlspecialchars($translations['doctor'] ?? 'Doctor'); ?></th>
                            <th><?php echo htmlspecialchars($translations['diagnosis'] ?? 'Diagnosis'); ?></th>
                            <th><?php echo htmlspecialchars($translations['treatment'] ?? 'Treatment'); ?></th>
                            <th><?php echo htmlspecialchars($translations['remarques']); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $cons): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cons['date_consultation']); ?></td>
                            <td><?php echo htmlspecialchars($cons['docteur_nom'] . ' ' . $cons['docteur_prenom']); ?></td>
                            <td><?php echo htmlspecialchars($cons['diagnostic']); ?></td>
                            <td><?php echo htmlspecialchars($cons['traitement']); ?></td>
                            <td><?php echo htmlspecialchars($cons['remarques']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php echo htmlspecialchars($translations['no_consultations'] ?? 'No consultations recorded.'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
