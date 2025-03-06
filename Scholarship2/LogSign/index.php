<?php
require_once 'dbConnect.php';

session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $school_id = trim($_POST['school_id']);
    $address = trim($_POST['address']);
    $contact_number = trim($_POST['contact_number']);
    $created_at = date('Y-m-d H:i:s');

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // Validate School ID format (e.g., 2020-1080-AB)
    if (!preg_match('/^\d{4}-\d{4}-[A-Z]{2}$/', $school_id)) {
        $errors['school_id'] = 'Invalid School ID format (e.g., 2020-1080-AB)';
    }

    // Validate contact number format (Philippines format 09xxxxxxxxx)
    if (!preg_match('/^09\d{9}$/', $contact_number)) {
        $errors['contact_number'] = 'Invalid Contact Number (should start with 09 and be 11 digits long)';
    }

    // Check if email or school ID is already registered
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR school_id = :school_id");
    $stmt->execute(['email' => $email, 'school_id' => $school_id]);
    if ($stmt->fetch()) {
        $errors['user_exist'] = 'Email or School ID is already registered';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: register.php');
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password, name, school_id, address, contact_number, created_at) 
                            VALUES (:email, :password, :name, :school_id, :address, :contact_number, :created_at)");
    $stmt->execute([
        'email' => $email,
        'password' => $hashedPassword,
        'name' => $name,
        'school_id' => $school_id,
        'address' => $address,
        'contact_number' => $contact_number,
        'created_at' => $created_at
    ]);

    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signin'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password cannot be empty';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'school_id' => $user['school_id'],
            'address' => $user['address'],
            'contact_number' => $user['contact_number'],
            'created_at' => $user['created_at']
        ];

        header('Location: userdash.php');
        exit();
    } else {
        $errors['login'] = 'Invalid email or password';
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register & Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1>
    <?php
    if (isset($errors['login'])) {
      echo '<div class="error-main">
                    <p>' . $errors['login'] . '</p>
                    </div>';
      unset($errors['login']);
    }
    ?>
    <form method="POST" action="userdash.php">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <?php
        if (isset($errors['email'])) {
          echo ' <div class="error">
                    <p>' . $errors['email'] . '</p>
                </div>';
        }
        ?>
      </div>
      <div class="input-group password">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i id="eye" class="fa fa-eye"></i>
        <?php
        if (isset($errors['password'])) {
          echo ' <div class="error">
                    <p>' . $errors['password'] . '</p>
                </div>';
        }
        ?>
      </div>
      <p class="recover">
        <a href="#">Recover Password</a>
      </p>
      <input type="submit" class="btn" value="Sign In" name="signin">
    </form>
 
    <div class="links">
      <p>Don't have account yet?</p>
      <a href="register.php">Sign Up</a>
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