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

    // Handle ingredient addition
    if (isset($_POST['add_ingredient'])) {
        // Sanitize and collect ingredient data
        $ingredient_name = mysqli_real_escape_string($conn, $_POST['ingredient_name']);
        $ingredient_price = mysqli_real_escape_string($conn, $_POST['price']);
        $ingredient_quantity = (int) $_POST['quantity'];

        // Handle image upload
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/'.$image;

        // Check if ingredient name already exists
        $select_ingredient_name = mysqli_query($conn, "SELECT ingredientName FROM `ingredients` WHERE ingredientName = '$ingredient_name'") or die('query failed');
        
        if(mysqli_num_rows($select_ingredient_name)>0){
            $message[] = 'Ingredient name already exists';
        } else {
            // Insert new ingredient into the database
            $insert_ingredient = mysqli_query($conn, 
                "INSERT INTO `ingredients`(`ingredientName`, `price`, `quantity`, `image`) 
                 VALUES ('$ingredient_name','$ingredient_price','$ingredient_quantity','$image')") or die('query failed');

            if ($insert_ingredient) {
                // Check for image size limit
                if ($image_size > 2000000) {
                    $message[] = 'Image size is too large';
                } else {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'Ingredient added successfully';
                }
            }
        }
    }

    // Handle ingredient deletion
    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];

        // Delete the associated image from the server
        $select_delete_image = mysqli_query($conn, "SELECT image FROM `ingredients` WHERE ingredientId = '$delete_id'") or die('query failed');
        $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
        unlink('images/'.$fetch_delete_image['image']);

        // Delete ingredient from the database
        mysqli_query($conn, "DELETE FROM `ingredients` WHERE ingredientId = '$delete_id'") or die('query failed');

        header('location:manageIngredients.php');
    }

    // Handle ingredient update
    if (isset($_POST['update_ingredient'])) {
        // Get updated data from the form
        $update_id = $_POST['update_id'];
        $update_name = $_POST['update_name'];
        $update_price = $_POST['update_price'];
        $update_quantity = (int) $_POST['update_quantity'];
        $update_image = $_FILES['update_image']['name'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'images/'.$update_image;

        // Update ingredient record in the database
        $update_query = mysqli_query($conn, 
            "UPDATE `ingredients` SET 
                `ingredientName`='$update_name', 
                `price`='$update_price', 
                `quantity`='$update_quantity',
                `image`='$update_image' 
            WHERE ingredientId = '$update_id'") or die('query failed');

        if($update_query){
            // Move uploaded image to destination folder
            move_uploaded_file($update_image_tmp_name, $update_image_folder);
            header('location:manageIngredients.php');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ingredients</title>

    <!-- Bootstrap icons and external styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>
</head>
<body>
    <!-- Include Header -->
    <?php include 'header.php'; ?>
    <br><br><br><br><br><br><br><br><br><br><br>

    <!-- Display any alert messages to the admin (e.g. ingredient added, error) -->
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

    <!-- Section to add new ingredients -->
    <section class="add-products form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Input fields for new ingredient data -->
            <div class="input-field"><label>Ingredient Name</label><br><input type="text" name="ingredient_name" required></div><br>
            <div class="input-field"><label>Ingredient Price</label><br><input type="text" name="price" required></div><br>
            <div class="input-field"><label>Ingredient Quantity</label><br><input type="number" name="quantity" required></div><br>
            <div class="input-field"><label>Ingredient Image</label><br><input type="file" name="image" accept="images/*" required></div><br>

            <!-- Submit button -->
            <input type="submit" name="add_ingredient" value="Add Ingredient" class="contact-btn">
        </form>
    </section>

    <!-- Section displaying all existing ingredients in the database -->
    <section class="show-products">
        <div class="box-container">
            <?php 
                $select_ingredients = mysqli_query($conn, "SELECT * FROM `ingredients`") or die('query failed');
                if (mysqli_num_rows($select_ingredients)>0) {
                    while($fetch_ingredients = mysqli_fetch_assoc($select_ingredients)){
            ?>
            <div class="box">
                <!-- Display each ingredient's image and details -->
                <img src="images/<?php echo $fetch_ingredients['image']; ?>">
                <p>Price: £<?php echo $fetch_ingredients['price']; ?></p>
                <p>Quantity: <?php echo $fetch_ingredients['quantity']; ?></p>
                <h4><?php echo $fetch_ingredients['ingredientName']; ?></h4>

                <!-- Links to edit or delete the ingredient -->
                <a href="manageIngredients.php?edit=<?php echo $fetch_ingredients['ingredientId']; ?>" class="edit">Edit</a>
                <a href="manageIngredients.php?delete=<?php echo $fetch_ingredients['ingredientId']; ?>" class="delete" onclick="return confirm('Want to delete this ingredient?');">Delete</a>
            </div>
            <?php 
                    }
                } else {
                    echo '<div class="empty"><p>No ingredients added yet!</p></div>';
                }
            ?>
        </div>
    </section>

    <!-- Section for editing an existing ingredient -->
    <section class="update-container">
    <?php 
        if (isset($_GET['edit'])) {
            $edit_id = $_GET['edit'];
            $edit_query = mysqli_query($conn, "SELECT * FROM `ingredients` WHERE ingredientId = '$edit_id'") or die('query failed');
            if (mysqli_num_rows($edit_query) > 0) {
                while($fetch_edit = mysqli_fetch_assoc($edit_query)){
    ?>
        <form method="POST" enctype="multipart/form-data">
            <!-- Pre-fill form with existing ingredient data -->
            <img src="images/<?php echo $fetch_edit['image']; ?>">
            <input type="hidden" name="update_id" value="<?php echo $fetch_edit['ingredientId']; ?>">

            <label>Ingredient Name</label>
            <input type="text" name="update_name" value="<?php echo $fetch_edit['ingredientName']; ?>" required>

            <label>Ingredient Price</label>
            <input type="number" name="update_price" min="0" value="<?php echo $fetch_edit['price']; ?>" required>

            <label>Ingredient Quantity</label>
            <input type="number" name="update_quantity" value="<?php echo $fetch_edit['quantity']; ?>" required>

            <label>Ingredient Image</label>
            <input type="file" name="update_image" accept="images/*">

            <!-- Update and cancel buttons -->
            <input type="submit" name="update_ingredient" value="Update" class="edit">
            <input type="reset" value="Cancel" class="option-btn btn" id="close-form">
        </form>
    <?php 
                }
            }
        } else {
            echo "<p>No ingredient selected to edit.</p>";
        }
    ?>
    </section>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>

    <!-- JS scripts -->
    <script src="script.js"></script>
</body>
</html> 
