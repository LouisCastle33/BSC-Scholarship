<?php
require_once 'dbConnect.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = "";
$user = $_SESSION['user'];
$school_id = $user['school_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    // Validate password
    if (!empty($new_password)) {
        if (strlen($new_password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        }
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE school_id = :school_id");
        $stmt->execute([
            'email' => $new_email,
            'password' => !empty($new_password) ? $hashedPassword : $user['password'],
            'school_id' => $school_id
        ]);
        
        $_SESSION['user']['email'] = $new_email;
        $success = "Account updated successfully. Redirecting to login page...";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function showSuccessModal() {
            alert("Account updated successfully! Redirecting to login page.");
            window.location.href = 'index.php';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Update Account</h2>
        <?php if (!empty($errors)) {
            echo '<div class="error">';
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo '</div>';
        } ?>
        
        <?php if ($success) {
            echo "<script>showSuccessModal();</script>";
        } ?>
        
        <form method="POST">
            <label for="email">New Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            
            <label for="password">New Password (leave blank to keep current):</label>
            <input type="password" name="password">
            
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password">
            
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
