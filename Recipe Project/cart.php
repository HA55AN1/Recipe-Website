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

// Add to cart
if (isset($_POST['add_to_cart'])) {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $product_type = 'recipe';
    } else if (isset($_POST['ingredient_id'])) {
        $product_id = $_POST['ingredient_id'];
        $product_type = 'ingredient';
    }

    $product_quantity = 1;

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

// Handle quantity update
if (isset($_POST['update_qty_btn'])) {
    $update_cart_id = $_POST['update_qty_id'];
    $update_quantity = intval($_POST['update_qty']);
    if ($update_quantity < 1) $update_quantity = 1;

    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE cartId = '$update_cart_id' AND userId = '$LoggedIn'") or die('query failed');
    header('location:cart.php');
    exit();
}

// Delete a cart item
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE cartId = '$delete_id' AND userId = '$LoggedIn'") or die('query failed');
    header('location:cart.php');
    exit();
}

// Delete all items
if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE userId = '$LoggedIn'") or die('query failed');
    header('location:cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="shop">
    <br><br>
    <h1 class="title">Your Cart</h1>

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

    <!-- Recipes in Cart -->
    <div>
        <h2>Recipes in Cart</h2>
        <div class="box-container1">
            <?php 
            $grand_total_recipes = 0;
            $select_cart_recipes = mysqli_query($conn, 
                "SELECT c.cartId, r.recipeId, r.recipeName, r.price, r.image, c.quantity 
                 FROM `cart` c 
                 JOIN `recipes` r ON c.recipeId = r.recipeId 
                 WHERE c.userId = '$LoggedIn'") or die('query failed');

            if (mysqli_num_rows($select_cart_recipes) > 0) {
                while ($fetch = mysqli_fetch_assoc($select_cart_recipes)) {
            ?>
            <form method="post" class="box">
                <img src="images/<?php echo $fetch['image']; ?>" alt="<?php echo $fetch['recipeName']; ?>">
                <div class="price">£<?php echo $fetch['price']; ?></div>
                <div class="name"><?php echo $fetch['recipeName']; ?></div>

                <div class="icon">
                    <a href="recipeDetail.php?recipeId=<?php echo $fetch['recipeId']; ?>" class="bi bi-eye-fill"></a>
                    <a href="cart.php?delete=<?php echo $fetch['cartId']; ?>" class="bi bi-x" onclick="return confirm('Do you want to remove this recipe from your cart?')"></a>
                </div><br>

                <div class="qty">
                    <input type="number" name="update_qty" value="<?php echo $fetch['quantity']; ?>" min="1">
                    <input type="hidden" name="update_qty_id" value="<?php echo $fetch['cartId']; ?>">
                    <input type="submit" name="update_qty_btn" value="Update Quantity">
                </div>
            </form>
            <?php 
                $grand_total_recipes += $fetch['price'] * $fetch['quantity'];
                }
            } else {
                echo '<p class="empty">No recipes in cart!</p>';
            }
            ?>
        </div>
        <p>Total amount of recipes in cart: £<?php echo $grand_total_recipes; ?>/-</p>
    </div>

    <!-- Ingredients in Cart -->
    <div>
        <h2>Ingredients in Cart</h2>
        <div class="box-container1">
            <?php 
            $grand_total_ingredients = 0;
            $select_cart_ingredients = mysqli_query($conn, 
                "SELECT c.cartId, i.ingredientId, i.ingredientName, i.price, i.image, c.quantity 
                 FROM `cart` c 
                 JOIN `ingredients` i ON c.ingredientId = i.ingredientId 
                 WHERE c.userId = '$LoggedIn'") or die('query failed');

            if (mysqli_num_rows($select_cart_ingredients) > 0) {
                while ($fetch = mysqli_fetch_assoc($select_cart_ingredients)) {
            ?>
            <form method="post" class="box">
                <img src="images/<?php echo $fetch['image']; ?>" alt="<?php echo $fetch['ingredientName']; ?>">
                <div class="price">£<?php echo $fetch['price']; ?></div>
                <div class="name"><?php echo $fetch['ingredientName']; ?></div>


                <div class="icon">
                    <a href="cart.php?delete=<?php echo $fetch['cartId']; ?>" class="bi bi-x" onclick="return confirm('Do you want to remove this ingredient from your cart?')"></a>
                </div><br>


                <div class="qty">
                    <input type="number" name="update_qty" value="<?php echo $fetch['quantity']; ?>" min="1">
                    <input type="hidden" name="update_qty_id" value="<?php echo $fetch['cartId']; ?>">
                    <input type="submit" name="update_qty_btn" value="Update Quantity">
                </div>

            </form>
            <?php 
                $grand_total_ingredients += $fetch['price'] * $fetch['quantity'];
                }
            } else {
                echo '<p class="empty">No ingredients in cart!</p>';
            }
            ?>
        </div>
        <p>Total amount of ingredients in cart: £<?php echo $grand_total_ingredients; ?>/-</p>
    </div>

    <!-- Cart Totals and Actions -->
    <?php
// Combine recipe and ingredient totals
$grand_total = $grand_total_recipes + $grand_total_ingredients;
?>

<div class="wishlist_total">
    <h3>Total Summary</h3>
    <p>Total Amount Payable: <strong>£<?php echo number_format($grand_total, 2); ?></strong></p>

    <div class="button-group">
        <?php if ($grand_total > 0): ?>
            <a href="cart.php?delete_all=true" class="delete" onclick="return confirm('Are you sure you want to delete all items from your cart?')">Delete All</a>
            <a href="checkout.php" class="delete">Proceed to Checkout</a>
        <?php else: ?>
            <a href="#" class="delete disabled" onclick="return false;" title="Cart is empty">Delete All</a>
            <a href="#" class="delete disabled" onclick="return false;" title="Cart is empty">Proceed to Checkout</a>
        <?php endif; ?>
    </div>
</div>

   

</section>

<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
