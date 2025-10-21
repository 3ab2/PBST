<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM observations");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['total' => $result['total']]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
