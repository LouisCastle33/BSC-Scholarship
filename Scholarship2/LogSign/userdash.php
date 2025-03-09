<?php
session_start();
require 'dbConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['errors']['email'] = "Email already exists!";
        header("Location: register.php");
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $password])) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['errors']['register'] = "Registration failed!";
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
        <h2>UniFAST-TDP Scholarship Application</h2>
        <form action="apply_scholarship.php" method="POST" enctype="multipart/form-data">
            <!-- Personal Information -->
            <h3>Personal Information</h3>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name">

            <label for="maiden_name">Maiden Name (For Married Women):</label>
            <input type="text" id="maiden_name" name="maiden_name">

            <label for="dob">Date of Birth:</label>
            <input type="date" id="dob" name="dob" required>

            <label for="place_of_birth">Place of Birth:</label>
            <input type="text" id="place_of_birth" name="place_of_birth" required>

            <label for="address">Permanent Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="zip_code">Zip Code:</label>
            <input type="text" id="zip_code" name="zip_code" required>

            <!-- School Information -->
            <h3>School Information</h3>

            <label for="school_name">Name of School Attended:</label>
            <input type="text" id="school_name" name="school_name" required>

            <label for="school_id">School ID Number:</label>
            <input type="text" id="school_id" name="school_id" required>

            <label for="school_address">School Address:</label>
            <textarea id="school_address" name="school_address" required></textarea>

            <label for="school_sector">School Sector:</label>
            <select id="school_sector" name="school_sector" required>
                <option value="">-- Select --</option>
                <option value="Public">Public</option>
                <option value="Private">Private</option>
            </select>

            <label for="year_level">Year Level:</label>
            <select id="year_level" name="year_level" required>
                <option value="">-- Select --</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>

            <!-- Contact Information -->
            <h3>Contact Information</h3>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <!-- Family Background -->
            <h3>Family Background</h3>

            <label for="father_name">Father's Name:</label>
            <input type="text" id="father_name" name="father_name">

            <label for="mother_name">Mother's Name:</label>
            <input type="text" id="mother_name" name="mother_name">

            <label for="parents_income">Total Parents' Gross Income:</label>
            <input type="text" id="parents_income" name="parents_income" required>

            <label for="siblings">Number of Siblings in the Family:</label>
            <input type="number" id="siblings" name="siblings" required>

            <!-- Additional Information -->
            <h3>Additional Information</h3>

            <label for="financial_assistance">Are you enjoying other educational financial assistance?</label>
            <select id="financial_assistance" name="financial_assistance" required>
                <option value="">-- Select --</option>
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <label for="financial_assistance_details">If yes, please specify:</label>
            <input type="text" id="financial_assistance_details" name="financial_assistance_details">

            <!-- Upload Required Documents -->
            <h3>Upload Required Documents</h3>

            <label for="id_picture">2x2 ID Picture:</label>
            <input type="file" id="id_picture" name="id_picture" accept=".jpg,.png,.pdf" required>

            <label for="cor">Certificate of Registration/Enrollment (COR/COE):</label>
            <input type="file" id="cor" name="cor" accept=".pdf,.jpg,.png" required>

            <label for="indigency">Certificate of Indigency:</label>
            <input type="file" id="indigency" name="indigency" accept=".pdf,.jpg,.png" required>

            <!-- Submit Button -->
            <button type="submit">Submit Application</button>
        </form>
    </div>
</div>
    


	<!-- SETTINGS MODAL -->
	<div id="settingsModal" class="modal">
	    <div class="modal-content">
	        <span class="close" id="closeSettingsModal">&times;</span>
	        <h2>Settings</h2>
	        <form action="settings.php" method="POST">
	            <label for="username">Username:</label>
	            <input type="text" id="username" name="username" required>

	            <label for="email">Email:</label>
	            <input type="email" id="email" name="email" required>

	            <label for="password">New Password:</label>
	            <input type="password" id="password" name="password">

	            <button type="submit">Save Changes</button>
	        </form>
	    </div>
	</div>

	<!-- STYLES -->
	<style>
/* Modal Styling */
/* Modal Background */
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

/* Modal Content (with scrolling enabled) */
.modal-content {
    background: #fff;
    padding: 20px;
    width: 50%;
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: fadeIn 0.3s ease-in-out;
    
    /* Makes the modal scrollable */
    max-height: 80vh;
    overflow-y: auto;
}

/* Scrollbar Styling (for better UI) */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Modal Close Button */
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 22px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

.close:hover {
    color: red;
}

/* Form Styling */
.modal-content h2 {
    text-align: center;
    margin-bottom: 15px;
    font-size: 22px;
    color: #333;
}

.modal-content h3 {
    margin-top: 20px;
    font-size: 18px;
    color: #555;
    border-bottom: 2px solid #ddd;
    padding-bottom: 5px;
}

label {
    font-size: 14px;
    font-weight: 600;
    color: #444;
    display: block;
    margin-top: 10px;
}

input, textarea, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

textarea {
    resize: none;
    height: 60px;
}

button {
    display: block;
    width: 100%;
    background: #4CAF50;
    color: white;
    padding: 10px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    margin-top: 20px;
    cursor: pointer;
}

button:hover {
    background: #45a049;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-content {
        width: 90%;
        max-height: 90vh;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
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
