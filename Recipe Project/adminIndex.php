<?php 
include 'connection.php';
session_start();

$admin_id = $_SESSION['admin_name'];

// Check if the admin is logged in, otherwise redirect to sign-in page
if (!isset($admin_id)) {
    header('location:signIn.php');
    exit();
}

// Handle admin logout
if (isset($_POST['logout'])) {
    session_destroy(); 
    header('location:signIn.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Box icons for the dashboard -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <title>Welcome Admin</title>
</head>
<body>
	<!-- Include header -->
    <?php include 'header.php'; ?> 

    <br><br><br><br><br><br><br><br><br>
    
    <!-- Banner section -->
    <div class="banner">
        <div class="detail">
            <h1>Admin Dashboard</h1>
        </div>
    </div>

    <!-- Admin Dashboard section -->
    <section class="dashboard">
        <div class="box-container">
            <!-- Total Pending Orders -->
            <div class="box">
                <?php 
                    // Calculate the total amount for all pending orders
                    $total_pendings = 0;
                    $select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE paymentStatus = 'pending'") or die('query failed');
                    while ($fetch_pending = mysqli_fetch_assoc($select_pendings)) {
                        $total_pendings += $fetch_pending['totalPrice']; // Sum the price of pending orders
                    }
                ?>
                <h3>£<?php echo $total_pendings; ?>.00</h3>
                <p>Total Pendings</p> <!-- Display the total amount of pending orders -->
            </div>

            <!-- Total Completed Orders -->
            <div class="box">
                <?php 
                    // Calculate the total amount for all completed orders
                    $total_completes = 0;
                    $select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE paymentStatus = 'complete'") or die('query failed');
                    while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
                        $total_completes += $fetch_completes['totalPrice']; // Sum the price of completed orders
                    }
                ?>
                <h3>£<?php echo $total_completes; ?>.00</h3>
                <p>Total Completes</p> <!-- Display the total amount of completed orders -->
            </div>

            <!-- Total Orders Placed -->
            <div class="box">
                <?php 
                    // Count the total number of orders placed
                    $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
                    $num_of_orders = mysqli_num_rows($select_orders); // Get the number of rows (orders)
                ?>
                <h3><?php echo $num_of_orders; ?></h3>
                <p>Orders Placed</p> <!-- Display the total number of orders placed -->
            </div>

            <!-- Total Recipes Added -->
            <div class="box">
                <?php 
                    // Count the total number of products (recipes) added
                    $select_products = mysqli_query($conn, "SELECT * FROM `recipes`") or die('query failed');
                    $num_of_products = mysqli_num_rows($select_products); // Get the number of products
                ?>
                <h3><?php echo $num_of_products; ?></h3>
                <p>Recipes Added</p> <!-- Display the total number of products added -->
            </div>

            <!-- Total Ingredients Added -->
            <div class="box">
               <?php 
                   // Count the total number of ingredients
                   $select_ingredients = mysqli_query($conn, "SELECT * FROM `ingredients`") or die('query failed');
                   $num_of_ingredients = mysqli_num_rows($select_ingredients); // Get the number of ingredients
                ?>
                <h3><?php echo $num_of_ingredients; ?></h3>
              <p>Ingredients Added</p> <!-- Display the total number of ingredients added -->
            </div>


            
            

            <!-- Total Normal Users -->
            <div class="box">
                <?php 
                    // Count the total number of normal users
                    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE userType = 'user'") or die('query failed');
                    $num_of_users = mysqli_num_rows($select_users); // Get the number of normal users
                ?>
                <h3><?php echo $num_of_users; ?></h3>
                <p>Total Normal Users</p> <!-- Display the total number of normal users -->
            </div>

            <!-- Total Admin Users -->
            <div class="box">
                <?php 
                    // Count the total number of admin users
                    $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE userType = 'admin'") or die('query failed');
                    $num_of_admin = mysqli_num_rows($select_admins); // Get the number of admin users
                ?>
                <h3><?php echo $num_of_admin; ?></h3>
                <p>Total Admins</p> <!-- Display the total number of admin users -->
            </div>


            <!-- Total New Messages -->
            <div class="box">
                <?php 
                    // Count the total number of messages received
                    $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                    $num_of_message = mysqli_num_rows($select_message); // Get the total number of messages
                ?>
                <h3><?php echo $num_of_message; ?></h3>
                <p>New Messages</p> <!-- Display the total number of new messages -->
            </div>
        </div>
    </section>
    <!-- Include footer -->
    <?php include 'footer.php'; ?> 

	<!-- Include JavaScript file -->
    <script src="script.js"></script> 
</body>
</html>
