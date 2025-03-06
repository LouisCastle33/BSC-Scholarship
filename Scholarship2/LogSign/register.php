<?php
session_start();
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>

        <?php
        if (isset($errors['user_exist'])) {
            echo '<div class="error-main">
                    <p>' . $errors['user_exist'] . '</p>
                  </div>';
            unset($errors['user_exist']);
        }
        ?>

        <form method="POST" action="user-account.php">
            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="school_id" id="school_id" placeholder="School ID (e.g., 2020-1080-AB)" required pattern="\d{4}-\d{4}-[A-Z]{2}">
                <?php
                if (isset($errors['school_id'])) {
                    echo '<div class="error">
                            <p>' . $errors['school_id'] . '</p>
                          </div>';
                    unset($errors['school_id']);
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Full Name" required>
                <?php
                if (isset($errors['name'])) {
                    echo '<div class="error">
                            <p>' . $errors['name'] . '</p>
                          </div>';
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="address" id="address" placeholder="Address" required>
                <?php
                if (isset($errors['address'])) {
                    echo '<div class="error">
                            <p>' . $errors['address'] . '</p>
                          </div>';
                    unset($errors['address']);
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number" required pattern="09[0-9]{9}">
                <?php
                if (isset($errors['contact_number'])) {
                    echo '<div class="error">
                            <p>' . $errors['contact_number'] . '</p>
                          </div>';
                    unset($errors['contact_number']);
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <?php
                if (isset($errors['email'])) {
                    echo '<div class="error">
                            <p>' . $errors['email'] . '</p>
                          </div>';
                    unset($errors['email']);
                }
                ?>
            </div>

            <div class="input-group password">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <i id="eye" class="fa fa-eye"></i>
                <?php
                if (isset($errors['password'])) {
                    echo '<div class="error">
                            <p>' . $errors['password'] . '</p>
                          </div>';
                    unset($errors['password']);
                }
                ?>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <?php
                if (isset($errors['confirm_password'])) {
                    echo '<div class="error">
                            <p>' . $errors['confirm_password'] . '</p>
                          </div>';
                    unset($errors['confirm_password']);
                }
                ?>
            </div>

            <input type="submit" class="btn" value="Sign Up" name="signup">
        </form>

        <div class="links">
            <p>Already Have an Account?</p>
            <a href="index.php">Sign In</a>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>

</html>

<?php
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>
