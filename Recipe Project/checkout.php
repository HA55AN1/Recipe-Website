<?php 
include 'connection.php';
session_start();

// Retrieve user or admin ID from the session
$user_id = $_SESSION['user_id'] ?? null;
$admin_id = $_SESSION['admin_id'] ?? null;
$LoggedIn = $admin_id ?? $user_id; 

// Redirect to sign-in page if user is not logged in
if (!isset($LoggedIn)) {
    header('location:signIn.php');
    exit();
}

// Handle user logout
if (isset($_POST['logout'])) {
    session_destroy(); 
    header("location: signIn.php");
    exit();
}

// Handle order placement
if (isset($_POST['order_btn'])) {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $number = trim($_POST['number']);
    $method = in_array($_POST['method'], ['credit card', 'paypal']) ? $_POST['method'] : 'unknown';

    $address_parts = [
        trim($_POST['flate']),
        trim($_POST['street']),
        trim($_POST['city']),
        trim($_POST['county']),
        trim($_POST['country']),
        trim($_POST['postcode'])
    ];
    $address = htmlspecialchars(implode(', ', $address_parts), ENT_QUOTES, 'UTF-8');

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Invalid email address.';
    } elseif (!ctype_digit($number)) {
        $message[] = 'Invalid phone number.';
    } else {
        $placed_on = date('Y-m-d');
        $cart_total = 0;
        $cart_products = [];

        // Fetch recipes from cart using prepared statement
        $stmt = $conn->prepare("SELECT c.quantity, r.recipeName, r.price FROM cart c JOIN recipes r ON c.recipeId = r.recipeId WHERE c.userId = ?");
        $stmt->bind_param("i", $LoggedIn);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($item = $result->fetch_assoc()) {
            $cart_products[] = $item['recipeName'] . ' (' . $item['quantity'] . ')';
            $cart_total += $item['price'] * $item['quantity'];
        }
        $stmt->close();

        // Fetch ingredients from cart using prepared statement
        $stmt = $conn->prepare("SELECT c.quantity, i.ingredientName, i.price FROM cart c JOIN ingredients i ON c.ingredientId = i.ingredientId WHERE c.userId = ?");
        $stmt->bind_param("i", $LoggedIn);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($item = $result->fetch_assoc()) {
            $cart_products[] = $item['ingredientName'] . ' (' . $item['quantity'] . ')';
            $cart_total += $item['price'] * $item['quantity'];
        }
        $stmt->close();

        $total_products = implode(', ', $cart_products);

        // Insert order securely
        $stmt = $conn->prepare("INSERT INTO orders (userId, userName, userEmail, number, orderMethod, userAddress, totalRecipes, totalPrice, placedOn) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssds", $LoggedIn, $name, $email, $number, $method, $address, $total_products, $cart_total, $placed_on);
        $stmt->execute();
        $stmt->close();

        // Clear the cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE userId = ?");
        $stmt->bind_param("i", $LoggedIn);
        $stmt->execute();
        $stmt->close();

        $message[] = 'Order has been placed successfully';
        header('location:cart.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="checkout-form">
    <h1 class="title">Payment Process</h1>

    <?php if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message">
                    <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                  </div>';
        }
    } ?>

    <div class="display-order">
        <div class="box-container">
        <?php
            $grand_total = 0;

            // Display cart recipes
            $stmt = $conn->prepare("SELECT c.quantity, r.recipeName, r.price, r.image FROM cart c JOIN recipes r ON c.recipeId = r.recipeId WHERE c.userId = ?");
            $stmt->bind_param("i", $LoggedIn);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($fetch_cart = $result->fetch_assoc()) {
                $total_price = $fetch_cart['price'] * $fetch_cart['quantity'];
                $grand_total += $total_price;
                echo '<div class="box">
                        <img src="images/' . htmlspecialchars($fetch_cart['image'], ENT_QUOTES, 'UTF-8') . '">
                        <span>' . htmlspecialchars($fetch_cart['recipeName'], ENT_QUOTES, 'UTF-8') . ' (' . (int)$fetch_cart['quantity'] . ')</span>
                      </div>';
            }
            $stmt->close();

            // Display cart ingredients
            $stmt = $conn->prepare("SELECT c.quantity, i.ingredientName, i.price, i.image FROM cart c JOIN ingredients i ON c.ingredientId = i.ingredientId WHERE c.userId = ?");
            $stmt->bind_param("i", $LoggedIn);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($ingredient = $result->fetch_assoc()) {
                $total_price = $ingredient['price'] * $ingredient['quantity'];
                $grand_total += $total_price;
                echo '<div class="box">
                        <img src="images/' . htmlspecialchars($ingredient['image'], ENT_QUOTES, 'UTF-8') . '">
                        <span>' . htmlspecialchars($ingredient['ingredientName'], ENT_QUOTES, 'UTF-8') . ' (' . (int)$ingredient['quantity'] . ')</span>
                      </div>';
            }
            $stmt->close();
        ?>
        </div>
        <span class="grand-total">Total Amount Payable : £<?= number_format($grand_total, 2); ?></span>
    </div>

    <section class="form-container">
        <form method="post">
            <div class="input-field"><label>Your Name</label><input type="text" name="name" placeholder="Enter your name" required></div>
            <div class="input-field"><label>Your Number</label><input type="text" name="number" placeholder="Enter your number" required></div>
            <div class="input-field"><label>Your Email</label><input type="email" name="email" placeholder="Enter your email" required></div>
            <div class="input-field">
                <label>Select Payment Method</label>
                <select name="method" required>
                    <option selected disabled>Select payment method</option>
                    <option value="credit card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <div class="input-field"><label>Address Line 1</label><input type="text" name="flate" placeholder="e.g. Flat No." required></div>
            <div class="input-field"><label>Address Line 2</label><input type="text" name="street" placeholder="e.g. Street Name" required></div>
            <div class="input-field"><label>City</label><input type="text" name="city" placeholder="e.g. Leicester" required></div>
            <div class="input-field"><label>County</label><input type="text" name="county" placeholder="e.g. Leicestershire" required></div>
            <div class="input-field"><label>Country</label><input type="text" name="country" placeholder="e.g. United Kingdom" required></div>
            <div class="input-field"><label>Postcode</label><input type="text" name="postcode" placeholder="e.g. LE2 2AA" required></div>
            <input type="submit" name="order_btn" class="contact-btn" value="Order Now">
        </form>
    </section>
</div>

<?php include 'footer.php'; ?>
<script src="script.js"></script>
</body>
</html>
