<?php 
include 'connection.php';
session_start();

// Retrieve user or admin ID from the session
$user_id = $_SESSION['user_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;

// Check if user is logged in, otherwise let them continue as a guest
$LoggedIn = $admin_id ?? $user_id;

// Handle user logout
if (isset($_POST['logout'])) {
    session_destroy(); 
    header("location: signIn.php");
    exit();
}

// Adding an ingredient to the cart (works for logged-in users)
if (isset($_POST['add_to_cart'])) {
    $ingredient_id = $_POST['ingredient_id']; // Ingredient ID to be added
    $ingredient_quantity = $_POST['ingredient_quantity'] ?? 1; // Get quantity from user input or default to 1

    // If the user is logged in, add to cart
    if ($LoggedIn) {
        // Check if the ingredient already exists in the cart
        $cart_num = mysqli_query($conn, "SELECT * FROM `cart` WHERE ingredientId = '$ingredient_id' AND userId = '$LoggedIn'") or die('query failed');
        
        // If the ingredient already exists in the cart, update its quantity
        if (mysqli_num_rows($cart_num) > 0) {
            $cart_item = mysqli_fetch_assoc($cart_num);
            $new_quantity = $cart_item['quantity'] + $ingredient_quantity;
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE ingredientId = '$ingredient_id' AND userId = '$LoggedIn'") or die('query failed');
            $message[] = 'Ingredient quantity updated in your cart';
        } else {
            // If not, insert the ingredient into the cart
            mysqli_query($conn, "INSERT INTO `cart`(`userId`, `ingredientId`, `quantity`) VALUES('$LoggedIn', '$ingredient_id', '$ingredient_quantity')") or die('query failed');
            $message[] = 'Ingredient successfully added to your cart';
        }
    } else {
        $message[] = 'You need to sign in to add items to the cart';
    }
}

// Adding an ingredient to bookmarks (works for logged-in users)
if (isset($_POST['bookmark'])) {
    $ingredient_id = $_POST['ingredient_id']; // Ingredient ID to be bookmarked

    // Check if the ingredient is already bookmarked
    if ($LoggedIn) {
        $bookmark_check = mysqli_query($conn, "SELECT * FROM `bookmark` WHERE ingredientId = '$ingredient_id' AND userId = '$LoggedIn'") or die('query failed');
        
        // If the ingredient is already bookmarked, show a message
        if (mysqli_num_rows($bookmark_check) > 0) {
            $message[] = 'Ingredient already bookmarked';
        } else {
            // If not, insert the ingredient into the bookmarks table
            mysqli_query($conn, "INSERT INTO `bookmark`(`userId`, `ingredientId`) VALUES('$LoggedIn', '$ingredient_id')") or die('query failed');
            $message[] = 'Ingredient successfully bookmarked';
        }
    } else {
        $message[] = 'You need to sign in to bookmark items';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
    <title>Ingredients</title>
</head>
<body>

 <!-- Include header -->
 <?php include 'header.php'; ?>

<section class="shop">
    <br><br><br><br><br>

    <h1 class="title">Available Ingredients</h1>

    <?php 
        // Display any messages (e.g., ingredient added or already in cart)
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '
                    <div class="message">
                        <span>'.$msg.'</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                ';
            }
        }
    ?>

    <div class="box-container1">
        <?php 
            $grand_total = 0; // Initialize total amount
            // Fetch ingredients from the database
            $select_ingredients = mysqli_query($conn, "SELECT * FROM `ingredients`") or die('query failed');

            // If there are ingredients, display them
            if (mysqli_num_rows($select_ingredients) > 0) {
                while ($fetch_ingredient = mysqli_fetch_assoc($select_ingredients)) {
        ?>

        <!-- Ingredient item form -->
        <form method="post" class="box">
            <img src="images/<?php echo $fetch_ingredient['image']; ?>" alt="<?php echo $fetch_ingredient['ingredientName']; ?>"> <!-- Ingredient Image -->
            <div class="name"><?php echo $fetch_ingredient['ingredientName']; ?></div> <!-- Ingredient Name -->
            
            <!-- Price Section -->
            <div class="price">£<?php echo $fetch_ingredient['price']; ?></div> <!-- Ingredient Price -->
            
            <!-- Icons for Actions (bookmark) -->
            <div class="icon">
                <button type="submit" name="bookmark" class="bi bi-bookmark-fill" value="Bookmark"></button> <!-- Bookmark icon -->
            </div>
            <br>

            <!-- Quantity Section -->
            <div class="qty">
                <input type="hidden" name="ingredient_id" value="<?php echo $fetch_ingredient['ingredientId']; ?>"> <!-- Hidden input to identify ingredient -->
                <input type="number" min="1" name="ingredient_quantity" value="1"> <!-- Input for quantity -->
                <input type="submit" name="add_to_cart" value="Add to Cart"> <!-- Add to cart button -->
            </div>

        </form>

        <?php 
                }
            } else {
                echo '<p class="empty">No ingredients available!</p>'; // If no ingredients found
            }
        ?>
    </div>

</section>

 <!-- Include footer -->
 <?php include 'footer.php'; ?>

<script src="script.js"></script>
</body>
</html>
