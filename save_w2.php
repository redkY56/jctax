<?php
require 'config.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die('Unauthorized access. Please log in.');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employer_ein       = $_POST["employer_ein"];
    $wages              = $_POST["wages"];
    $fed_tax_withheld   = $_POST["fed_tax_withheld"];
    $state_tax_withheld = $_POST["state_tax_withheld"];
    $tax_year           = $_POST["tax_year"];

    $total_income       = $_POST["total_income"];
    $total_expenses     = $_POST["total_expenses"];
    $data_source        = $_POST["data_source"];
    $processed_at       = date("Y-m-d H:i:s");

    try {
        $pdo->beginTransaction();

        // Step 1: Insert into tax_returns
        $stmt0 = $pdo->prepare("
            INSERT INTO tax_returns (User_ID, tax_year, status, submitted_at, submission_type, is_complete)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt0->execute([$user_id, $tax_year, 'Draft', $processed_at, 'manual', 0]);
        $return_ID = $pdo->lastInsertId();

        // Step 2: Insert into w2_forms
        $stmt1 = $pdo->prepare("
            INSERT INTO w2_forms (return_ID, employer_ein, wages, fed_tax_withheld, state_tax_withheld, tax_year)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt1->execute([
            $return_ID,
            $employer_ein,
            $wages,
            $fed_tax_withheld,
            $state_tax_withheld,
            $tax_year
        ]);

        // Step 3: Insert into financial_data
        $stmt2 = $pdo->prepare("
            INSERT INTO financial_data (return_ID, total_income, total_expenses, data_source, processed_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->execute([
            $return_ID,
            $total_income,
            $total_expenses,
            $data_source,
            $processed_at
        ]);

        // Step 4: Correct Calculations
        $fed_tax_owed     = round($fed_tax_withheld * 0.80, 2);  // 80% of fed withheld
        $state_tax_owed   = round($state_tax_withheld * 0.80, 2); // 80% of state withheld
        $total_deductions = round($fed_tax_withheld + $state_tax_withheld, 2); // sum of raw withheld amounts
        $calculated_amt   = round($fed_tax_owed + $state_tax_owed, 2); // sum of owed taxes
        $final_amt        = round($total_deductions - $calculated_amt, 2); // total deductions - calculated_amt

        // Step 5: Insert into tax_calculation
        $stmt3 = $pdo->prepare("
            INSERT INTO tax_calculation (return_ID, fed_tax_owed, state_tax_owed, total_deductions, final_amt, calculated_amt)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt3->execute([
            $return_ID,
            $fed_tax_owed,
            $state_tax_owed,
            $total_deductions,
            $final_amt,
            $calculated_amt
        ]);

        $pdo->commit();

        // Redirect to calculator.php
        header("Location: calculator.php?return_id=" . $return_ID);
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error saving W‑2 data: " . $e->getMessage();
    }
}
?>
