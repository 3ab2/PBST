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
?>
<?php include 'templates/header.php'; ?>
<div class="mt-3 mb-4">
    <a href="<?php echo $back_url; ?>" class="btn btn-secondary"><?php echo htmlspecialchars($translations['back'] ?? 'Back'); ?></a>
</div>

<h2><?php echo htmlspecialchars($translations['consultation_details'] ?? 'Consultation Details'); ?></h2>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><?php echo htmlspecialchars($translations['basic_info'] ?? 'Basic Info'); ?></h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> <?php echo $consultation['id']; ?></p>
                <p><strong><?php echo htmlspecialchars($translations['date_consultation'] ?? 'Date'); ?>:</strong> <?php echo $consultation['date_consultation']; ?></p>
                <p><strong><?php echo htmlspecialchars($translations['diagnostic'] ?? 'Diagnostic'); ?>:</strong> <?php echo htmlspecialchars($consultation['diagnostic'] ?? ''); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['traitement'] ?? 'Treatment'); ?>:</strong> <?php echo htmlspecialchars($consultation['traitement'] ?? ''); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['remarques'] ?? 'Remarks'); ?>:</strong> <?php echo htmlspecialchars($consultation['remarques'] ?? ''); ?></p>
                <?php if ($consultation['file']): ?>
                <p><strong><?php echo htmlspecialchars($translations['file'] ?? 'File'); ?>:</strong> <a href="<?php echo $consultation['file']; ?>" target="_blank"><?php echo htmlspecialchars($translations['download'] ?? 'Download'); ?></a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
  
</div>

<div class="row mt-3 mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5><?php echo htmlspecialchars($translations['stagiaire_info'] ?? 'Stagiaire Info'); ?></h5>
            </div>
            <div class="card-body">
                <p><strong><?php echo htmlspecialchars($translations['matricule'] ?? 'Matricule'); ?>:</strong> <?php echo htmlspecialchars($consultation['matricule']); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['name'] ?? 'Name'); ?>:</strong> <?php echo htmlspecialchars($consultation['nom'] . ' ' . $consultation['prenom']); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['grade'] ?? 'Grade'); ?>:</strong> <?php echo htmlspecialchars($consultation['grade'] ?? ''); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['specialite'] ?? 'Specialty'); ?>:</strong> <?php echo htmlspecialchars($consultation['nom_specialite']); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['stage'] ?? 'Stage'); ?>:</strong> <?php echo htmlspecialchars($consultation['stage_intitule']); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['date_naissance'] ?? 'Birth Date'); ?>:</strong> <?php echo $consultation['date_naissance']; ?></p>
                <p><strong><?php echo htmlspecialchars($translations['telephone'] ?? 'Phone'); ?>:</strong> <?php echo htmlspecialchars($consultation['telephone'] ?? ''); ?></p>
                <p><strong><?php echo htmlspecialchars($translations['email'] ?? 'Email'); ?>:</strong> <?php echo htmlspecialchars($consultation['email'] ?? ''); ?></p>
            </div>
        </div>
    </div>
</div>


<?php include 'templates/footer.php'; ?>
