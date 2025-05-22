<?php 
	include 'connection.php';
	session_start();

	$admin_id = $_SESSION['admin_name'];

	// Redirect to login if admin is not logged in
	if (!isset($admin_id)) {
		header('location:signIn.php');
	}

	// Handle logout action
	if (isset($_POST['logout'])) {
		session_destroy();
		header('location:signIn.php');
	}
	
	// Delete an order if 'delete' is set in URL
	if (isset($_GET['delete'])) {
		$delete_id = $_GET['delete'];
		mysqli_query($conn, "DELETE FROM `orders` WHERE orderId = '$delete_id'") or die('query failed');
		$message[] = 'Order removed successfully';
		header('location:manageOrders.php');
	}

	// Update order payment status if form is submitted
	if (isset($_POST['update_order']) && isset($_POST['update_payment'])) {
		$order_id = $_POST['order_id'];
		$update_payment = $_POST['update_payment'];

		mysqli_query($conn, "UPDATE `orders` SET paymentStatus = '$update_payment' WHERE orderId='$order_id'") or die('query failed');
		$message[] = "Order payment status updated successfully!";
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- Bootstrap Icons CDN -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>

	<title>Manage Orders</title>
</head>
<body>

	<!-- Include Header -->
	<?php include 'header.php'; ?>

	<!-- Spacing below header -->
	<br><br><br><br><br><br><br><br><br><br><br>

	<!-- Show system messages (e.g., order deleted) -->
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

	<!-- Orders Management Section -->
	<section class="order-container">
		<h1 class="title">Total Orders Placed</h1>
		<div class="box-container">

			<?php 
				// Fetch all orders, including canceled orders
				$select_orders = mysqli_query($conn,"SELECT * FROM `orders`") or die('query failed');
				if (mysqli_num_rows($select_orders) > 0) {
					while($fetch_orders = mysqli_fetch_assoc($select_orders)){
			?>

			<!-- Display Each Order -->
			<div class="box">
				<p>User Name: <span><?php echo $fetch_orders['userName']; ?></span></p>
				<p>User ID: <span><?php echo $fetch_orders['userId']; ?></span></p>
				<p>Placed On: <span><?php echo $fetch_orders['placedOn']; ?></span></p>
				<p>Phone Number: <span><?php echo $fetch_orders['number']; ?></span></p>
				<p>Email: <span><?php echo $fetch_orders['userEmail']; ?></span></p>
				<p>Total Price: <span><?php echo $fetch_orders['totalPrice']; ?></span></p>
				<p>Payment Method: <span><?php echo $fetch_orders['orderMethod']; ?></span></p>
				<p>Address: <span><?php echo $fetch_orders['userAddress']; ?></span></p>
				<p>Order Details: <span><?php echo $fetch_orders['totalRecipes']; ?></span></p>
				
				<!-- Display Payment Status -->
				<p>Status: 
					<span class="<?php echo ($fetch_orders['paymentStatus'] == 'canceled') ? 'canceled-status' : ''; ?>">
						<?php echo ucfirst($fetch_orders['paymentStatus']); ?>
					</span>
				</p>

				<!-- Update Payment Status Form -->
				<form method="post">
					<input type="hidden" name="order_id" value="<?php echo $fetch_orders['orderId']; ?>">

					<!-- Payment Status Dropdown -->
					<select name="update_payment" required>
                     <option value="pending" <?php if($fetch_orders['paymentStatus'] == 'pending') echo 'selected'; ?>>Pending</option>
                     <option value="complete" <?php if($fetch_orders['paymentStatus'] == 'complete') echo 'selected'; ?>>Complete</option>
                     <option value="canceled" <?php if($fetch_orders['paymentStatus'] == 'canceled') echo 'selected'; ?>>Canceled Order</option>
                    </select>
					

					<!-- Update Button -->
					<input type="submit" name="update_order" value="Update Payment" class="btn">

					<!-- Delete Order (allowed for all statuses) -->
                    <a href="manageOrders.php?delete=<?php echo $fetch_orders['orderId']; ?>" onclick="return confirm('Delete this order?');" class="delete">Delete</a>

				</form>
			</div>

			<?php 
					}
				} else {
					// Message shown if no orders exist
					echo '
						<div class="empty">
							<p>No orders have been placed yet!</p>
						</div>
					';
				}		
			?>
		</div>
	</section>

	<!-- Include Footer -->
    <?php include 'footer.php'; ?>

	<!-- JavaScript File -->
	<script src="script.js"></script>
	
</body>
</html>
