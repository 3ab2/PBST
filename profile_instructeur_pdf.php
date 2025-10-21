<?php
ob_start();
require 'vendor/autoload.php';
require 'functions.php';
$lang = include 'lang/fr.php';
echo '<link rel="icon" type="image/svg+xml" href="../images/bst.png">';
check_role(['cellule_pedagogique', 'admin']);

$instructor_id = $_GET['instructor_id'] ?? null;
if (!$instructor_id) {
    die('Missing instructor_id');
}

// Fetch instructor
$stmt = $pdo->prepare("SELECT * FROM instructors WHERE id_instructor = ?");
$stmt->execute([$instructor_id]);
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$instructor) {
    die('Instructor not found');
}

// Subjects
$subStmt = $pdo->prepare("SELECT s.name, s.type FROM subjects s JOIN instructor_subjects isub ON s.id_subject = isub.subject_id WHERE isub.instructor_id = ? ORDER BY s.type, s.name");
$subStmt->execute([$instructor_id]);
$subjects = $subStmt->fetchAll(PDO::FETCH_ASSOC);

// Observations (limit to recent 100 to keep PDF light)
$obsStmt = $pdo->prepare("SELECT o.*, s.name AS subject_name FROM observations o JOIN subjects s ON o.subject_id = s.id_subject WHERE o.instructor_id = ? ORDER BY o.obs_date DESC, o.heure_debut DESC LIMIT 100");
$obsStmt->execute([$instructor_id]);
$observations = $obsStmt->fetchAll(PDO::FETCH_ASSOC);

// Monthly stats (last 12 months)
$msStmt = $pdo->prepare("SELECT year, month, positive_count, negative_count, total, positive_ratio FROM monthly_instructor_stats WHERE instructor_id = ? ORDER BY year DESC, month DESC LIMIT 12");
$msStmt->execute([$instructor_id]);
$monthlyStats = $msStmt->fetchAll(PDO::FETCH_ASSOC);

// Custom PDF class to handle header and footer on all pages
class MyPDF extends TCPDF {
    public function Header() {
        

        // Header: logo center, date left, text right
        $logoFile = __DIR__ . '/images/far.png';
           if (file_exists($logoFile)) {
             $this->Image($logoFile, 90, 10, 15, 20, '', '', '', false, 300, '', false, false, 0);
        }

        $this->SetFont('helvetica', '', 10);
        $this->SetXY(10, 12);
        $this->Cell(50, 10, 'Date: ' . date('Y-m-d'), 0, 0, 'L');

        $this->SetFont('helvetica', 'B', 8);
        $this->SetXY(120, 10);
        $this->Cell(80, 5, 'ROYAUME DU MAROC', 0, 1, 'C');
        $this->SetXY(120, 15);
        $this->Cell(80, 5, 'FORCES ARMÉES ROYALES', 0, 1, 'C');
        $this->SetXY(120, 20);
        $this->Cell(80, 5, 'PLACE D’ARME DE MEKNES', 0, 1, 'C');
        $this->SetXY(120, 25);
        $this->Cell(80, 5, '1° BATTAILLON DE SOUTIEN DES TRANSMISSIONS', 0, 1, 'C');
        
    }
     
    
    public function Footer() {
        // Draw footer line
        $this->Line(10, 280, 200, 280);

        // Footer: copyright center
        $this->SetFont('helvetica', '', 8);
        $this->SetXY(0, 285);
        $this->Cell(0, 10, '© 2025 Système de Gestion des Instructeurs . Tous droits réservés.', 0, 0, 'C');
    }
}

$pdf = new MyPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('PBST App');
$pdf->SetAuthor('1 BST');
$pdf->SetTitle('Instructor Profile');
$pdf->SetSubject('Instructor PDF');

// Enable custom header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// Set margins
$pdf->SetMargins(15, 50, 15); // Increased top margin to accommodate header

// Add a page
$pdf->AddPage();

// Body title in red center
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetTextColor(0, 0, 255); // Blue
$pdf->SetXY(0, 50);
$nom = htmlspecialchars($instructor['first_name']);
$prenom = htmlspecialchars($instructor['last_name']);
$pdf->Cell(0, 10, 'L\'instructeur ' . $nom . ' ' . $prenom, 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Reset to black

// Set font for content
$pdf->SetFont('helvetica', '', 12);

// Line after header
$pdf->Ln(10);
$pdf->Line(10, 70, 200, 70);

// Define months array
$months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];

// Personal Information
$html = '<h2 style="color:#2C5F2D;">' . $lang['personal_information'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
$html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
            <th>' . $lang['first_name'] . '</th>
            <th>' . $lang['last_name'] . '</th>
            <th>CINE</th>
            <th>MLE</th>
            <th>' . $lang['status'] . '</th>
          </tr>';
$html .= '<tr>
            <td>' . htmlspecialchars($instructor['first_name']) . '</td>
            <td>' . htmlspecialchars($instructor['last_name']) . '</td>
            <td>' . htmlspecialchars($instructor['cine']) . '</td>
            <td>' . htmlspecialchars($instructor['mle']) . '</td>
            <td>' . ($instructor['is_active'] ? 'Active' : 'Inactive') . '</td>
          </tr>';
$html .= '</table>';

// Subjects
if (!empty($subjects)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['subjects'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['subject_name'] . '</th>
                <th>' . $lang['type'] . '</th>
              </tr>';
    foreach ($subjects as $s) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($s['name']) . '</td>
                    <td>' . htmlspecialchars($s['type']) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Monthly Statistics
if (!empty($monthlyStats)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['monthly_statistics'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['year'] . '</th>
                <th>' . $lang['month'] . '</th>
                <th>' . $lang['positive'] . '</th>
                <th>' . $lang['negative'] . '</th>
                <th>' . $lang['total'] . '</th>
                <th>' . $lang['score'] . '</th>
              </tr>';
    foreach ($monthlyStats as $ms) {
        $score = isset($ms['positive_ratio']) ? number_format($ms['positive_ratio'] * 100, 1) . '%' : 'N/A';
        $html .= '<tr>
                    <td>' . (int)$ms['year'] . '</td>
                    <td>' . htmlspecialchars($months[(int)$ms['month']] ?? (string)$ms['month']) . '</td>
                    <td>' . (int)$ms['positive_count'] . '</td>
                    <td>' . (int)$ms['negative_count'] . '</td>
                    <td>' . (int)$ms['total'] . '</td>
                    <td>' . htmlspecialchars($score) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Observations
if (!empty($observations)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['observations'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['subject'] . '</th>
                <th>' . $lang['date'] . '</th>
                <th>' . $lang['time'] . '</th>
                <th>' . $lang['rating'] . '</th>
                <th>' . $lang['score'] . '</th>
                <th>' . $lang['comment'] . '</th>
              </tr>';
    foreach ($observations as $o) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($o['subject_name']) . '</td>
                    <td>' . htmlspecialchars($o['obs_date']) . '</td>
                    <td>' . htmlspecialchars($o['heure_debut']) . ' - ' . htmlspecialchars($o['heure_fin']) . '</td>
                    <td>' . htmlspecialchars($o['rating']) . '</td>
                    <td>' . htmlspecialchars($o['score'] ?? '-') . '</td>
                    <td>' . htmlspecialchars($o['comment'] ?? '') . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('profile_instructor_' . $instructor_id . '.pdf', 'I');
