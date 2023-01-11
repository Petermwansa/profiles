<?php
require_once "pdo.php";
session_start();

//If the user is not yet logged in, this will display to on the screen to prompt the user to login
if (! isset($_SESSION['email'])) {
echo'<h1>Welcome to Peter\'s Automobiles</h1>';
die('<a href="login.php">Please Log in</a>');
}

if ( isset($_POST['make']) 
     && isset($_POST['model'])
     && isset($_POST['year']) 
     && isset($_POST['mileage']) 
     && isset($_POST['autos_id']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1  || 
         strlen($_POST['model']) < 1 || 
         strlen($_POST['year']) < 1 || 
         strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?user_id=".$_POST['autos_id']);
        return;
    }



    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing The ID for the Automobile";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for the ID of the Automobile';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$ma = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>


<!DOCTYPE html>
<html>
<head>
	<title>Peter Mwansa Edit Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>

<body>
<p>Edit Automobile</p>
<form method="post">
    <p>Make:
    <input type="text" name="make" value="<?= $ma ?>"></p>
    <p>Model:
    <input type="text" name="model" value="<?= $mo ?>"></p>
    <p>Year:
    <input type="text" name="year" value="<?= $y ?>"></p>
    <p>Mileage:
    <input type="text" name="mileage" value="<?= $mi ?>"></p>

    <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
    <button type="submit" value="Save">Save</button>
    <a href="index.php">Cancel</a>
</form>
</body>
</html>



