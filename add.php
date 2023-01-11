<?php
	session_start();
	require_once "pdo.php";

	//If the user is not yet logged in, this will display to on the screen to prompt the user to login
	if (! isset($_SESSION['email'])) {
	echo'<h1>Welcome to Peter\'s Automobiles</h1>';
	die('<a href="login.php">Please Log in</a>');
	}

	$fName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
	$lName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$headline = isset($_POST['headline']) ? $_POST['headline'] : '';
	$summary = isset($_POST['summary']) ? $_POST['summary'] : '';

	if (! isset($_SESSION['email'])) {
		die('Not logged in');
	}

	if ( isset($_SESSION["error"])){

		echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
		unset($_SESSION["error"]);
	}


	if (! isset($_POST['first_name']) && isset($_POST['last_name'])
		&& isset($_POST['email'])){
		$_SESSION["error"] = "First name, last name and email are required";
		header('Location: add.php');
		return;
	}



	if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
		&& isset($_POST['headline'])  && isset($_POST['summary']) ){

		if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
			$_SESSION["error"] = "All fields are required";
			header('Location: add.php');
			return;
		} 
		
		if (strlen($_POST['first_name']) < 1 ) {
			$_SESSION["error"] = "Make required";
			header('Location: add.php');
			return;
		}
		else {
			$stmt = $pdo->prepare('INSERT INTO profile
							(first_name, last_name, email, headline, summary ) VALUES (:fn, :ln, :em, :hd, :su)');
			$stmt->execute(array(
				':fn' => htmlentities($_POST['first_name']),			
				':ln' => htmlentities($_POST['last_name']),			
				':em' => htmlentities($_POST['email']),
				':hd' => htmlentities($_POST['headline']),
				':su' => htmlentities($_POST['summary']),
			));
			$_SESSION["success"]= "Record added";
			header('Location: index.php');
			return;
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Peter Mwansa Add Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<h1>Autos Database</h1>
	<?php
	if ( isset($_REQUEST['name']) ) {
		echo "<p>Welcome: ";
		echo htmlentities($_REQUEST['name']);
		echo "</p>\n";
	}
	?>
	<form method="post">
		<p>Add a new Profile</p>
		<p>First Name:
		<input type="text" name="first_name" size="40" value="<?= htmlentities($fName)?>"></p>      <!-- All the data has been escaped used the hmtlentities  -->
        <p>Last Name:
		<input type="text" name="last_name" size="40" value="<?= htmlentities($lName)?>"></p>  <!-- All the data has been escaped used the hmtlentities  -->
		<p>Email:
		<input type="text" name="email" value="<?= htmlentities($email)?>"></p>      <!-- All the data has been escaped used the hmtlentities  -->
		<p>Headline:
		<input type="text" name="headline" value="<?= htmlentities($headline)?>"></p>        <!-- All the data has been escaped used the hmtlentities  -->
		<p>Summary:
		<input type="text" name="summary" value="<?= htmlentities($summary)?>"></p>        <!-- All the data has been escaped used the hmtlentities  -->

		<input type="submit" value="Add">
	</form>
	<form method="post">
		<?php

		if ( isset($_POST['cancel'])){
			header('Location: index.php');
			return;
		}
		?>
		<input type="submit" name="cancel" value="cancel">
	</form>
</body>
</html>

</html>


