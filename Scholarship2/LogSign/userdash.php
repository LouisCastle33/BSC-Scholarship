<?php
session_start();
require 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['school_id']; // Store user ID
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: userdash.php");
        exit();
    } else {
        $_SESSION['errors']['login'] = "Invalid email or password!";
        header("Location: index.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="styles.css">

	<title>User Dashboard</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">Student Dashboard</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#" id="openSettingsModal">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="index.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="#" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				<a href="#" class="btn-scholarship" id="openModal">
					<i class='bx bxs-graduation'></i>
					<span>Apply for Scholarship</span>
				</a>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3>1020</h3>
						<p>New Order</p>
					</span>
				</li>
			</ul>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<!-- SCHOLARSHIP APPLICATION MODAL -->
	<div id="scholarshipModal" class="modal">
	    <div class="modal-content">
	        <span class="close" id="closeModal">&times;</span>
	        <h2>Scholarship Application</h2>
	        <form action="apply_scholarship.php" method="POST">
	            <label for="full_name">Full Name:</label>
	            <input type="text" id="full_name" name="full_name" required>

	            <label for="student_id">Student ID:</label>
	            <input type="text" id="student_id" name="student_id" required>

	            <label for="email">Email:</label>
	            <input type="email" id="email" name="email" required>

	            <label for="reason">Why do you need this scholarship?</label>
	            <textarea id="reason" name="reason" required></textarea>

	            <button type="submit">Submit Application</button>
	        </form>
	    </div>
	</div>

	<!-- SETTINGS MODAL -->
<!-- SETTINGS MODAL -->
<div id="settingsModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeSettingsModal">&times;</span>
        <h2>Update Account</h2>
        <form action="update_account.php" method="POST">
            <label for="email">New Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Update Account</button>
        </form>
    </div>
</div>


	<!-- STYLES -->
	<style>
	    .modal {
	        display: none;
	        position: fixed;
	        z-index: 1000;
	        left: 0;
	        top: 0;
	        width: 100%;
	        height: 100%;
	        background-color: rgba(0, 0, 0, 0.5);
	        display: flex;
	        justify-content: center;
	        align-items: center;
	    }
	    .modal-content {
	        background: #fff;
	        padding: 20px;
	        border-radius: 10px;
	        width: 400px;
	        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
	    }
	    .close {
	        float: right;
	        font-size: 24px;
	        cursor: pointer;
	    }
	    input, textarea {
	        width: 100%;
	        padding: 8px;
	        margin-top: 5px;
	        border: 1px solid #ccc;
	        border-radius: 5px;
	    }
	    button {
	        margin-top: 10px;
	        padding: 10px;
	        background-color: #28a745;
	        color: white;
	        border: none;
	        cursor: pointer;
	        width: 100%;
	        border-radius: 5px;
	    }
	</style>

	<!-- JAVASCRIPT -->
	<script>
    document.addEventListener("DOMContentLoaded", function () {
        var settingsModal = document.getElementById("settingsModal");
        var openSettingsBtn = document.getElementById("openSettingsModal");
        var closeSettingsBtn = document.getElementById("closeSettingsModal");

        // Ensure modal is hidden when page loads
        settingsModal.style.display = "none";

        // Open modal only when button is clicked
        openSettingsBtn.addEventListener("click", function(event) {
            event.preventDefault();
            settingsModal.style.display = "flex"; // Show modal
        });

        // Close modal when clicking the close button (X)
        closeSettingsBtn.addEventListener("click", function() {
            settingsModal.style.display = "none"; // Hide modal
        });

        // Close modal when clicking outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target === settingsModal) {
                settingsModal.style.display = "none"; // Hide modal
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById("scholarshipModal");
        var openModalBtn = document.getElementById("openModal");
        var closeModalBtn = document.getElementById("closeModal");

        // Ensure modal is hidden when page loads
        modal.style.display = "none";

        // Open modal only when button is clicked
        openModalBtn.addEventListener("click", function(event) {
            event.preventDefault();
            modal.style.display = "flex"; // Show modal
        });

        // Close modal when clicking the close button (X)
        closeModalBtn.addEventListener("click", function() {
            modal.style.display = "none"; // Hide modal
        });

        // Close modal when clicking outside the modal content
        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                modal.style.display = "none"; // Hide modal
            }
        });
    });
</script>
	<script src="scripts.js"></script>
</body>
</html>
