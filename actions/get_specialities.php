<?php
require '../functions.php';

header('Content-Type: application/json');

try {

    $stmt = $pdo->prepare("SELECT id, nom_specialite FROM specialites ORDER BY nom_specialite");
    $stmt->execute();
    $specialities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($specialities);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
