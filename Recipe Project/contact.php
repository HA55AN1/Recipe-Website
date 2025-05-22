<?php 
include 'connection.php'; // Include DB connection
session_start(); // Start session

// Retrieve logged-in user ID if available (user or admin)
$user_id = $_SESSION['user_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;
$LoggedIn = $user_id ?? $admin_id;

// Handle logout request
if (isset($_POST['logout'])) {
    session_destroy();  // Clear session data
    header("location: signIn.php"); // Redirect to login
    exit;
}

// Fetch user data from database if logged in
if ($LoggedIn) {
    $user_query = $conn->prepare("SELECT * FROM users WHERE userId = ?");
    $user_query->bind_param("i", $LoggedIn);
    $user_query->execute();
    $user_data = $user_query->get_result()->fetch_assoc();
}

// Handle contact form submission
if (isset($_POST['submit-btn'])) {
    // Sanitize and validate inputs
    $name = trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $content = trim(htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8'));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = "Invalid email format.";
    } 
    // Ensure required fields are not empty
    elseif (empty($name) || empty($content)) {
        $message[] = "All required fields must be filled.";
    } 
    else {
        // If user is not logged in, mark message as guest
        if (!$LoggedIn) {
            $name .= " (guest)";
            $LoggedIn = 0; // Set to 0 to indicate guest submission
        }

        // Check for duplicate messages to prevent spam
        $check_stmt = $conn->prepare("SELECT * FROM message WHERE userName = ? AND userEmail = ? AND messageContent = ?");
        $check_stmt->bind_param("sss", $name, $email, $content);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'Message already sent.';
        } else {
            // Insert the new message securely using prepared statement
            $insert_stmt = $conn->prepare("INSERT INTO message (userId, userName, userEmail, messageContent) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("isss", $LoggedIn, $name, $email, $content);
            if ($insert_stmt->execute()) {
                $message[] = 'Message sent successfully!';
            } else {
                $message[] = 'Failed to send message. Please try again later.';
            }
            $insert_stmt->close();
        }

        $check_stmt->close(); // Clean up
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css" />
    <title>Contact Us</title>  
</head>
<body>

<?php include 'header.php'; ?> 

<div class="container">
    <h1>Get in Touch</h1>
    <p>Fill up the form to get in touch with us. </p>

 <!-- Contact Form Messages -->
<?php 
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
            <div class="message">
                <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
        ';
    }
}
?>

<!-- Contact Form Section -->
<div class="contact-box">
    <div class="container-left">
        <h3>Fill the Form*</h3>
        <form id="contactForm" method="POST" action="">

            <!-- Name Field (Pre-filled if logged in) -->
            <div class="input-row">
                <div class="input-group">
                    <label>User Name:*</label>
                    <input 
                        type="text" 
                        name="name" 
                        autocomplete="name" 
                        placeholder="Enter your First Name" 
                        required
                        value="<?= isset($user_data['userName']) ? htmlspecialchars($user_data['userName'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    >
                </div>

                <!-- OPTIONAL Last Name Field (currently unused in backend) -->
                <div class="input-group">
                    <label>Name: (optional)</label>
                    <input 
                        type="text" 
                        id="lastname" 
                        autocomplete="family-name" 
                        placeholder="Enter your Full Name"
                        value="<?= isset($user_data['userLastName']) ? htmlspecialchars($user_data['userLastName'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    >
                </div>
            </div>

            <!-- Email Field -->
            <div class="input-row">
                <div class="input-group">
                    <label>Email:*</label>
                    <input 
                        type="email" 
                        name="email" 
                        autocomplete="email" 
                        placeholder="Email@Email.com" 
                        required
                        value="<?= isset($user_data['userEmail']) ? htmlspecialchars($user_data['userEmail'], ENT_QUOTES, 'UTF-8') : '' ?>"
                    >
                </div>                
            </div>

            <!-- Message Field -->
            <label>Your Message:*</label>
            <textarea 
                rows="10" 
                name="message" 
                placeholder="Enter your Message" 
                required
            ></textarea>

            <!-- Submit Button -->
            <button class="contact-btn" type="submit" name="submit-btn">SEND MESSAGE</button>  
        </form>
    </div>

    <!-- Contact Info -->
    <div class="container-right">
        <h3>Reach Us</h3>
        <table>
            <tr>
                <td>Email:</td>
                <td>RecipeVault@gmail.com</td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>+00 00000000000</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>
                    De Montfort University <br>
                    Gateway House, Leicester, <br>
                    LE1 9BH - United Kingdom
                </td>
            </tr>
        </table>

        <!-- Google Map Embed -->
        <div class="map">
                       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19373.01539116062!2d-1.1612582652343848!3d52.63058569999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4879ddcfc377f7cd%3A0xd91c9efcc41fdd79!2sDe%20Montfort%20University!5e0!3m2!1sen!2suk!4v1713183774638!5m2!1sen!2suk" width="800" height="275" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>
</div>

<?php include 'footer.php'; ?> 
<script src="script.js"></script>
</body>
</html>
