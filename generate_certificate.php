<?php
ob_start();
require 'vendor/autoload.php';
require 'functions.php';
$lang = include 'lang/fr.php';
echo '<link rel="icon" type="image/svg+xml" href="images/bst.png">';
check_role(['cellule_pedagogique', 'admin']);

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? null;

// Fetch top 3 instructors
$query = "
    SELECT i.id_instructor,
           CONCAT(i.first_name, ' ', i.last_name) AS name,
           COUNT(CASE WHEN o.rating='positive' THEN 1 END) AS positive_count,
           AVG(o.score) AS avg_score
    FROM instructors i
    LEFT JOIN observations o ON o.instructor_id = i.id_instructor
    WHERE YEAR(o.obs_date) = ?
";

$params = [$year];

if ($month) {
    $query .= " AND MONTH(o.obs_date) = ?";
    $params[] = $month;
}

$query .= "
    GROUP BY i.id_instructor
    ORDER BY positive_count DESC, avg_score DESC
    LIMIT 3
";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $instructors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die('Error fetching instructors: ' . $e->getMessage());
}

if (empty($instructors)) {
    die('No instructors found for the selected period.');
}

// Custom PDF class for certificate
class CertificatePDF extends TCPDF {
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

$pdf = new CertificatePDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('PBST App');
$pdf->SetAuthor('1 BST');
$pdf->SetTitle('Certificates for Top Instructors');
$pdf->SetSubject('Instructor Certificates');

// Enable custom header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// Set margins
$pdf->SetMargins(15, 50, 15);

// Ranks for top 3
$ranks = ['1er', '2ème', '3ème'];

foreach ($instructors as $index => $instructor) {
    $pdf->AddPage();

    // Certificate title
    $pdf->SetFont('helvetica', 'B', 24);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->SetXY(0, 60);
    $pdf->Cell(0, 20, 'CERTIFICAT D\'EXCELLENCE', 0, 1, 'C');
    $pdf->SetTextColor(0, 0, 0);

    // Rank and year
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Ln(10);
    $pdf->Cell(0, 10, $ranks[$index] . ' Meilleur Instructeur de l\'Année ' . $year, 0, 1, 'C');

    // Instructor name
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Ln(20);
    $pdf->Cell(0, 10, htmlspecialchars($instructor['name']), 0, 1, 'C');

    // Details
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 10, "Félicitations pour votre excellence en tant qu'instructeur. Vous avez obtenu " . (int)$instructor['positive_count'] . " observations positives avec une moyenne de " . number_format($instructor['avg_score'], 2) . " points.", 0, 'C');

    // Signature placeholder
    $pdf->Ln(30);
    $pdf->Cell(0, 10, 'Signature du Commandant', 0, 1, 'R');
    $pdf->Line(150, $pdf->GetY(), 190, $pdf->GetY());
}

ob_end_clean();
$pdf->Output('certificates_top_instructors_' . $year . '.pdf', 'I');
?>
