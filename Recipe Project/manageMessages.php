<?php 
	include 'connection.php';
	session_start();

	$admin_id = $_SESSION['admin_name'];

	// Redirect to sign-in page if admin is not logged in
	if (!isset($admin_id)) {
		header('location:signIn.php');
	}

	// Handle logout
	if (isset($_POST['logout'])) {
		session_destroy(); 
		header('location:signIn.php');
	}
	

	// Delete message from database if 'delete' is set in URL
	if (isset($_GET['delete'])) {
		$delete_id = $_GET['delete'];
		
		mysqli_query($conn, "DELETE FROM `message` WHERE messageId = '$delete_id'") or die('query failed');  // Execute delete query

		header('location:manageMessages.php');
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

	<title>Manage Messages</title>
</head>
<body>

	<!-- Include Header -->
	<?php include 'header.php'; ?>

	<br><br><br><br><br><br><br><br><br><br><br>

	<!-- Show any system messages -->
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

	<!-- Section for displaying messages -->
	<section class="message-container">
		<h1 class="title">Unread Messages</h1>
		<div class="box-container">

			<?php 
				// Fetch all messages from database
				$select_message = mysqli_query($conn,"SELECT * FROM `message`") or die('query failed');
				
				if (mysqli_num_rows($select_message) > 0) {
					while ($fetch_message = mysqli_fetch_assoc($select_message)) {
			?>
			
			<!-- Display individual message -->
			<div class="box">
				<p>Name: <span><?php echo $fetch_message['userName']; ?></span></p>
				<p>Email: <span><?php echo $fetch_message['userEmail']; ?></span></p>
				<p>Message Content: <?php echo $fetch_message['messageContent']; ?></p>
				
				<!-- Delete message link with confirmation -->
				<a href="manageMessages.php?delete=<?php echo $fetch_message['messageId']; ?>" onclick="return confirm('Delete this message?');" class="delete">delete</a>
			</div>

			<?php 
					}
				} else {
					// Show message if no messages are found
					echo '
						<div class="empty">
							<p>No messages currently!</p>
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
