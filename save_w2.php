<?php
require 'config.php';
session_start();

// Simulate a logged-in user (replace this with real session)
$user_id = $_SESSION["user_id"] ?? null;
if (!$user_id) {
    die("Unauthorized: No user ID in session.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get W-2 and Financial Data inputs
    $employer_ein       = $_POST["employer_ein"];
    $wages              = $_POST["wages"];
    $fed_tax_withheld   = $_POST["fed_tax_withheld"];
    $state_tax_withheld = $_POST["state_tax_withheld"];
    $tax_year           = $_POST["tax_year"];

    $total_income   = $_POST["total_income"];
    $total_expenses = $_POST["total_expenses"];
    $data_source    = $_POST["data_source"];
    $now            = date("Y-m-d H:i:s");

    try {
        $pdo->beginTransaction();

        // Step 1: Insert into tax_returns and get return_ID
        $stmt0 = $pdo->prepare("
            INSERT INTO tax_returns (User_ID, tax_year, status, submitted_at, submission_type, is_complete)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt0->execute([$user_id, $tax_year, 'Draft', $now, 'manual', 0]);
        $return_ID = $pdo->lastInsertId();

        // Step 2: Insert into w2_forms
        $stmt1 = $pdo->prepare("
            INSERT INTO w2_forms (return_ID, employer_ein, wages, fed_tax_withheld, state_tax_withheld, tax_year)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt1->execute([$return_ID, $employer_ein, $wages, $fed_tax_withheld, $state_tax_withheld, $tax_year]);

        // Step 3: Insert into financial_data
        $stmt2 = $pdo->prepare("
            INSERT INTO financial_data (return_ID, total_income, total_expenses, data_source, processed_at)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->execute([$return_ID, $total_income, $total_expenses, $data_source, $now]);

        $pdo->commit();

        $_SESSION["return_ID"] = $return_ID;
        header("Location: calculator.html");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error saving data: " . $e->getMessage();
    }
}
?>
