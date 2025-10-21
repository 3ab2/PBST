<?php
require '../functions.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT id, intitule FROM stages ORDER BY intitule");
    $stmt->execute();
    $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($stages);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
