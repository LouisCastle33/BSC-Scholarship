<?php
session_start();
require 'dbConnect.php'; // Ensure this connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $year_level = trim($_POST['year_level']);
    
    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["documents"]["name"]);
    move_uploaded_file($_FILES["documents"]["tmp_name"], $target_file);

    // Insert into the database
    $stmt = $pdo->prepare("INSERT INTO scholarship_applications (fullname, student_id, email, course, year_level, documents) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$fullname, $student_id, $email, $course, $year_level, $target_file])) {
        $_SESSION['success'] = "Application submitted successfully!";
    } else {
        $_SESSION['errors']['application'] = "Failed to submit application.";
    }

    header("Location: index.php");
    exit();
}
?>
