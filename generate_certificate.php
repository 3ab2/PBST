<?php
require 'vendor/autoload.php';
require 'functions.php';
check_role(['cellule_pedagogique', 'admin']);

// Get parameters
$instructor_id = $_GET['instructor_id'] ?? null;
$year = $_GET['year'] ?? null;
$month = $_GET['month'] ?? null;
$position = isset($_GET['position']) ? (int)$_GET['position'] : null; // 1,2,3 for top 3

if (!$instructor_id || !$year || !$month) {
    die('Missing parameters');
}

// Get instructor data
$stmt = $pdo->prepare("SELECT * FROM instructors WHERE id_instructor = ?");
$stmt->execute([$instructor_id]);
$instructor = $stmt->fetch();

if (!$instructor) {
    die('Instructor not found');
}

// Get stats
$stmt = $pdo->prepare("SELECT * FROM monthly_instructor_stats WHERE instructor_id = ? AND year = ? AND month = ?");
$stmt->execute([$instructor_id, $year, $month]);
$stats = $stmt->fetch();

if (!$stats) {
    die('Stats not found');
}

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Cellule Pédagogique');
$pdf->SetTitle('Certificate of Appreciation');
$pdf->SetSubject('Instructor Certificate');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

// Set background image
$pdf->Image('images/border.jpg', 0, 0, 210, 297, 'JPG', '', '', false, 300, '', false, false, 0);

// HTML content
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

$month_name = $months[$month];

$ratio = number_format($stats['positive_ratio'] * 100, 1);

$html = '
<style>
body { font-family: dejavusans; margin: 0; padding: 20px; }
.header { display: table; width: 100%; margin-bottom: 40px; }
.header-left { display: table-cell; vertical-align: top; text-align: left; font-size: 14px; }
.header-center { display: table-cell; vertical-align: top; text-align: center; }
.header-right { display: table-cell; vertical-align: top; text-align: right; font-size: 12px; }
.body { text-align: center; margin: 40px 0; }
.title { font-size: 32px; font-weight: bold; margin-bottom: 20px; }
.appreciation { font-size: 18px; margin-bottom: 20px; }
.name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }
.details { font-size: 16px; margin-bottom: 30px; }
.footer { text-align: right; margin-top: 60px; font-size: 14px; }
.signature { margin-bottom: 10px; }
.hinata { font-size: 12px; color: #888; }
</style>

<div class="header">
    <div class="header-left">MEKNES LE, ' . date('d M Y') . '</div>
    <div class="header-center"><img src="images/far.png" alt="logo" width="80"/></div>
    <div class="header-right">
        ROYAUME DU MAROC<br>
        FORCES ARMÉES ROYALES<br>
        PLACE D’ARME DE MEKNES<br>
        1° BATAILLON DE SOUTIEN DES TRANSMISSIONS
    </div>
</div>

<div class="body">
    <div class="title">Meilleur Formateur</div>
    <div class="appreciation">
        En reconnaissance de vos excellentes performances pédagogiques et de votre dévouement à l\'enseignement.<br>
        Merci pour votre contribution exceptionnelle à la formation de nos stagiaires.
    </div>
    <div class="name">' . htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']) . '</div>
    <div class="details">
        CINE: ' . htmlspecialchars($instructor['cine']) . ' | MLE: ' . htmlspecialchars($instructor['mle']) . '<br>
        Période: ' . $month_name . ' ' . $year . '<br>
        Observations positives: ' . $stats['positive_count'] . ', Négatives: ' . $stats['negative_count'] . ', Total: ' . $stats['total'] . ', Score: ' . $ratio . '%
    </div>
</div>

<div class="footer">
    <div class="signature">Le colonel ABDELHAFID ERRADI</div>
    <div class="hinata">Hinata ❤️</div>
</div>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('certificate_' . $instructor_id . '_' . $year . '_' . $month . '.pdf', 'I');
?>
