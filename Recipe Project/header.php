<?php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}

// Include the database connection file
include 'connection.php';  

// Get user ID and userType if logged in (for both users and admins)
$user_id = $_SESSION['user_id'] ?? null; // User ID from session if available
$admin_id = $_SESSION['admin_id'] ?? null; // Admin ID from session if available
$user_or_admin_id = $user_id ?? $admin_id; // Check if the user or admin is logged in

// Logout logic
if (isset($_POST['logout'])) {
  $_SESSION = [];  // Clear session data
  session_unset(); // Remove session variables
  session_destroy(); // Destroy the session
  header('Location: signIn.php'); // Redirect to the sign-in page
  exit(); // Stop further script execution after redirection
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>
    <title>RecipeVault</title>
  </head>
  <body>
    <header>
      <nav>
        <div class="nav_header">
          <div class="nav_logo">
            <!-- Logo and brand name link -->
            <a href="index.php">Recipe<span>Vault</span>.</a>
          </div>
          <div class="nav_menu_btn" id="menu_btn">
            <!-- Menu icon for responsive design -->
            <span><i class="bi bi-list"></i></span>
          </div>
        </div>

        <!-- Main navigation links -->
        <ul class="nav_links" id="nav_links">
            <li><a href="index.php">Home</a></li> 
            <li><a href="ingredients.php">Ingredients</a></li> 
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <?php if ($admin_id): // Show admin-specific dropdown only for admins ?>
            <!-- Admin dropdown menu for managing different aspects -->
           <li class="administration">
           <a href="adminIndex.php"><span>Administration</span></a>
           <div class="admin-content">
            <a href="manageUser.php">Manage Users</a>
            <a href="manageRecipes.php">Manage Recipes</a>
            <a href="manageIngredients.php">Manage Ingredients</a>
            <a href="manageOrders.php">Manage Orders</a>
            <a href="manageMessages.php">Manage Messages</a>
           </div>
           </li>
           <?php endif; ?>
        </ul>

        <div class="nav_btns">
        <?php if (!$user_or_admin_id): // If not logged in, show Sign In & Sign Up buttons ?>
                <!-- Buttons for users to sign up or sign in -->
                <a href="signUp.php"><button class="btn sign_up">Sign Up</button></a>
                <a href="signIn.php"><button class="btn sign_in">Sign In</button></a>
            <?php else: // If logged in, show user info and logout button ?>
              <div class="icons">
                <!-- User-specific icons for profile, bookmarks, and cart -->
				      <i class="bi bi-person" id="user-btn"></i>
              <?php 
                    // Query to count the number of items in the user's bookmark list
					    $select_bookmark = mysqli_query($conn, "SELECT * FROM `bookmark` WHERE userId='$user_or_admin_id'") or die ('query failed');
					    $bookmark_num_rows = mysqli_num_rows($select_bookmark); // Get the number of bookmarks
				      ?>
              <!-- Bookmark icon with a count of bookmarked items -->
              <a href="bookmark.php"><i class="bi bi-bookmark"></i><sup><?php echo $bookmark_num_rows; ?></sup></a>
              
              <?php 
                    // Query to count the number of items in the user's cart
					    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE userId='$user_or_admin_id'") or die ('query failed');
					    $cart_num_rows = mysqli_num_rows($select_cart); // Get the number of items in the cart
				     ?>
				     <!-- Cart icon with a count of items in the cart -->
				     <a href="cart.php"><i class="bi bi-cart"></i><sup><?php echo $cart_num_rows; ?></sup></a>
			       </div>

               <!-- Display user information (username, email) and a logout button -->
               <div class="user-box">
                <p>Username : <span><?php echo $_SESSION['user_name'] ?? $_SESSION['admin_name']; ?></span></p>
                <p>Email : <span><?php echo $_SESSION['user_email'] ?? $_SESSION['admin_email']; ?></span></p>

               <!-- View Profile Link -->
               <a href="viewProfile.php" class="contact-btn">View Profile</a>

               <!-- Logout button form -->
               <form method="post">
                <button type="submit" name="logout" class="contact-btn">Log Out</button>
               </form>
              </div>

            <?php endif; ?>
        </div>
      </nav>
    </header>



   


  </body>
  </html>
