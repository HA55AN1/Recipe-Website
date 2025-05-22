<?php 
include 'connection.php'; 
session_start();

if (isset($_POST['submit-btn'])) {
    // Sanitize & trim inputs
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = trim($email);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email format';
    }
    // Validate password strength
    elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password)) {
        $message[] = 'Password must be at least 8 characters and include at least one uppercase letter and one number';
    }
    // Check password confirmation
    elseif ($password !== $cpassword) {
        $message[] = 'Passwords do not match';
    } else {
        // Check for existing user
        $stmt = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'User already exists';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO users (userName, userEmail, userPassword) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($insert_stmt->execute()) {
                $_SESSION['success'] = 'Registered successfully. You can now log in.';
                header('Location: signIn.php');
                exit();
            } else {
                $message[] = 'Registration failed, try again later';
            }

            $insert_stmt->close();
        }

        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
	
<!-- Include header -->
<?php include 'header.php'; ?>

<!-- Sign Up Form Section -->
<section class="form-container">
    <?php 
    // Show feedback messages
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
                <div class="message">
                    <span>' . $message . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>
            ';
        }
    }
    ?>
    
    <form method="post">
        <h1>Register Now</h1>

        <!-- Name Input -->
        <div class="input-field">
            <label>Your Name</label><br>
            <input type="text" name="name" placeholder="Enter your name" required>
        </div>

        <!-- Email Input -->
        <div class="input-field">
            <label>Your Email</label><br>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <!-- Password Input -->
        <div class="input-field">
            <label>Your Password</label><br>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <!-- Confirm Password Input -->
        <div class="input-field">
            <label>Re-enter Your Password</label><br>
            <input type="password" name="cpassword" placeholder="Confirm your password" required>
        </div>

        <!-- Submit Button -->
        <input type="submit" name="submit-btn" value="Register Now" class="contact-btn">

        <!-- Redirect to Login -->
        <p>Already have an account? <a href="signIn.php">Login now</a></p>
    </form>
</section>

<!-- Footer Section -->
<?php include 'footer.php'; ?>
<!-- Scripts -->
<script src="script.js"></script>

</body>
</html>
