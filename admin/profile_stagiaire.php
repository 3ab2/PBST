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

$page_title = htmlspecialchars($translations['trainee_profile']) . ' — ' . htmlspecialchars($stagiaire['nom'] . ' ' . $stagiaire['prenom']) ;
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<style>
    /* Military Style Enhancements for Profile Stagiaire Page */
    .military-card {
        background: linear-gradient(135deg, rgba(44, 95, 45, 0.9) 0%, rgba(61, 90, 60, 0.9) 100%);
        border: 3px solid #D4AF37;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .military-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('../images/army.png') no-repeat center center;
        background-size: cover;
        opacity: 0.1;
        pointer-events: none;
    }

    .military-card .card-header {
        background: linear-gradient(135deg, #1A3A1A 0%, #2C5F2D 100%);
        border-bottom: 2px solid #D4AF37;
        color: #D4AF37;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-align: center;
    }

    .military-card .card-body {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(5px);
    }

    .military-card p {
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .military-card strong {
        color: #D4AF37;
        font-weight: 700;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #556B2F 0%, #6B8E23 100%);
        border: 2px solid #4A5D23;
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #6B8E23 0%, #556B2F 100%);
        border-color: #D4AF37;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(107, 142, 35, 0.4);
    }

    h2 {
        color: #D4AF37;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }

    h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #D4AF37, transparent);
    }

    /* Profile Picture Military Style */
    .img-fluid.rounded-circle {
        border: 4px solid #D4AF37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #2C5F2D 0%, #1A3A1A 100%);
        padding: 5px;
    }

    .bg-light.rounded-circle {
        background: linear-gradient(135deg, #556B2F 0%, #6B8E23 100%) !important;
        border: 4px solid #D4AF37;
        color: #D4AF37;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Table Military Style */
    .table {
        background: linear-gradient(135deg, rgba(76, 91, 65, 0.15) 0%, rgba(58, 73, 47, 0.10) 100%);
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
        border: 2px solid #D4AF37;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .table::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(255, 255, 255, 0.02) 10px,
            rgba(255, 255, 255, 0.02) 20px
        );
        pointer-events: none;
        border-radius: 12px;
    }

    .table th {
        background: linear-gradient(135deg, #1A3A1A 0%, #2C5F2D 100%);
        color: #D4AF37;
        text-align: center;
        vertical-align: middle;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #D4AF37;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
        padding: 0.75rem;
        border: none;
        color: #fff;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(76, 91, 65, 0.20) 0%, rgba(58, 73, 47, 0.15) 100%);
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Dark mode adjustments */
    .dark .military-card {
        background: linear-gradient(135deg, rgba(61, 90, 60, 0.9) 0%, rgba(74, 107, 74, 0.9) 100%);
    }

    .dark .military-card .card-header {
        background: linear-gradient(135deg, #2C5F2D 0%, #1A3A1A 100%);
    }

    .dark .table {
        background: linear-gradient(135deg, rgba(76, 91, 65, 0.30) 0%, rgba(58, 73, 47, 0.25) 100%);
        border-color: #2C5F2D;
    }

    .dark .table th {
        background: linear-gradient(135deg, #2C5F2D 0%, #1A3A1A 100%);
    }

    .dark .table tbody tr:hover {
        background: linear-gradient(135deg, rgba(76, 91, 65, 0.35) 0%, rgba(58, 73, 47, 0.30) 100%);
    }
</style>
<?php include '../templates/header.php'; ?>

<div class="container mt-4">
    <div class="row">
<div class="col-md-12 mb-4">
             <a href="manage_stagiaires.php" class="btn btn-secondary mb-3"><?php echo htmlspecialchars($translations['back'] ?? 'Back'); ?></a>
             <a href="../generate_stagiaire_pdf.php?id=<?php echo $stagiaire['id']; ?>" class="btn btn-secondary mb-3 ms-2">Download PDF</a>
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
    <div class="card military-card mb-4">
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
