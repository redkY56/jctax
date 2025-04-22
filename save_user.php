<?php
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST["email"];
    $name     = $_POST["name"];
    $password = $_POST["password"];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO user (email, name, password) VALUES (?, ?, ?)");
        $stmt->execute([$email, $name, $hashedPassword]);

        // Store user_id in session
        $_SESSION["user_id"] = $pdo->lastInsertId();

        // Redirect to next page
        header("Location: w2.html");
        exit();
    } catch (PDOException $e) {
        echo "Error saving user: " . $e->getMessage();
    }
}
?>
