<html>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
		<a href="user.php">User</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="home.php">Search</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="fun.php">Fun</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="login.php">Logout</a>
    </ul>
</div>
<?php
include 'functions.php';
function printUserProfile($result) { //prints results from a select statement
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	echo "<font size='10'><b>$row[USERNAME]'s</b> User Profile</font><br>";
	echo "<font size='4'>First Name: <b>$row[FIRSTNAME]</b>&nbsp;&nbsp;&nbsp;&nbsp;
						Last Name: <b>$row[LASTNAME]</b><br>
						Address: <b>$row[ADDRESS]</b><br>
						Email: <b>$row[EMAIL]</b>&nbsp;&nbsp;&nbsp;&nbsp;
						Phone Number: <b>$row[PHONENUMBER]</b></font><br>";
	echo "<hr>";

}
function printUserReservation($result) {
	echo "<br><b><font size='5'>My Upcoming Reservations:</font></b><br>";
	echo "<table>";
	echo "<tr><th>Date</th><th>Time</th><th>Confirmation #</th><th align='left'>Restaurant</th><th align='left'>Address</th><th>Guests</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		$confNumber = $row["CONFNUMBER"];
		echo "<tr><td>" . $row["DATERESERVED"] . "</td>
				<td>" . $row["FROMTIME"] . "</td>
				<td align='center'>" . $row["CONFNUMBER"] . "</td>
				<td>" . $row["RESTNAME"] . "</td>
				<td>" . $row["RESTADDRESS"] . "</td>
				<td align='center'>" . $row["NUMOFPEOPLE"] . "</td>
				<td><form method='POST' action='user.php'>
					<input type='hidden' value='$confNumber' name='confNumber'>
					<input type='submit' value='Cancel'></form></td></tr>";
		
	}
	echo "</table>";
	// echo '<form method="POST" action="reservation.php">
			// <p><input type="submit" value="Make A New Reservation"</p>
			// </form>';
	echo "-----------------------------------------------------<br>";
}

function printUserReview($result) { //prints results from a select statement
	echo "<br><b><font size='5'>My Reviews:</font></b><br>";
	echo "<table>";
	echo "<tr><th>Review ID </th><th align='left'>Restaurant     </th><th align='left'>Address    </th><th>Rating </th><th>Comments</th></tr>";
	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		$reviewID = $row["REVIEWID"];
		echo "<tr><td align='center'>" . $row["REVIEWID"] . "</td>
				<td>" . $row["RESTNAME"] . "</td>
				<td>" . $row["RESTADDRESS"] . "</td>
				<td align='center'>" . $row["RATING"] . "</td>
				<td>" . $row["COMMENTS"] . "</td>
				<td>
					<form method='POST' action='user.php'>
					<input type='hidden' value='$reviewID' name='reviewID'>
					<input type='submit' value='Remove'></form></td></tr>";
	}
	echo "</table>";
	echo "-----------------------------------------------------<br>";

}

// Connect Oracle...
if ($db_conn) {
	session_save_path('PHPSessions');
	session_start();
	
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	
	$userProfile = executePlainSQL("select * from users where username='".$username."'");
	printUserProfile($userProfile);
	
	$userReservation = executePlainSQL("select * from reservation where username='".$username."' Order by dateReserved ASC");
	printUserReservation($userReservation);
	
	$userReview = executePlainSQL("select * from review where username='".$username."'");
	printUserReview($userReview);
	
	if (array_key_exists('updatePassword', $_POST)) {
		$currentPassword = $_POST['currentPassword'];
		$newPassword = $_POST['newPassword'];
		if(!empty($newPassword) && !empty($currentPassword)) {
			if ($password == $currentPassword) {
				executePlainSQL("update users set password='".$newPassword."' where username='".$username."'");
		OCICommit($db_conn);
		$_SESSION['password'] = $newPassword;
		?>
			<script>
			alert("Password successfully updated!");
			</script>
			<?php
				
			}else {
				?>
			<script>
			alert("Password Update Failed: Current Password Incorrect!");
			</script>
			<?php
		}
		}else {
			?>
			<script>
			alert("Passwords cannot be empty!");
			</script>
			<?php
		}
		
	} else if (array_key_exists('updateFirstName', $_POST)) {
			$newFirstName = $_POST['newFirstName'];
			if(!empty($newFirstName)) {
			executePlainSQL("update users set firstName='".$newFirstName."' where username='".$username."'");
			OCICommit($db_conn);
			header("Location: user.php");
			}
		else {
			?>
			<script>
			alert("First Name cannot be empty!");
			</script>
			<?php
		}
			
	} else if (array_key_exists('updateLastName', $_POST)) {
			$newLastName = $_POST['newLastName'];
			if(!empty($newLastName)) {
			executePlainSQL("update users set lastName='".$newLastName."' where username='".$username."'");
			OCICommit($db_conn);
			header("Location: user.php");
			}
		else {
			?>
			<script>
			alert("Last Name cannot be empty!");
			</script>
			<?php
		}

	} else if (array_key_exists('updateEmail', $_POST)) {
			$newEmail = $_POST['newEmail'];
			if(!empty($newEmail)) {
			executePlainSQL("update users set email='".$newEmail."' where username='".$username."'");
			OCICommit($db_conn);
			header("Location: user.php");
			}
		else {
			?>
			<script>
			alert("Email cannot be empty!");
			</script>
			<?php
		}
			
	} else if (array_key_exists('updateAddress', $_POST)) {
			$newAddress = $_POST['newAddress'];
			if(!empty($newAddress)) {
			executePlainSQL("update users set address='".$newAddress."' where username='".$username."'");
			OCICommit($db_conn);
			header("Location: user.php");
			}
		else {
			?>
			<script>
			alert("Address cannot be empty!");
			</script>
			<?php
		}
			
	} else if (array_key_exists('updatePhoneNumber', $_POST)) {
			$newPhoneNumber = $_POST['newPhoneNumber'];
			if(!empty($newPhoneNumber)) {
			executePlainSQL("update users set phoneNumber='".$newPhoneNumber."' where username='".$username."'");
			OCICommit($db_conn);
			header("Location: user.php");
			}
		else {
			?>
			<script>
			alert("Phone Number cannot be empty!");
			</script>
			<?php
		}
		
	} else if (isset($_POST['reviewID'])){
			$reviewID = $_POST['reviewID'];
			executePlainSQL("delete from review where reviewID='".$reviewID."'");
			OCICommit($db_conn);
			header("location: user.php");
	
	} else if (isset($_POST['confNumber'])){
			$confNumber = $_POST['confNumber'];
			echo"$confNumber";
			executePlainSQL("delete from reservation where confNumber='".$confNumber."'");
			OCICommit($db_conn);
			header("location: user.php");
			
	} else if (isset($_POST['deleteUser'])){
			executePlainSQL("delete from users where userName='".$username."'");
			OCICommit($db_conn);
			session_destroy();
			echo "<script language='javascript'>alert('User Successfully Deleted: Please Create New Account!');window.location.href='login.php';</script>";
			
	} else if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: login.php");
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

<p><b><font size='5'>Update User Profile</font></b></p>
<form method="POST" action="user.php">
Password:	<input type="password" placeholder="Current Password" name="currentPassword" size="25">&nbsp;&nbsp;
			<input type="password" placeholder="New Password" name="newPassword" size="25">
<input type="submit" value="Update" name="updatePassword"></form>

<form method="POST" action="user.php">
First Name: <input type="text" placeholder="New First Name" name="newFirstName" size="25">
<input type="submit" value="Update" name="updateFirstName"></form>

<form method="POST" action="user.php">
Last Name: <input type="text" placeholder="New Last Name" name="newLastName" size="25">
<input type="submit" value="Update" name="updateLastName"></form>

<form method="POST" action="user.php">
Email:	<input type="text" placeholder="New Email" name="newEmail" size="25">
<input type="submit" value="Update" name="updateEmail"></form>

<form method="POST" action="user.php">
Address:	<input type="text" placeholder="New Address" name="newAddress" size="25">
<input type="submit" value="Update" name="updateAddress"></form>

<form method="POST" action="user.php">
Phone Number:	<input type="text" placeholder="New Phone Number" name="newPhoneNumber" size="25">
<input type="submit" value="Update" name="updatePhoneNumber"></form>

<form method="POST" action="user.php">
<input type="submit" value="Delete User" name="deleteUser"></form>
</html>