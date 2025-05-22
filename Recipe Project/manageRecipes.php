<?php 
    include 'connection.php';
    session_start();

    // Get current admin session ID
    $admin_id = $_SESSION['admin_name'];

    // Redirect to login if admin is not logged in
    if (!isset($admin_id)) {
        header('location:signIn.php');
    }

    // Logout functionality
    if (isset($_POST['logout'])) {
        session_destroy();
        header('location:signIn.php');    
    }

    // Handle product addition
    if (isset($_POST['add_product'])) {
        // Sanitize and collect product data
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_price = mysqli_real_escape_string($conn, $_POST['price']);
        $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $method = mysqli_real_escape_string($conn, $_POST['method']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $prepTime = (int) $_POST['prepTime'];
        $cookTime = (int) $_POST['cookTime'];
        $calories = (int) $_POST['calories'];
        $nutrients = mysqli_real_escape_string($conn, $_POST['nutrients']);
        
        // Handle image upload
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/'.$image;

        // Check if product name already exists
        $select_product_name = mysqli_query($conn, "SELECT recipeName FROM `recipes` WHERE recipeName = '$product_name'") or die('query failed');
        
        if(mysqli_num_rows($select_product_name)>0){
            $message[] = 'recipe name already exists';
        } else {
            // Insert new recipe into database
            $insert_product = mysqli_query($conn, 
                "INSERT INTO `recipes`(`recipeName`, `price`, `recipeDescription`, `image`, `recipeMethod`, `recipeIngredient`, `prepTime`, `cookTime`, `calories`, `recipeNutrients`) 
                 VALUES ('$product_name','$product_price','$product_detail','$image','$method','$ingredients','$prepTime','$cookTime','$calories','$nutrients')") or die('query failed');

            if ($insert_product) {
                // Check for image size limit
                if ($image_size > 2000000) {
                    $message[] = 'image size is too large';
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'product added successfully';
                }
            }
        }
    }

    // Handle product deletion
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];

        // Delete the associated image from the server
        $select_delete_image = mysqli_query($conn, "SELECT image FROM `recipes` WHERE recipeId = '$delete_id'") or die('query failed');
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        unlink('images/'.$fetch_delete_image['image']);

        // Delete recipe from all related tables
        mysqli_query($conn, "DELETE FROM `recipes` WHERE recipeId = '$delete_id'") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE recipeId = '$delete_id'") or die('query failed');
        mysqli_query($conn, "DELETE FROM `bookmark` WHERE recipeId = '$delete_id'") or die('query failed');

        header('location:manageRecipes.php');
    }

    // Handle product update
    if (isset($_POST['updte_product'])) {
        // Get updated data from the form
        $update_id = $_POST['update_id'];
        $update_name = $_POST['update_name'];
        $update_price = $_POST['update_price'];
        $update_detail = $_POST['update_detail'];
        $update_method = $_POST['update_method'];
        $update_ingredients = $_POST['update_ingredients'];
        $update_prepTime = $_POST['update_prepTime'];
        $update_cookTime = $_POST['update_cookTime'];
        $update_calories = $_POST['update_calories'];
        $update_nutrients = $_POST['update_nutrients'];
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'images/'.$update_image;

        // Update recipe record in database
        $update_query = mysqli_query($conn, 
            "UPDATE `recipes` SET 
                `recipeName`='$update_name', 
                `price`='$update_price', 
                `recipeDescription`='$update_detail',
                `recipeMethod`='$update_method',
                `recipeIngredient`='$update_ingredients',
                `prepTime`='$update_prepTime',
                `cookTime`='$update_cookTime',
                `calories`='$update_calories',
                `recipeNutrients`='$update_nutrients',
                `image`='$update_image' 
            WHERE recipeId = '$update_id'") or die('query failed');

        if($update_query){
            // Move uploaded image to destination folder
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            header('location:manageRecipes.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipes</title>

    <!-- Bootstrap icons and external styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>
    <br><br><br><br><br><br><br><br><br><br><br>

    <!-- Display any alert messages to the admin (e.g. recipe added, error) -->
    <?php 
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

    <!-- Section to add new recipes -->
    <section class="add-products form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Input fields for new recipe data -->
            <div class="input-field"><label>Recipe Name</label><br><input type="text" name="name" required></div><br>
            <div class="input-field"><label>Recipe Price</label><br><input type="text" name="price" required></div><br>
            <div class="input-field"><label>Recipe Detail</label><br><textarea name="detail" required></textarea></div><br>
            <div class="input-field"><label>Recipe Method</label><br><textarea name="method" required></textarea></div><br>
            <div class="input-field"><label>Recipe Ingredients</label><br><textarea name="ingredients" required></textarea></div><br>
            <div class="input-field"><label>Preparation Time (mins)</label><br><input type="number" name="prepTime" min="0" required></div><br>
            <div class="input-field"><label>Cooking Time (mins)</label><br><input type="number" name="cookTime" min="0" required></div><br>
            <div class="input-field"><label>Calories (Kcal)</label><br><input type="number" name="calories" min="0" required></div><br>
            <div class="input-field"><label>Recipe Nutrients</label><br><textarea name="nutrients" required></textarea></div><br>
            <div class="input-field"><label>Recipe Image</label><br><input type="file" name="image" accept="images/*" required></div><br>

            <!-- Submit button -->
            <input type="submit" name="add_product" value="Add Recipe" class="contact-btn">
        </form>
    </section>

    <!-- Section displaying all existing recipes in the database -->
    <section class="show-products">
        <div class="box-container">
            <?php 
                $select_products = mysqli_query($conn, "SELECT * FROM `recipes`") or die('query failed');
                if (mysqli_num_rows($select_products)>0) {
                    while($fetch_products = mysqli_fetch_assoc($select_products)){
            ?>
            <div class="box">
                <!-- Display each recipe's image and details -->
                <img src="images/<?php echo $fetch_products['image']; ?>">
                <p>Price: £<?php echo $fetch_products['price']; ?></p>
                <p>Prep: <?php echo $fetch_products['prepTime']; ?> mins | Cook: <?php echo $fetch_products['cookTime']; ?> mins</p>
                <p>Calories: <?php echo $fetch_products['calories']; ?></p>
                <h4><?php echo $fetch_products['recipeName']; ?></h4>

                <!-- Links to view, edit, or delete the recipe -->
                <a href="recipeDetail.php?recipeId=<?php echo $fetch_products['recipeId']; ?>" class="details-btn">⮞Show Details</a><br>
                <a href="manageRecipes.php?edit=<?php echo $fetch_products['recipeId']; ?>" class="edit">Edit</a>
                <a href="manageRecipes.php?delete=<?php echo $fetch_products['recipeId']; ?>" class="delete" onclick="return confirm('Want to delete this recipe?');">Delete</a>
            </div>
            <?php 
                    }
                } else {
                    echo '<div class="empty"><p>No products added yet!</p></div>';
                }
            ?>
        </div>
    </section>

    <!-- Section for editing an existing recipe -->
    <section class="update-container">
    <?php 
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM `recipes` WHERE recipeId = '$edit_id'") or die('query failed');
            if (mysqli_num_rows($edit_query) > 0) {
                while($fetch_edit = mysqli_fetch_assoc($edit_query)){
    ?>
        <form method="POST" enctype="multipart/form-data">
            <!-- Pre-fill form with existing recipe data -->
            <img src="images/<?php echo $fetch_edit['image']; ?>">
            <input type="hidden" name="update_id" value="<?php echo $fetch_edit['recipeId']; ?>">

            <label>Recipe Name</label>
            <input type="text" name="update_name" value="<?php echo $fetch_edit['recipeName']; ?>" required>

            <label>Recipe Price</label>
            <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>" required>

            <label>Recipe Detail</label>
            <textarea name="update_detail" required><?php echo $fetch_edit['recipeDescription']; ?></textarea>

            <label>Recipe Method</label>
            <textarea name="update_method" required><?php echo $fetch_edit['recipeMethod']; ?></textarea>

            <label>Recipe Ingredients</label>
            <textarea name="update_ingredients" required><?php echo $fetch_edit['recipeIngredient']; ?></textarea>

            <label>Preparation Time (mins)</label>
            <input type="number" name="update_prepTime" min="0" value="<?php echo $fetch_edit['prepTime']; ?>" required>

            <label>Cooking Time (mins)</label>
            <input type="number" name="update_cookTime" min="0" value="<?php echo $fetch_edit['cookTime']; ?>" required>

            <label>Calories (Kcal)</label>
            <input type="number" name="update_calories" min="0" value="<?php echo $fetch_edit['calories']; ?>" required>

            <label>Recipe Nutrients</label>
            <textarea name="update_nutrients" required><?php echo $fetch_edit['recipeNutrients']; ?></textarea>

            <label>Recipe Image</label>
            <input type="file" name="update_image" accept="images/*">

            <!-- Update and cancel buttons -->
            <input type="submit" name="updte_product" value="Update" class="edit">
            <input type="reset" value="Cancel" class="option-btn btn" id="close-form">
        </form>
    <?php 
                }
            }
        } else {
            echo "<p>No recipe selected to edit.</p>";
        }
    ?>
    </section>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>

    <!-- JS scripts -->
    <script src="script.js"></script>
</body>
</html>
