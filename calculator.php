<?php
require 'config.php';
session_start();

// Fetch calculation if return_id is passed
$calculation = null;

if (isset($_GET['return_id'])) {
    $return_ID = intval($_GET['return_id']);

    $stmt = $pdo->prepare("SELECT * FROM tax_calculation WHERE return_ID = ? ORDER BY calc_id DESC LIMIT 1");
    $stmt->execute([$return_ID]);
    $calculation = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>JC‑TAX – Calculator</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    :root {
      --bg-main:#c8ffd0;
      --sidebar:#b8ffcc;
      --panel:#dcffd5;
      --jc-gold:#c9bb8e;
      --jc-blue:#0d6efd;
    }
    body { background: var(--bg-main); }

    .sidebar {
      background: var(--sidebar);
      min-height: 100vh;
      padding: 20px;
    }
    .sidebar-item {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }
    .jc-logo {
      color: var(--jc-gold);
      font-size: 2.5rem;
      font-weight: 700;
      text-decoration: none;
    }
    .panel {
      background: var(--panel);
      padding: 30px;
      border-radius: 20px;
    }
    .btn-submit, .btn-previous {
      border-radius: 20px;
      font-weight: bold;
      width: 100%;
    }
    .btn-submit {
      background-color: var(--jc-blue);
      color: white;
    }
    .btn-previous {
      background-color: grey;
      color: white;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-3 sidebar">
      <div class="d-flex flex-column align-items-center mb-4">
        <a href="welcome.html" class="jc-logo">JC</a>
      </div>
      <a href="w2.html" class="sidebar-item d-block text-decoration-none text-dark">W‑2 Form</a>
      <a href="calculator.php" class="sidebar-item d-block text-decoration-none text-dark">Calculator</a>
      <a href="IRSsub.html"   class="sidebar-item d-block text-decoration-none text-dark">SUBMIT TO IRS</a>
      </div>

    <!-- Main Content -->
    <div class="col-9 p-4">
      <h1>JC‑TAX Calculator</h1>

      <?php if ($calculation): ?>
        <div class="alert alert-success" role="alert">
          Tax calculation completed successfully!
        </div>

        <div class="panel mt-4">
          <h4 class="text-center mb-4">Calculation Results</h4>
          <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Federal Tax Owed:</strong> $<?php echo number_format($calculation['fed_tax_owed'], 2); ?></li>
            <li class="list-group-item"><strong>State Tax Owed:</strong> $<?php echo number_format($calculation['state_tax_owed'], 2); ?></li>
            <li class="list-group-item"><strong>Total Deductions:</strong> $<?php echo number_format($calculation['total_deductions'], 2); ?></li>
            <li class="list-group-item"><strong>Calculated Amount:</strong> $<?php echo number_format($calculation['calculated_amt'], 2); ?></li>
            <li class="list-group-item"><strong>Final Amount (After Deductions):</strong> $<?php echo number_format($calculation['final_amt'], 2); ?></li>
          </ul>
        </div>
      <?php else: ?>
        <div class="alert alert-warning" role="alert">
          No calculation found. Please complete your W‑2 form first.
        </div>
      <?php endif; ?>

      <!-- Navigation Buttons -->
      <div class="panel mt-4">
        <div class="row">
          <div class="col-md-6">
            <a href="w2.html" class="btn btn-previous">Previous</a>
          </div>
          <div class="col-md-6">
            <a href="IRSsub.html" class="btn btn-submit">Next</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
</body>
</html>


