<?php
ob_start();
require 'vendor/autoload.php';
require 'functions.php';
$lang = include 'lang/fr.php';
echo '<link rel="icon" type="image/svg+xml" href="../images/bst.png">';
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
        $this->Cell(0, 10, '© 2025 Système de Gestion des Stagiaires . Tous droits réservés.', 0, 0, 'C');
    }
}

// Check if id is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid stagiaire ID');
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
    die('Stagiaire not found');
}

// Fetch consultations
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

// Fetch notes
$remarques = $pdo->prepare("SELECT r.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
                            FROM remarques r
                            LEFT JOIN users u ON r.auteur_id = u.id
                            WHERE r.id_stagiaire = ?
                            ORDER BY r.date_remarque DESC");
$remarques->execute([$id]);
$remarques = $remarques->fetchAll();

// Fetch sanctions
$punitions = $pdo->prepare("SELECT p.*, u.nom AS auteur_nom, u.prenom AS auteur_prenom
                            FROM punitions p
                            LEFT JOIN users u ON p.auteur_id = u.id
                            WHERE p.id_stagiaire = ?
                            ORDER BY p.date_punition DESC");
$punitions->execute([$id]);
$punitions = $punitions->fetchAll();

$html = '';

// Create new PDF document
$pdf = new MyPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('PBST App');
$pdf->SetAuthor('1 BST');
$pdf->SetTitle('Stagiaire Profile');
$pdf->SetSubject('Stagiaire PDF');

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
$nom = htmlspecialchars($stagiaire['nom']);
$prenom = htmlspecialchars($stagiaire['prenom']);
$pdf->Cell(0, 10, 'Le stagiaire ' . $nom . ' ' . $prenom, 0, 1, 'C');
$pdf->SetTextColor(0, 0, 0); // Reset to black

// Set font for content
$pdf->SetFont('helvetica', '', 12);

// Line after header
$pdf->Ln(10);
$pdf->Line(10, 70, 200, 70);

// Personal Information
$html .= '<h2 style="color:#2C5F2D;">' . $lang['personal_information'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
$html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
            <th>' . $lang['matricule'] . '</th>
            <th>' . $lang['last_name'] . '</th>
            <th>' . $lang['first_name'] . '</th>
            <th>' . $lang['birth_date'] . '</th>
            <th>' . $lang['blood_group'] . '</th>
            <th>' . $lang['grade'] . '</th>
          </tr>';
$html .= '<tr>
            <td>' . htmlspecialchars($stagiaire['matricule']) . '</td>
            <td>' . htmlspecialchars($stagiaire['nom']) . '</td>
            <td>' . htmlspecialchars($stagiaire['prenom']) . '</td>
            <td>' . htmlspecialchars($stagiaire['date_naissance']) . '</td>
            <td>' . htmlspecialchars($stagiaire['groupe_sanguin']) . '</td>
            <td>' . htmlspecialchars($stagiaire['grade']) . '</td>
          </tr>';
$html .= '</table>';

// Contact Information
$html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['contact_information'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
$html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
            <th>' . $lang['phone'] . '</th>
            <th>' . $lang['email'] . '</th>
            <th>' . $lang['address'] . '</th>
          </tr>';
$html .= '<tr>
            <td>' . htmlspecialchars($stagiaire['telephone']) . '</td>
            <td>' . htmlspecialchars($stagiaire['email']) . '</td>
            <td>' . htmlspecialchars($stagiaire['adresse']) . '</td>
          </tr>';
$html .= '</table>';

// Training Details
$html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['internship_training_details'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
$html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
            <th>' . $lang['stage'] . '</th>
            <th>' . $lang['speciality'] . '</th>
            <th>' . $lang['date'] . '</th>
          </tr>';
$html .= '<tr>
            <td>' . htmlspecialchars($stagiaire['stage_name']) . '</td>
            <td>' . htmlspecialchars($stagiaire['specialite_name']) . '</td>
            <td>' . htmlspecialchars($stagiaire['date_inscription']) . '</td>
          </tr>';
$html .= '</table>';

// Course Info
$html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['course_program_info'] . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
$html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
            <th>' . $lang['stage'] . '</th>
            <th>' . $lang['start_date'] . '</th>
            <th>' . $lang['end_date'] . '</th>
            <th>' . $lang['speciality'] . '</th>
            <th>' . $lang['description'] . '</th>
          </tr>';
$html .= '<tr>
            <td>' . htmlspecialchars($stagiaire['stage_name']) . '</td>
            <td>' . htmlspecialchars($stagiaire['date_debut']) . '</td>
            <td>' . htmlspecialchars($stagiaire['date_fin']) . '</td>
            <td>' . htmlspecialchars($stagiaire['specialite_name']) . '</td>
            <td>' . htmlspecialchars($stagiaire['specialite_description']) . '</td>
          </tr>';
$html .= '</table>';

// Permissions
if (!empty($permissions)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['permissions'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['type'] . '</th>
                <th>' . $lang['start_date'] . '</th>
                <th>' . $lang['end_date'] . '</th>
                <th>' . $lang['status'] . '</th>
                <th>' . $lang['reason'] . '</th>
              </tr>';
    foreach ($permissions as $perm) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($perm['type']) . '</td>
                    <td>' . htmlspecialchars($perm['date_debut']) . '</td>
                    <td>' . htmlspecialchars($perm['date_fin']) . '</td>
                    <td>' . htmlspecialchars($perm['statut']) . '</td>
                    <td>' . htmlspecialchars($perm['motif']) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Consultations
if (!empty($consultations)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['consultations'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['date'] . '</th>
                <th>' . $lang['doctor'] . '</th>
                <th>' . $lang['diagnosis'] . '</th>
                <th>' . $lang['treatment'] . '</th>
              </tr>';
    foreach ($consultations as $cons) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($cons['date_consultation']) . '</td>
                    <td>' . htmlspecialchars($cons['docteur_nom'] . ' ' . $cons['docteur_prenom']) . '</td>
                    <td>' . htmlspecialchars($cons['diagnostic']) . '</td>
                    <td>' . htmlspecialchars($cons['traitement']) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Sanctions
if (!empty($punitions)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['sanctions'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['date'] . '</th>
                <th>' . $lang['type'] . '</th>
                <th>' . $lang['description'] . '</th>
                <th>' . $lang['responsible'] . '</th>
              </tr>';
    foreach ($punitions as $pun) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($pun['date_punition']) . '</td>
                    <td>' . htmlspecialchars($pun['type']) . '</td>
                    <td>' . htmlspecialchars($pun['description']) . '</td>
                    <td>' . htmlspecialchars($pun['auteur_nom'] . ' ' . $pun['auteur_prenom']) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Notes
if (!empty($remarques)) {
    $html .= '<h2 style="color:#2C5F2D; margin-top:20px;">' . $lang['notes'] . '</h2>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse;">';
    $html .= '<tr style="background-color:#0000FF; color:#FFF; font-weight:bold;">
                <th>' . $lang['date'] . '</th>
                <th>' . $lang['author'] . '</th>
                <th>' . $lang['note'] . '</th>
              </tr>';
    foreach ($remarques as $rem) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($rem['date_remarque']) . '</td>
                    <td>' . htmlspecialchars($rem['auteur_nom'] . ' ' . $rem['auteur_prenom']) . '</td>
                    <td>' . htmlspecialchars($rem['remarque']) . '</td>
                  </tr>';
    }
    $html .= '</table>';
}

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
ob_end_clean();
$pdf->Output('stagiaire_' . $stagiaire['id'] . '.pdf', 'I');
