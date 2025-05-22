<?php 
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($user_id) && !isset($admin_id)) {
    header('location:signIn.php');
    exit();
}

// Handle user logout
if (isset($_POST['logout'])) {
    session_destroy(); 
    header("location: signIn.php");
    exit();
}

$LoggedIn = $admin_id ?? $user_id;

// Adding a recipe or ingredient to the cart
if (isset($_POST['add_to_cart'])) {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $product_type = 'recipe';
    } else if (isset($_POST['ingredient_id'])) {
        $product_id = $_POST['ingredient_id'];
        $product_type = 'ingredient';
    }

    $product_quantity = $_POST['product_quantity'] ?? 1;

    if ($product_type == 'recipe') {
        $cart_num = mysqli_query($conn, "SELECT * FROM `cart` WHERE recipeId = '$product_id' AND userId = '$LoggedIn'") or die('query failed');
    } else {
        $cart_num = mysqli_query($conn, "SELECT * FROM `cart` WHERE ingredientId = '$product_id' AND userId = '$LoggedIn'") or die('query failed');
    }

    if (mysqli_num_rows($cart_num) > 0) {
        $message[] = 'Item already in cart';
    } else {
        if ($product_type == 'recipe') {
            mysqli_query($conn, "INSERT INTO `cart`(`userId`, `recipeId`, `quantity`) VALUES('$LoggedIn', '$product_id', '$product_quantity')") or die('query failed');
        } else {
            mysqli_query($conn, "INSERT INTO `cart`(`userId`, `ingredientId`, `quantity`) VALUES('$LoggedIn', '$product_id', '$product_quantity')") or die('query failed');
        }
        $message[] = 'Item added to cart';
    }
}

// Delete specific bookmark
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `bookmark` WHERE bookmarkId = '$delete_id' AND userId = '$LoggedIn'") or die('query failed');
    header('location:bookmark.php');
    exit();
}

// Delete all bookmarks
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `bookmark` WHERE userId = '$LoggedIn'") or die('query failed');
    header('location:bookmark.php');
    exit();
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
    <title>Your Bookmark</title>
</head>
<body>
    <?php include 'header.php'; ?> 

    <section class="shop">
        <br><br>
        <h1 class="title">Your Bookmark</h1>

        <?php 
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

        <!-- Bookmarked Recipes -->
        <div>
            <h2>Bookmarked Recipes</h2>
            <div class="box-container1">
                <?php 
                $select_wishlist_recipes = mysqli_query($conn, 
                    "SELECT b.bookmarkId, r.recipeId, r.recipeName, r.image 
                     FROM `bookmark` b 
                     JOIN `recipes` r ON b.recipeId = r.recipeId 
                     WHERE b.userId = '$LoggedIn'") or die('query failed');

                if (mysqli_num_rows($select_wishlist_recipes) > 0) {
                    while ($fetch_wishlist_recipe = mysqli_fetch_assoc($select_wishlist_recipes)) {
                ?>
                <form method="post" class="box">
                    <img src="images/<?php echo $fetch_wishlist_recipe['image']; ?>" alt="<?php echo $fetch_wishlist_recipe['recipeName']; ?>">
                    <div class="name"><?php echo $fetch_wishlist_recipe['recipeName']; ?></div>

                    <input type="hidden" name="product_id" value="<?php echo $fetch_wishlist_recipe['recipeId']; ?>">
                    <input type="hidden" name="product_quantity" value="1">

                    <div class="icon">
                        <a href="recipeDetail.php?recipeId=<?php echo $fetch_wishlist_recipe['recipeId']; ?>" class="bi bi-eye-fill"></a>
                        <a href="bookmark.php?delete=<?php echo $fetch_wishlist_recipe['bookmarkId']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this recipe from your bookmark?')"></a>
                        <button type="submit" name="add_to_cart" class="bi bi-cart" title="Add to cart"></button>
                    </div>
                </form>
                <?php 
                    }
                } else {
                    echo '<p class="empty">No recipes BookMarked!</p>';
                }
                ?>
            </div>
        </div>

        <!-- Bookmarked Ingredients -->
        <div>
            <h2>Bookmarked Ingredients</h2>
            <div class="box-container1">
                <?php 
                $select_wishlist_ingredients = mysqli_query($conn, 
                    "SELECT b.bookmarkId, i.ingredientId, i.ingredientName, i.image 
                     FROM `bookmark` b 
                     JOIN `ingredients` i ON b.ingredientId = i.ingredientId 
                     WHERE b.userId = '$LoggedIn'") or die('query failed');

                if (mysqli_num_rows($select_wishlist_ingredients) > 0) {
                    while ($fetch_wishlist_ingredient = mysqli_fetch_assoc($select_wishlist_ingredients)) {
                ?>
                <form method="post" class="box">
                    <img src="images/<?php echo $fetch_wishlist_ingredient['image']; ?>" alt="<?php echo $fetch_wishlist_ingredient['ingredientName']; ?>">
                    <div class="name"><?php echo $fetch_wishlist_ingredient['ingredientName']; ?></div>

                    <input type="hidden" name="ingredient_id" value="<?php echo $fetch_wishlist_ingredient['ingredientId']; ?>">
                    <input type="hidden" name="product_quantity" value="1">

                    <div class="icon">
                        <a href="bookmark.php?delete=<?php echo $fetch_wishlist_ingredient['bookmarkId']; ?>" class="bi bi-x" onclick="return confirm('Do you want to delete this ingredient from your bookmark?')"></a>
                        <button type="submit" name="add_to_cart" class="bi bi-cart" title="Add to cart"></button>
                    </div>
                </form>
                <?php 
                    }
                } else {
                    echo '<p class="empty">No ingredients BookMarked!</p>';
                }
                ?>
            </div>
        </div>

        <!-- Bookmark Control Buttons -->
        <div class="wishlist_total">
            <div class="button-group">
                <a href="index.php" class="delete">Continue Shopping</a>
                <?php 
                // Check if there are any bookmarks to show delete all button
                $check_any_bookmarks = mysqli_query($conn, "SELECT * FROM `bookmark` WHERE userId = '$LoggedIn'") or die('query failed');
                if (mysqli_num_rows($check_any_bookmarks) > 0): ?>
                    <a href="bookmark.php?delete_all=true" class="delete" onclick="return confirm('Do you want to delete all items from your bookmark?')">Delete All</a>
                <?php else: ?>
                    <a href="#" class="delete disabled" onclick="return false;" title="No items in bookmarks">Delete All</a>
                <?php endif; ?>
            </div>
        </div>

    </section>

    <?php include 'footer.php'; ?> 
    <script src="script.js"></script> 
</body>
</html>
