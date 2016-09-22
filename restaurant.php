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
function printRestaurant($result) {
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	echo "<b><font size='10'>$row[RESTNAME]</font></b><br>";
	echo "<font size='5'><b>Address:</b> $row[RESTADDRESS]&nbsp;&nbsp;&nbsp;&nbsp;
						<b>CuisineType:</b> $row[CUISINETYPE]<br>
						<b>Phone Number:</b> $row[PHONENUMBER]&nbsp;&nbsp;&nbsp;&nbsp;
						<b>Hours of Operation:</b> $row[HOURS]</font><br>";
	// echo "---------------------------------------------------------------------------------------------------------<br>";
	echo "<hr>";
}	

function printReview($result) { //prints results from a select statement
	echo "<br><br><b><font size='5'>Ratings and Reviews:</font></b><br>";
	echo "<table>";
	echo "<tr><th>Review ID</th><th>Username</th><th>Rating</th><th>Comment</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td align='center'>" . $row["REVIEWID"] . "</td>
				<td align='center'>" . $row["USERNAME"] . "</td>
				<td align='center'>" . $row["RATING"] . "</td>
				<td>" . $row["COMMENTS"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
	echo '<form method="POST" action="review.php">
			<p><input type="submit" value="Add Your Review"</p>
			</form>';
	
}

function printReservations($result) {
	echo "<br><br><b><font size='5'>Upcoming Reservations:</font></b><br>";
	echo "<table>";
	echo "<tr><th>Confirmation #</th><th>Date</th><th>Time</th><th>Username</th><th>Guests</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr><td align='center'>" . $row["CONFNUMBER"] . "</td>
				<td>" . $row["DATERESERVED"] . "</td>
				<td>" . $row["FROMTIME"] . "</td>
				<td align='center'>" . $row["USERNAME"] . "</td>
				<td align='center'>" . $row["NUMOFPEOPLE"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
	echo '<form method="POST" action="ReservationPage.php">
			<p><input type="submit" value="Make A Reservation"</p>
			</form>';
	echo "-----------------------------------------";
}

function printFood($result) {
	echo "<br><b><font size='5'>Food Items:</font></b><br>";
	echo "<table>";
	echo "<tr><th align='left'>Name</th><th align='left'>Description</th><th>Price</th><th>Ordered</th></tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		$price = number_format((float)$row["PRICE"], 2, '.', '');
		echo "<tr><td>" . $row["FOODNAME"] . "</td>
				<td>" . $row["DESCRIPTION"] . "</td>
				<td align='center'>$".$price."</td>
				<td align='center'>" . $row["NUMOFTIMESORDERED"] . "</td></tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
	echo "-----------------------------------------";
}
// Connect Oracle...
if ($db_conn) {
	session_save_path('PHPSessions');
	session_start();
	$restName = $_SESSION['restName'];
	$restAddress = $_SESSION['restAddress'];
	// $restName = 'Seasons in the Park';
	// $restAddress = 'West 33rd Avenue and Cambie Street';
	
	$restaurantResult = executePlainSQL("select * from restaurant where restName='".$restName."' and restAddress='".$restAddress."'");
	printRestaurant($restaurantResult);
	
	$foodResult = executePlainSQL("select * from food where restName='".$restName."' and restAddress='".$restAddress."'");
	printFood($foodResult);
	
	$reservationResult = executePlainSQL("Select * From Reservation V, Restaurant R Where R.restAddress=V.restAddress AND R.restAddress='".$restAddress."' AND R.restName=V.restName AND R.restname='".$restName."' Order By dateReserved ASC");
	printReservations($reservationResult);
	
	$reviewResult = executePlainSQL("select * from review where restName='".$restName."' and restAddress='".$restAddress."'");
	printReview($reviewResult);
	
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

?>
</html>