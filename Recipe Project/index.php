<?php 
    // Include the database connection file
    include 'connection.php';
    session_start();

    // Unified session ID for both users and admins
    $LoggedIn = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;

    // Store the previous page URL for later use (for going back after search)
    if (!isset($_SESSION['previous_page'])) {
        $_SESSION['previous_page'] = $_SERVER['REQUEST_URI'];
    }

    // Handle logout functionality
    if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session to log out
        header("location: signIn.php"); // Redirect to sign in page
        exit();
    }

    // Initialize search query if the user has submitted a search
    $search_query = '';
    if (isset($_POST['search'])) {
        // Escape the search query to prevent SQL injection
        $search_query = mysqli_real_escape_string($conn, $_POST['search']);
    }

    // Handle adding a recipe to bookmarks
    if (isset($_POST['add_to_bookmark'])) {
        if ($LoggedIn) { // Check if the user is logged in
            $product_id = $_POST['product_id'];

            // Check if the recipe is already in the bookmark or cart
            $wishlist_check = mysqli_query($conn, "SELECT * FROM `bookmark` WHERE recipeId = '$product_id' AND userId = '$LoggedIn'") or die('query failed');
            $cart_check = mysqli_query($conn, "SELECT * FROM `cart` WHERE recipeId = '$product_id' AND userId = '$LoggedIn'") or die('query failed');

            if (mysqli_num_rows($wishlist_check) > 0) {
                $message[] = 'Recipe already exists in bookmark'; // Display message if already bookmarked
            } else if (mysqli_num_rows($cart_check) > 0) {
                $message[] = 'Recipe already exists in cart'; // Display message if already in cart
            } else {
                // Insert into the bookmark table if the recipe is not already bookmarked or in cart
                mysqli_query($conn, "INSERT INTO `bookmark`(`recipeId`, `userId`) VALUES('$product_id', '$LoggedIn')") or die('Insert failed');
                $message[] = 'Recipe successfully added to your bookmark'; // Success message
            }
        } else {
            $message[] = 'Please log in to bookmark recipes.'; // Prompt to log in if not logged in
        }
    }

    // Handle adding a recipe to the cart
    if (isset($_POST['add_to_cart'])) {
        if ($LoggedIn) { // Check if the user is logged in
            $product_id = $_POST['product_id'];
            $product_quantity = $_POST['product_quantity'];

            // Fetch recipe details from the recipes table
            $recipe_query = mysqli_query($conn, "SELECT recipeName, price, image FROM `recipes` WHERE recipeId = '$product_id'") or die('query failed');
            
            // Check if the recipe exists in the database
            if (mysqli_num_rows($recipe_query) > 0) {
                $recipe = mysqli_fetch_assoc($recipe_query);
                $product_name = $recipe['recipeName'];
                $product_price = $recipe['price'];
                $product_image = $recipe['image'];

                // Check if the recipe is already in the cart
                $cart_check = mysqli_query($conn, "SELECT * FROM `cart` WHERE recipeId = '$product_id' AND userId = '$LoggedIn'") or die('query failed');

                if (mysqli_num_rows($cart_check) > 0) {
                    $message[] = 'Recipe already exists in cart'; // Message if already in cart
                } else {
                    // Insert the recipe into the cart table
                    mysqli_query($conn, "INSERT INTO `cart`(`userId`, `recipeId`, `quantity`) 
                        VALUES('$LoggedIn', '$product_id', '$product_quantity')") or die('Insert failed');
                    $message[] = 'Recipe successfully added to your cart'; // Success message
                }
            } else {
                $message[] = 'Recipe not found in the database'; // If recipe not found
            }
        } else {
            $message[] = 'Please log in to add to cart.'; // Prompt to log in if not logged in
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>
    <title>Home</title>
</head>
<body>

<?php include 'header.php';?>

<section class="hero">
    <div class="hero-section">
        <h2>Welcome to our Recipe Vault.</h2>
        <p>Search mouthwatering recipes to satisfy your cravings.</p>
        <!-- Search form to allow users to search for recipes -->
        <form action="" method="post" class="search-box">
            <input class="search" type="text" name="search" value="<?php echo $search_query; ?>" placeholder="Search For Recipes">
            <button class="search-btn" type="submit">Search</button>
        </form>
    </div>
</section>

<section class="shop">
    <h1 class="title">Our Recipes</h1>

    <?php 
        // Display any messages (like success or error messages)
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
            // Build the search query conditionally if a search query exists
            $search_condition = '';
            if (!empty($search_query)) {
                $search_condition = "WHERE recipeName LIKE '%$search_query%'";
            }

            // Query to fetch recipes based on the search condition
            $select_products = mysqli_query($conn, "SELECT * FROM `recipes` $search_condition") or die('query failed');
            
            if (mysqli_num_rows($select_products) > 0) {
                // Display each recipe in a loop
                while ($fetch = mysqli_fetch_assoc($select_products)) {
        ?>
        <form method="post" class="box">
            <img src="images/<?php echo $fetch['image']; ?>" alt="Recipe Image">
            <div class="price">£<?php echo $fetch['price']; ?></div>
            <div class="name"><?php echo $fetch['recipeName']; ?></div>
            <div class="info">
                <small>Prep: <?php echo $fetch['prepTime']; ?> mins | Cook: <?php echo $fetch['cookTime']; ?> mins | kcal: <?php echo $fetch['calories']; ?> kcal</small><br>
            </div>
            <input type="hidden" name="product_id" value="<?php echo $fetch['recipeId']; ?>">
            <input type="hidden" name="product_quantity" value="1">
            <div class="icon">
                <a href="recipeDetail.php?recipeId=<?php echo $fetch['recipeId']; ?>" class="bi bi-eye-fill"></a>
                <button type="submit" name="add_to_bookmark" class="bi bi-bookmark-fill"></button>
                <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
            </div>
        </form>
        <?php 
                }
            } else {
                echo '<p class="empty">No products found matching your search!</p>'; // If no products match the search
            }
        ?>
    </div>

    <?php if (!empty($search_query)): ?>
        <a href="<?php echo $_SESSION['previous_page'] ?? 'index.php'; ?>" class="contact-btn">Go Back</a> <!-- Back button if search is done -->
    <?php endif; ?>
</section>

<br><br>

<!-- Botpress chat integration -->
<script src="https://cdn.botpress.cloud/webchat/v2.3/inject.js"></script>
<script src="https://files.bpcontent.cloud/2025/04/16/19/20250416192152-J7B50D1F.js"></script>

<?php include 'footer.php';?>
<script src="script.js"></script>
</body>
</html>
