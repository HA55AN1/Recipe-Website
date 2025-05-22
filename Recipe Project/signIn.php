<?php 
include 'connection.php'; 
session_start(); 

// Redirect if already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

// Handle login form submission
if (isset($_POST['submit-btn'])) {
    // Trim and sanitize inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = trim($email);
    $password = trim($_POST['password']);

    // Validate non-empty inputs
    if (!empty($email) && !empty($password)) {
        // Prepare SQL query to avoid SQL injection
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE userEmail = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if user found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $row['userPassword'])) {
                session_regenerate_id(true); // Prevent session fixation

                // Set session variables
                if ($row['userType'] === 'admin') {
                    $_SESSION['admin_name'] = $row['userName'];
                    $_SESSION['admin_email'] = $row['userEmail'];
                    $_SESSION['admin_id'] = $row['userId'];
                    header('Location: adminIndex.php');
                    exit();
                } else {
                    $_SESSION['user_name'] = $row['userName'];
                    $_SESSION['user_email'] = $row['userEmail'];
                    $_SESSION['user_id'] = $row['userId'];
                    header('Location: index.php');
                    exit();
                }
            } else {
                $message[] = 'Incorrect password';
            }
        } else {
            $message[] = 'User not found';
        }

        $stmt->close();
    } else {
        $message[] = 'Please fill in all fields';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>

<!-- Include header -->
<?php include 'header.php'; ?>

<!-- Login form section -->
<section class="form-container">
    <?php 
    // Show error or success messages
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
                <div class="message">
                    <span>' . $msg . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div>
            ';
        }
    }
    ?>

    <form method="post">
        <h1>Login Now</h1>

        <div class="input-field">
            <label>Your Email</label><br>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>

        <div class="input-field">
            <label>Your Password</label><br>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <input type="submit" name="submit-btn" value="Login Now" class="contact-btn">
        <p>Don't have an account? <a href="signUp.php">Register now</a></p>
    </form>
</section>

<!-- Footer section -->
<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="script.js"></script>

</body>
</html>
