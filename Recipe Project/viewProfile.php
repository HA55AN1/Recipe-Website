<?php
include 'connection.php';
session_start();

// Get the user ID from session to identify the logged-in user
$user_id = $_SESSION['user_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;
$LoggedIn = $user_id ?? $admin_id;

// Redirect if not logged in
if (!$LoggedIn) {
    header('Location: signIn.php');
    exit();
}

// Fetch user data
$query = mysqli_query($conn, "SELECT * FROM users WHERE userId = '$LoggedIn'") or die('Query failed');
$user = mysqli_fetch_assoc($query);
$is_admin = ($user['userType'] === 'admin');

// Table and column references
$table = 'users';
$column = 'userId';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    // Fetch the current hashed password
    $user_query = mysqli_query($conn, "SELECT userPassword FROM `$table` WHERE `$column` = '$LoggedIn'") or die('Query failed');
    $user_data = mysqli_fetch_assoc($user_query);
    $current_hashed_password = $user_data['userPassword'];

    // Handle password update
    if (!empty($old_password)) {
        if (password_verify($old_password, $current_hashed_password)) {
            if ($new_password === $confirm_new_password) {
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE `$table` SET userPassword = '$hashed_new_password' WHERE `$column` = '$LoggedIn'") or die('Password update failed');
                $message[] = 'Password updated successfully';
            } else {
                $message[] = 'New password and confirm password do not match';
            }
        } else {
            $message[] = 'Old password is incorrect';
        }
    }

    // Update profile data
    mysqli_query($conn, "UPDATE `$table` SET userEmail = '$email', number = '$number', userAddress = '$address' WHERE `$column` = '$LoggedIn'") or die('Update failed for user');

    // Refresh user data
    $query = mysqli_query($conn, "SELECT * FROM `$table` WHERE `$column` = '$LoggedIn'") or die('Query failed');
    $user = mysqli_fetch_assoc($query);

    $message[] = 'Profile updated successfully';
}

// Cancel order
if (isset($_GET['cancel_order'])) {
    $order_id = $_GET['cancel_order'];
    $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE orderId = '$order_id' AND userId = '$LoggedIn' AND paymentStatus = 'pending'");
    if ($order = mysqli_fetch_assoc($order_query)) {
        $update_query = mysqli_query($conn, "UPDATE orders SET paymentStatus = 'canceled' WHERE orderId = '$order_id'");
        if ($update_query) {
            $message[] = 'Order has been successfully cancelled';
        } else {
            $message[] = 'Failed to cancel the order. Please try again';
        }
    } else {
        $message[] = 'This order cannot be cancelled or is not in pending status';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="styles.css"/>
  <title>View Profile</title>
</head>
<body>

<?php include 'header.php'; ?>
<br>

<?php 
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
            <div class="message">
                <span>' . htmlspecialchars($msg) . '</span>
                <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
            </div>
        ';
    }
}
?>

<div class="container">
  <h2>My Profile</h2>

  <form action="" method="post" class="form">
    <label>Email:</label>
    <input type="text" name="email" value="<?php echo htmlspecialchars($user['userEmail']); ?>" required>

    <label>Phone Number:</label>
    <input type="text" name="number" value="<?php echo htmlspecialchars($user['number']); ?>">

    <label>Address:</label>
    <input type="text" name="address" value="<?php echo htmlspecialchars($user['userAddress']); ?>">

    <label>Old Password:</label>
    <input type="password" name="old_password" placeholder="Enter current password to change">

    <label>New Password:</label>
    <input type="password" name="new_password" placeholder="Enter new password">

    <label>Confirm New Password:</label>
    <input type="password" name="confirm_new_password" placeholder="Re-enter new password">

    <button type="submit" name="update_profile" class="contact-btn">Update Profile</button>
  </form>
</div>

<div class="order-container1">
  <h2>Order History</h2>
  <?php
    $current_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;

    if ($current_id) {
        $order_query = mysqli_query($conn, "SELECT * FROM orders WHERE userId = '$LoggedIn' ORDER BY placedOn DESC");

        if (mysqli_num_rows($order_query) > 0) {
            echo '<table class="order-history">';
            echo '<thead><tr>
                    <th>Order ID</th>
                    <th>Order Method</th>
                    <th>Your Order</th>
                    <th>Total Price</th>
                    <th>Placed On</th>
                    <th>Payment Status</th>
                    <th>Action</th>
                  </tr></thead><tbody>';

            while ($order = mysqli_fetch_assoc($order_query)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($order['orderId']) . '</td>';
                echo '<td>' . htmlspecialchars($order['orderMethod']) . '</td>';
                echo '<td>' . htmlspecialchars($order['totalRecipes']) . '</td>';
                echo '<td>£' . htmlspecialchars($order['totalPrice']) . '</td>';
                echo '<td>' . htmlspecialchars($order['placedOn']) . '</td>';
                echo '<td>' . htmlspecialchars($order['paymentStatus']) . '</td>';

                if ($order['paymentStatus'] === 'pending') {
                    echo '<td><a href="viewProfile.php?cancel_order=' . htmlspecialchars($order['orderId']) . '" class="edit">Cancel Order</a></td>';
                } else {
                    echo '<td>-</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>No order history found.</p>';
        }
    } else {
        echo '<p>User not logged in.</p>';
    }
  ?>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>

</body>
</html>
