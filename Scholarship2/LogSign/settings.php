<?php
session_start();
require 'dbConnect.php';

if (!isset($_SESSION['school_id'])) {
    header("Location: index.php");
    exit();
}

$school_id = $_SESSION['school_id']; // Get logged-in user's school_id

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Check if email is already used by another user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND school_id != ?");
    $stmt->execute([$email, $school_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['errors']['email'] = "Email is already taken!";
        header("Location: userdash.php");
        exit();
    }

    // Update user information
    if ($password) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE school_id = ?");
        $stmt->execute([$name, $email, $password, $school_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE school_id = ?");
        $stmt->execute([$name, $email, $school_id]);
    }

    session_destroy(); // Log out user after update
    header("Location: index.php"); // Redirect to login page
    exit();
}
?>
