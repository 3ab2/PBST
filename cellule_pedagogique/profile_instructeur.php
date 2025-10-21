<?php
require '../functions.php';
check_role(['cellule_pedagogique', 'admin']);
$instructor_id = $_GET['id'] ?? null;
if (!$instructor_id) {
    header('Location: stats.php');
    exit;
}

$stmt = $pdo->prepare("SELECT i.*, s.nom_specialite FROM instructors i LEFT JOIN specialites s ON i.speciality_id = s.id WHERE i.id_instructor = ?");
$stmt->execute([$instructor_id]);
$instructor = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$instructor) {
    header('Location: stats.php');
    exit;
}

// Monthly stats (last 12 months)
$statsStmt = $pdo->prepare("SELECT year, month, positive_count, negative_count, total, positive_ratio FROM monthly_instructor_stats WHERE instructor_id = ? ORDER BY year DESC, month DESC LIMIT 12");
$statsStmt->execute([$instructor_id]);
$monthlyStats = $statsStmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Instructor Profile';
?>
<link rel="icon" type="image/svg+xml" href="../images/bst.png">
<?php include '../templates/header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="breadcrumb-header"><?php echo htmlspecialchars($translations['breadcrumb_cellule_pedagogique']); ?> <span>></span> <?php echo htmlspecialchars($translations['instructor']); ?> <span>></span> <?php echo htmlspecialchars($translations['profile'] ?? 'Profile'); ?></div>
    <a class="btn btn-success" href="../profile_instructeur_pdf.php?instructor_id=<?php echo urlencode($instructor_id); ?>" target="_blank" title="Download PDF"><i class="bi bi-filetype-pdf me-1"></i><?php echo htmlspecialchars($translations['download'] ?? 'Download'); ?></a>
  </div>

  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
          <h5 class="mb-0"><i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($translations['instructor']); ?></h5>
        </div>
        <div class="card-body">
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['name'] ?? 'Name'); ?>:</strong> <?php echo htmlspecialchars($instructor['first_name'] . ' ' . $instructor['last_name']); ?></p>
          <p class="mb-1"><strong>CINE:</strong> <?php echo htmlspecialchars($instructor['cine']); ?></p>
          <p class="mb-1"><strong>MLE:</strong> <?php echo htmlspecialchars($instructor['mle']); ?></p>
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['email'] ?? 'Email'); ?>:</strong> <?php echo htmlspecialchars($instructor['email'] ?? '-'); ?></p>
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['phone'] ?? 'Phone'); ?>:</strong> <?php echo htmlspecialchars($instructor['phone'] ?? '-'); ?></p>
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['specialty'] ?? 'Specialty'); ?>:</strong> <?php echo htmlspecialchars($instructor['nom_specialite'] ?? '-'); ?></p>
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['status'] ?? 'Status'); ?>:</strong> <?php echo $instructor['is_active'] ? 'Active' : 'Inactive'; ?></p>
          <p class="mb-1"><strong><?php echo htmlspecialchars($translations['bio'] ?? 'Bio'); ?>:</strong> <?php echo htmlspecialchars($instructor['bio'] ?? '-'); ?></p>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
          <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> <?php echo htmlspecialchars($translations['monthly_rankings']); ?></h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead class="table-dark">
                <tr>
                  <th><?php echo htmlspecialchars($translations['year']); ?></th>
                  <th><?php echo htmlspecialchars($translations['month']); ?></th>
                  <th><?php echo htmlspecialchars($translations['positive']); ?></th>
                  <th><?php echo htmlspecialchars($translations['negative']); ?></th>
                  <th><?php echo htmlspecialchars($translations['total']); ?></th>
                  <th><?php echo htmlspecialchars($translations['average_score']); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php
                $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'];
                foreach ($monthlyStats as $s):
                  $avg = isset($s['positive_ratio']) ? number_format($s['positive_ratio'] * 100, 1) . '%' : 'N/A';
              ?>
                <tr>
                  <td><?php echo (int)$s['year']; ?></td>
                  <td><?php echo htmlspecialchars($months[(int)$s['month']] ?? (string)$s['month']); ?></td>
                  <td><?php echo (int)$s['positive_count']; ?></td>
                  <td><?php echo (int)$s['negative_count']; ?></td>
                  <td><?php echo (int)$s['total']; ?></td>
                  <td><?php echo htmlspecialchars($avg); ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="card mt-3" style="border: 2px solid #8B7355; border-bottom: 3px solid #6B5B47; background: linear-gradient(to bottom, #F5F5DC 0%, #FFFFFF 100%);">
        <div class="card-header" style="background-color: #8B7355; color: white;">
          <h5 class="mb-0"><i class="bi bi-eye"></i> <?php echo htmlspecialchars($translations['observations']); ?></h5>
        </div>
        <div class="card-body">
          <div id="observations-container"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const instructorId = <?php echo json_encode((int)$instructor_id); ?>;

  // Load subjects taught (via existing endpoint)
  fetch(`../actions/get_instructor_subjects.php?instructor_id=${instructorId}`)
    .then(r => r.json())
    .then(list => {
      const ul = document.getElementById('subjects-list');
      ul.innerHTML = '';
      if (!Array.isArray(list) || list.length === 0) {
        ul.innerHTML = '<li>-</li>';
        return;
      }
      list.forEach(s => {
        const li = document.createElement('li');
        li.textContent = s.name;
        ul.appendChild(li);
      });
    });

  // Load all observations of this instructor
  fetch(`../actions/get_observations.php?instructor_id=${instructorId}`)
    .then(r => r.json())
    .then(rows => {
      const cont = document.getElementById('observations-container');
      cont.innerHTML = '';
      if (!Array.isArray(rows) || rows.length === 0) {
        cont.innerHTML = '<div class="text-muted">No observations yet.</div>';
        return;
      }
      const table = document.createElement('table');
      table.className = 'table table-striped table-hover';
      table.innerHTML = `
        <thead class="table-dark">
          <tr>
            <th><?php echo htmlspecialchars($translations['subject']); ?></th>
            <th><?php echo htmlspecialchars($translations['date']); ?></th>
            <th><?php echo htmlspecialchars($translations['time']); ?></th>
            <th><?php echo htmlspecialchars($translations['rating']); ?></th>
            <th><?php echo htmlspecialchars($translations['score']); ?></th>
            <th><?php echo htmlspecialchars($translations['comment']); ?></th>
          </tr>
        </thead>
        <tbody></tbody>
      `;
      const tbody = table.querySelector('tbody');
      rows.forEach(o => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${o.subject_name}</td>
          <td>${o.obs_date}</td>
          <td>${o.obs_time}</td>
          <td>${o.rating}</td>
          <td>${o.score || '-'}</td>
          <td>${o.comment || ''}</td>
        `;
        tbody.appendChild(tr);
      });
      cont.appendChild(table);
    });
});
</script>

<?php include '../templates/footer.php'; ?>
