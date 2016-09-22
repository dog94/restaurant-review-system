<html>
<p> <font size="16"> Write Your Review </font> </p>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
		<li><a href="user.php">User</a></li>
        <li><a href="home.php">Home</a></li>
         <li><a href="login.php">Logout</a></li>
    </ul>
</div>
<?php

include 'functions.php';
// Connect Oracle...
if ($db_conn) {
	session_save_path('PHPSessions');
	session_start();
	$username = $_SESSION['username'];
	$restName = $_SESSION['restName'];
	$restAddress = $_SESSION['restAddress'];
	
	echo "<font size='6'>$restName - $restAddress</font>";
?>
<form method="POST" action="review.php">
<p><font size="2"> Rating </font> 
<input type="text" name="review-rating" size="4">

&nbsp;&nbsp; <font size="2"> Title of Review </font> 
<input type="text" name="review-title" size="30"></p>

<p> <font size="2"> Comment </font> </p>
<p> <textarea name="review-comment" rows="5" cols="40"></textarea></p>

<input type="submit" value="Post" name="review-create">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
<input type="submit" value="Cancel" name="cancel">
</form>
<?php
		if (array_key_exists('review-create', $_POST)) {
			if (!empty($_POST['review-rating']) && !empty($_POST['review-title']) && !empty($_POST['review-comment'])){
			//Getting the values from user and insert data into the table
			$tuple = array (
				":bind1" => rand(1,1000000),
				":bind2" => $restName,
				":bind3" => $restAddress,
				":bind4" => $username,
				":bind5" => $_POST['review-rating'],
				":bind6" => $_POST['review-title'],
				":bind7" => $_POST['review-comment'],
			);
			$alltuples = array (
				$tuple
			);
			OCICommit($db_conn);
			
			executeBoundSQL("
			INSERT INTO Review
			VALUES (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)
			", $alltuples);
			
			OCICommit($db_conn);
			echo "<script language='javascript'>alert('Review Successfully added!');window.location.href='restaurant.php';</script>"; 
			} else {
			?>
			<script>
			alert("Error: Empty Field Detected!");
			</script>
			<?php
		}
			
		}else if (isset($_POST['cancel'])){
			header("location: restaurant.php");
			

		}else if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: restaurant.php");
	} else {
	}

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}
?>

</html>