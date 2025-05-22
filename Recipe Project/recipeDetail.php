<?php 
include 'connection.php'; // Include the database connection
session_start(); // Start the session

// Get the user ID if user or admin is logged in
$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null;

// Handle logout functionality
if(isset($_POST['logout'])){
    session_destroy(); // Destroy the session
    header("location: signIn.php"); // Redirect to the sign-in page
}

// Handle Add Comment functionality
if (isset($_POST['add_comment'])) {
    if ($user_id) {
        // Sanitize and store comment data
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $recipe_id = $_POST['recipe_id'];
        // Insert comment into the database
        mysqli_query($conn, "INSERT INTO comments (userId, recipeId, comment, createdOn) VALUES ('$user_id', '$recipe_id', '$comment', NOW())") or die('Query failed');
        header("Location: ".$_SERVER['REQUEST_URI']); // Avoid form resubmission
        exit();
    } else {
        $message[] = 'Please log in to comment.'; // Message if not logged in
    }
}

// Handle Edit Comment functionality
if (isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $updated_comment = mysqli_real_escape_string($conn, $_POST['updated_comment']);
    // Update the comment only if the user is the owner
    mysqli_query($conn, "UPDATE comments SET comment = '$updated_comment' WHERE commentId = '$comment_id' AND userId = '$user_id'") or die('Query failed');
    $message[] = 'Comment updated successfully.'; // Message on successful update
}

// Handle Delete Comment functionality
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $comment_check = mysqli_query($conn, "SELECT * FROM comments WHERE commentId = '$comment_id'") or die('Query failed');

    if (mysqli_num_rows($comment_check) > 0) {
        $row = mysqli_fetch_assoc($comment_check);
        $is_admin = isset($_SESSION['admin_id']) && $_SESSION['admin_id'];

        // Allow only admin or the comment owner to delete
        if ($is_admin || $row['userId'] == $user_id) {
            $delete_query = $is_admin 
                ? "DELETE FROM comments WHERE commentId = '$comment_id'" 
                : "DELETE FROM comments WHERE commentId = '$comment_id' AND userId = '$user_id'";
            
            $result = mysqli_query($conn, $delete_query);
            $message[] = $result ? 'Comment deleted successfully!' : 'Error deleting comment.'; // Message on delete
        } else {
            $message[] = 'You cannot delete this comment.'; // Message if the user can't delete the comment
        }
    }
}

// Handle adding recipe to bookmark
if (isset($_POST['add_to_bookmark'])) {
    if ($user_id) {
        $product_id = $_POST['product_id'];
        // Check if recipe is already bookmarked
        $wishlist_number = mysqli_query($conn, "SELECT * FROM `bookmark` WHERE recipeId = '$product_id' AND userId = '$user_id'") or die('query failed');
        
        if (mysqli_num_rows($wishlist_number) > 0) {
            $message[] = 'Recipe already exists in bookmark'; // Message if already bookmarked
        } else {
            mysqli_query($conn, "INSERT INTO `bookmark`(`recipeId`, `userId`) VALUES('$product_id', '$user_id')"); // Insert new bookmark
            $message[] = 'Recipe successfully added to your bookmark'; // Success message
        }
    } else {
        $message[] = 'Please log in to bookmark recipes.'; // Message if not logged in
    }
}

// Handle adding recipe to cart
if (isset($_POST['add_to_cart'])) {
    if ($user_id) {
        $product_id = $_POST['product_id'];
        $product_quantity = $_POST['product_quantity'];

        // Check if recipe is already in cart
        $cart_check = mysqli_query($conn, "SELECT * FROM `cart` WHERE recipeId = '$product_id' AND userId = '$user_id'") or die('query failed');

        if (mysqli_num_rows($cart_check) > 0) {
            $existing = mysqli_fetch_assoc($cart_check);
            $new_quantity = $existing['quantity'] + $product_quantity;
            // Update quantity
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE recipeId = '$product_id' AND userId = '$user_id'") or die('Update failed');
            $message[] = 'Recipe quantity updated in cart.'; // Update message
        } else {
            // Add new entry to cart
            mysqli_query($conn, "INSERT INTO `cart`(`userId`, `recipeId`, `quantity`) VALUES('$user_id', '$product_id', '$product_quantity')") or die('Insert failed');
            $message[] = 'Recipe successfully added to your cart.'; // Success message
        }
    } else {
        $message[] = 'Please log in to add to cart.'; // Message if not logged in
    }
}
?>

<!-- HTML section starts here -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="styles.css"/>
  <title>Recipe Details</title>
</head>
<body>
<?php include 'header.php';?> <!-- Include the header -->

<section>
<br><br>
<?php 
// Display messages if there are any (such as errors or confirmations)
if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>'.$message.'</span>
            <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
        </div>
        ';
    }
}
?>

<?php 
// Display recipe details if the recipeId is passed in the URL
if (isset($_GET['recipeId'])) {
    $pid = $_GET['recipeId'];
    $select_products = mysqli_query($conn, "SELECT * FROM `recipes` WHERE recipeId = '$pid'") or die('query failed');
    if (mysqli_num_rows($select_products) > 0) {
        while($fetch_products = mysqli_fetch_assoc($select_products)){
?>

<!-- Recipe Page Section -->
<div class="recipe-page">
  <form method="post">
    <section class="recipe-hero">
      <img src="images/<?php echo $fetch_products['image']; ?>">
      <div class="recipe-info">
        <br><h2><div class="name"><?php echo $fetch_products['recipeName']; ?></div></h2><br><br>
        <p><div class="detail"><?php echo $fetch_products['recipeDescription']; ?></div></p>

        <div class="recipe-icons">
          <div>
            <i class="bi bi-alarm-fill"></i>
            <h5>prep time</h5>
            <p><?php echo $fetch_products['prepTime']; ?> min</p>
          </div>
          <div>
            <i class="bi bi-alarm"></i>
            <h5>cook time</h5>
            <p><?php echo $fetch_products['cookTime']; ?> min</p>
          </div>
          <div>
            <i class="bi bi-people-fill"></i>
            <h5>calories</h5>
            <p><?php echo $fetch_products['calories']; ?> kcal</p>
          </div>
        </div>

        <div class="price1">£<?php echo $fetch_products['price']; ?> /per serving</div>

        <div class="checkout-icons">
          <button type="submit" name="add_to_bookmark" class="bi bi-bookmark"></button>
          <input type="number" name="product_quantity" value="1" min="0" class="quantity">
          <button type="submit" name="add_to_cart" class="bi bi-cart"></button>
        </div>
      </div>
    </section>

    <!-- Instructions and Ingredients Section -->
    <section class="recipe-content">
      <div>
        <h4>Instructions</h4>
        <?php 
        // Split recipe instructions by newlines and display them
        $steps = explode("\n", $fetch_products['recipeMethod']);
        $stepNum = 1;
        foreach ($steps as $step) {
          if (trim($step)) {
            echo '
            <div class="single-instruction">
              <header>
                <p>Step '.$stepNum++.'</p>
                <div></div>
              </header>
              <p>'.htmlspecialchars($step).'</p>
            </div>';
          }
        }
        ?>
      </div>

      <div class="second-column">
        <div>
          <h4>Ingredients</h4>
          <?php 
          // Split ingredients and display them
          $ingredients = explode("\n", $fetch_products['recipeIngredient']);
          foreach ($ingredients as $ingredient) {
            if (trim($ingredient)) {
              echo '<p class="single-tool">'.htmlspecialchars($ingredient).'</p>';
            }
          }
          ?>
        </div>

        <div>
          <h4>Nutrients</h4>
          <?php 
          // Display nutrients
          $nutrients = explode("\n", $fetch_products['recipeNutrients']);
          foreach ($nutrients as $nutrient) {
            if (trim($nutrient)) {
              echo '<p class="single-tool">'.htmlspecialchars($nutrient).'</p>';
            }
          }
          ?>
        </div>
      </div>

      <!-- Hidden fields for product data -->
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['recipeId']; ?>">
      <input type="hidden" name="product_name" value="<?php echo $fetch_products['recipeName']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
    </section>
  </form>
</div>

<?php 
        } // while
    } // if num rows
} // if GET
?>
</section>

<!-- Comment Section -->
<section class="comment-section">
  <h3>Comments </h3>

  <!-- Only allow logged-in users or admins to post comments -->
  <?php if ($user_id || (isset($_SESSION['admin_id']) && $_SESSION['admin_id'])): ?>
    <form method="POST" class="comment-form">
        <textarea name="comment" rows="3" required placeholder="Leave a comment..."></textarea>
        <input type="hidden" name="recipe_id" value="<?php echo $pid; ?>">
        <button type="submit" name="add_comment">Post Comment</button>
    </form>
  <?php else: ?>
    <p>Please <a href="signIn.php">log in</a> to post a comment.</p>
  <?php endif; ?>

 <div class="all-comments">
  <?php 
  // Query to display all comments with user type
  $comments_query = mysqli_query($conn, "
    SELECT c.*, u.username, u.userType 
    FROM comments c 
    JOIN users u ON c.userId = u.userId 
    WHERE c.recipeId = '$pid' 
    ORDER BY c.createdOn DESC
  ") or die('Query failed');

  if (mysqli_num_rows($comments_query) > 0) {
      while ($row = mysqli_fetch_assoc($comments_query)) {
          echo '<div class="single-comment">';

          // Display username and "(admin)" if userType is admin
          $username = htmlspecialchars($row['username']);
          if ($row['userType'] === 'admin') {
              $username .= " (admin)";
          }
          echo '<strong>' . $username . '</strong>';
          echo '<p>' . nl2br(htmlspecialchars($row['comment'])) . '</p>';
          echo '<small>' . date('F j, Y, g:i a', strtotime($row['createdOn'])) . '</small>';

          // Show edit/delete if logged in user is owner or admin
          if ((isset($_SESSION['admin_id']) && $_SESSION['admin_id']) || $row['userId'] == $user_id) {
              echo '
                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="comment_id" value="'.$row['commentId'].'">
                    <button type="submit" name="delete_comment" class="delete-btn">Delete</button>
                    <button type="button" class="edit-btn" onclick="toggleEditForm(\'edit-form-'.$row['commentId'].'\')">Edit</button>
                </form>';

              // Edit form (initially hidden)
              echo '
                <form method="POST" id="edit-form-'.$row['commentId'].'" style="display:none; margin-top:5px;">
                    <textarea name="updated_comment" rows="3" required>'.htmlspecialchars($row['comment']).'</textarea>
                    <input type="hidden" name="comment_id" value="'.$row['commentId'].'">
                    <button type="submit" class="edit-btn" name="edit_comment">Save Changes</button>
                </form>';
          }

          echo '</div>'; // close single comment
      }
  } else {
      echo '<p>No comments yet. Be the first to comment!</p>';
  }
  ?>
</div>

</section>

<!-- Include the footer -->
<?php include 'footer.php';?> 

<!-- Include custom JavaScript -->
<script src="script.js"></script> 
</body>
</html>
