<?php 
	include 'connection.php';  
	session_start();

	$admin_id = $_SESSION['admin_name'];

	if (!isset($admin_id)) {
		header('location:signIn.php');
	}

	// If the logout button is pressed, destroy the session and redirect to sign-in page
	if (isset($_POST['logout'])) {
		session_destroy();
		header('location:signIn.php');	
	}
	

	// Delete user from the database
	if (isset($_GET['delete'])) {
		$delete_id = $_GET['delete'];  // Gets the user ID to delete
		
		// Executes the delete query
		mysqli_query($conn, "DELETE FROM `users` WHERE userId = '$delete_id'") or die('query failed');
		$message[] = 'User removed successfully';
		header('location:manageUser.php');
	}
	
	// Promote or demote user between 'admin' and 'user'
	if (isset($_GET['promote'])) {
		$promote_id = $_GET['promote'];  // Gets the user ID to promote/demote

		// Fetch the current user type
		$select_user = mysqli_query($conn, "SELECT userType FROM `users` WHERE userId = '$promote_id'") or die('query failed');
		if (mysqli_num_rows($select_user) > 0) {
			$user = mysqli_fetch_assoc($select_user);
			$current_type = $user['userType'];

			// Toggle between 'admin' and 'user'
			$new_type = $current_type == 'admin' ? 'user' : 'admin';

			// Update the user type in the database
			mysqli_query($conn, "UPDATE `users` SET userType = '$new_type' WHERE userId = '$promote_id'") or die('query failed');
			$message[] = "User role updated to $new_type";
			header('location:manageUser.php');  // Redirect to the manageUser page after update
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Box icon link to include Bootstrap icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css"/>

	<title>Manage Users</title>
</head>
<body>
	<!-- Include Header -->
	<?php include 'header.php'; ?>
	
	<br><br><br><br><br><br><br><br><br><br><br>
	
	<?php 
		// Display success or error messages
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
	
	<!-- Section to display user accounts -->
	<section class="message-container">
		<h1 class="title">User Accounts</h1>
		<div class="box-container">
			<?php 
				// Fetch all users from the database
				$select_users = mysqli_query($conn,"SELECT * FROM `users`") or die('query failed');
				if (mysqli_num_rows($select_users) > 0) {
					// Loop through each user and display their details
					while($fetch_users = mysqli_fetch_assoc($select_users)) {
			?>
				<div class="box">
					<p>User Id: <span><?php echo $fetch_users['userId']; ?></span></p>
					<p>Name: <span><?php echo $fetch_users['userName']; ?></span></p>
					<p>Email: <span><?php echo $fetch_users['userEmail']; ?></span></p>
					<p>User Type: <span style="color:<?php if($fetch_users['userType']=='admin'){echo 'green';} ?>"><?php echo $fetch_users['userType']; ?></span></p>
					
					<!-- Link to delete user with confirmation prompt -->
					<a href="manageUser.php?delete=<?php echo $fetch_users['userId']; ?>" onclick="return confirm('Delete this user?');" class="delete">delete</a>
					
					<!-- Link to promote or demote the user with confirmation prompt -->
					<a href="manageUser.php?promote=<?php echo $fetch_users['userId']; ?>" 
					   onclick="return confirm('<?php echo $fetch_users['userType'] == 'admin' ? 'Demote this admin to user?' : 'Promote this user to admin?'; ?>');" 
					   class="promote" style="background-color: <?php echo $fetch_users['userType'] == 'admin' ? 'red' : '#4caf50'; ?>;">
					   <?php echo $fetch_users['userType'] == 'admin' ? 'Demote' : 'Promote'; ?>
					</a>
				</div>
			<?php 
					}
				} else {
					// If no users are found, display a message
					echo '
						<div class="empty">
							<p>No users found!</p>
						</div>
					';
				}		
			?>
		</div>
	</section>

	<!-- Include Footer -->
	<?php include 'footer.php'; ?>

	<!-- Include Script -->
	<script src="script.js"></script>
	
</body>
</html>
