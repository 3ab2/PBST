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

// HTML content
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

$month_name = $months[$month];

// Place-specific styling (for top 3 only)
$badge_text = '';
$badge_color = '#4A6B4A';
if ($position === 1) { $badge_text = '1st Place'; $badge_color = '#D4AF37'; }
elseif ($position === 2) { $badge_text = '2nd Place'; $badge_color = '#C0C0C0'; }
elseif ($position === 3) { $badge_text = '3rd Place'; $badge_color = '#CD7F32'; }
$ratio = number_format($stats['positive_ratio'] * 100, 1);

$html = '
<style>
body { font-family: dejavusans; text-align: center; padding: 40px; }
.logo { margin-top: 20px; }
.title { font-size: 28px; margin-top: 30px; font-weight: bold; }
.badge { display:inline-block; margin-top:10px; padding:6px 14px; border-radius:20px; color:#000; font-weight:bold; border:2px solid ' . $badge_color . '; }
.name { font-size: 24px; margin-top: 20px; }
.meta { margin-top: 20px; font-size: 16px; }
.signatures { margin-top: 60px; display: flex; justify-content: space-around; }
.signature { text-align: center; }
</style>

<img class="logo" src="images/bst.png" alt="logo" width="140"/>
<div class="title">Certificate of Appreciation</div>
' . ($badge_text ? '<div class="badge" style="background-color: rgba(0,0,0,0);">' . htmlspecialchars($badge_text) . '</div>' : '') . '
<div class="meta">This is to certify that</div>
<div class="name">' . htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']) . '</div>
<div class="meta">CINE: ' . htmlspecialchars($instructor['cine']) . ' | MLE: ' . htmlspecialchars($instructor['mle']) . '</div>
<p style="margin-top:30px;">
    In recognition of outstanding teaching performance for <strong>' . $month_name . ' ' . $year . '</strong>.
    Positive observations: <strong>' . $stats['positive_count'] . '</strong>, Negative: <strong>' . $stats['negative_count'] . '</strong>, Total: <strong>' . $stats['total'] . '</strong>, Score: <strong>' . $ratio . '%</strong>.
</p>
<div class="signatures">
    <div class="signature">
        <img src="images/director_signature.png" alt="director" width="180"/><br/>
        Director<br/>Colonel Erradi
    </div>
    <div class="signature">
        <img src="images/cellule_signature.png" alt="cellule" width="180"/><br/>
        Cellule Pédagogique<br/>Head of Cellule
    </div>
</div>
<div style="margin-top:30px;">Date: ' . date('F j, Y') . '</div>
';

$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('certificate_' . $instructor_id . '_' . $year . '_' . $month . '.pdf', 'I');
?>
