<?php
// Cron script to compute monthly instructor stats
// Run on 1st of each month at 00:05

require 'config.php';

$year = date('Y');
$month = date('m');

// Compute for previous month
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month == 0) {
    $prev_month = 12;
    $prev_year = $year - 1;
}

try {
    $pdo->beginTransaction();

    // Aggregate observations
    $stmt = $pdo->prepare("
        INSERT INTO monthly_instructor_stats (instructor_id, year, month, positive_count, negative_count, total, positive_ratio)
        SELECT i.id_instructor, ? AS year, ? AS month,
         SUM(CASE WHEN o.rating='positive' THEN 1 ELSE 0 END) AS positive_count,
         SUM(CASE WHEN o.rating='negative' THEN 1 ELSE 0 END) AS negative_count,
         COUNT(o.id_observation) AS total,
         CASE WHEN COUNT(o.id_observation) = 0 THEN 0 ELSE SUM(CASE WHEN o.rating='positive' THEN 1 ELSE 0 END) / COUNT(o.id_observation) END AS positive_ratio
        FROM instructors i
        LEFT JOIN observations o ON o.instructor_id = i.id_instructor AND YEAR(o.obs_date) = ? AND MONTH(o.obs_date) = ?
        GROUP BY i.id_instructor
        ON DUPLICATE KEY UPDATE positive_count=VALUES(positive_count), negative_count=VALUES(negative_count), total=VALUES(total), positive_ratio=VALUES(positive_ratio), computed_at=NOW()
    ");
    $stmt->execute([$prev_year, $prev_month, $prev_year, $prev_month]);

    $pdo->commit();
    echo "Monthly stats computed for $prev_year-$prev_month\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
?>
