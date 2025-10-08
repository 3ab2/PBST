<?php
require 'functions.php';
check_role(['admin', 'docteur']);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT c.*, s.*, u.nom AS docteur_nom, u.prenom AS docteur_prenom, u.email AS docteur_email, sp.nom_specialite, st.intitule AS stage_intitule
        FROM consultations c
        JOIN stagiaires s ON c.id_stagiaire = s.id
        JOIN users u ON c.id_docteur = u.id
        JOIN specialites sp ON s.id_specialite = sp.id
        JOIN stages st ON s.id_stage = st.id
        WHERE c.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$consultation = $stmt->fetch();

if (!$consultation) {
    header('Location: index.php');
    exit;
}

// Determine back URL
$back_url = ($_SESSION['role'] === 'admin') ? 'admin/manage_consultations.php' : 'docteur/manage_consultations.php';
$page_title = htmlspecialchars($translations['consultation_details']);
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<style>
    /* Military Style Enhancements for View Consultation Page */
    .military-card {
        background: linear-gradient(135deg, rgba(44, 95, 45, 0.9) 0%, rgba(61, 90, 60, 0.9) 100%);
        border: 3px solid #D4AF37;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        color: #fff;
        position: relative;
        overflow: hidden;
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

    .mt-3.mb-4 {
        text-align: center;
    }

    /* Dark mode adjustments */
    .dark .military-card {
        background: linear-gradient(135deg, rgba(61, 90, 60, 0.9) 0%, rgba(74, 107, 74, 0.9) 100%);
    }

    .dark .military-card .card-header {
        background: linear-gradient(135deg, #2C5F2D 0%, #1A3A1A 100%);
    }
</style>
<?php include 'templates/header.php'; ?>
<div class="mt-3 mb-4 text-start">
    <a href="<?php echo $back_url; ?>" class="btn btn-secondary"><?php echo htmlspecialchars($translations['back'] ?? 'Back'); ?></a>
</div>

<h2><?php echo htmlspecialchars($translations['consultation_details'] ?? 'Consultation Details'); ?></h2>

<div class="row">
    <div class="col-md-12">
        <div class="card military-card">
            <div class="card-header">
                <h5 class="text-center"><?php echo htmlspecialchars($translations['basic_info'] ?? 'Basic Info'); ?></h5>
            </div>
            <div class="card-body">
                <p class="text-start"><strong>ID:</strong> <?php echo $consultation['id']; ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['date_consultation'] ?? 'Date'); ?> :</strong> <?php echo $consultation['date_consultation']; ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['diagnostic'] ?? 'Diagnostic'); ?> :</strong> <?php echo htmlspecialchars($consultation['diagnostic'] ?? ''); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['traitement'] ?? 'Treatment'); ?> :</strong> <?php echo htmlspecialchars($consultation['traitement'] ?? ''); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['remarques'] ?? 'Remarks'); ?> :</strong> <?php echo htmlspecialchars($consultation['remarques'] ?? ''); ?></p>
                <?php if ($consultation['file']) : ?>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['file'] ?? 'File'); ?> :</strong> <a class="btn btn-outline-danger" href="<?php echo $consultation['file']; ?>" target="_blank"><?php echo htmlspecialchars($translations['download'] ?? 'Download'); ?></a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
  
</div>

<div class="row mt-3 mb-4">
    <div class="col-md-12 ">
        <div class="card military-card">
            <div class="card-header">
                <h5 class="text-center"><?php echo htmlspecialchars($translations['stagiaire_info'] ?? 'Stagiaire Info'); ?></h5>
            </div>
            <div class="card-body">
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['matricule'] ?? 'Matricule'); ?> :</strong> <?php echo htmlspecialchars($consultation['matricule']); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['name'] ?? 'Name'); ?> :</strong> <?php echo htmlspecialchars($consultation['nom'] . ' ' . $consultation['prenom']); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['grade'] ?? 'Grade'); ?> :</strong> <?php echo htmlspecialchars($consultation['grade'] ?? ''); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['specialite'] ?? 'Specialty'); ?> :</strong> <?php echo htmlspecialchars($consultation['nom_specialite']); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['stage'] ?? 'Stage'); ?> :</strong> <?php echo htmlspecialchars($consultation['stage_intitule']); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['date_naissance'] ?? 'Birth Date'); ?> :</strong> <?php echo $consultation['date_naissance']; ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['telephone'] ?? 'Phone'); ?> :</strong> <?php echo htmlspecialchars($consultation['telephone'] ?? ''); ?></p>
                <p class="text-start"><strong><?php echo htmlspecialchars($translations['email'] ?? 'Email'); ?> :</strong> <?php echo htmlspecialchars($consultation['email'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</div>


<?php include 'templates/footer.php'; ?>
