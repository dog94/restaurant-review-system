<html>
<p> <font size="16"> Make a Reservation </font> </p>
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
    	// $username = 'Lex';
    	// $restName = 'Seasons in the Park';
    	// $restAddress = 'West 33rd Avenue and Cambie Street';
    	
		echo "<font size='6'>$restName - $restAddress</font>";
?>
<p>Enter Date, time and # of guests to make a reservation.</p>
<form method="GET" action="ReservationPage.php">
<!--refresh page when submit-->

<p>	Date: <input type="date" name="date"></p>
<p>	Time: <input type="text" name="fromTimeHH" placeholder="HH" size="2">:
		<input type="text" name="fromTimeMM" placeholder="MM" size="2">
		<input type="text" name="fromTimeAM" placeholder="AM/PM" size="2"><br></p>
<p>	# of Guests: <input type="text" name="numOfPeople" placeholder="eg. 2" size="2"></p>
<input type="submit" value="Reserve" name="reserve">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<input type="submit" value="Cancel" name="cancel">
</form>
<?php
    	if(array_key_exists('reserve', $_GET)){
			
    		$date = $_GET['date'];
			$timeHH = str_pad($_GET['fromTimeHH'], 2, '0', STR_PAD_LEFT);
			$timeMM = str_pad($_GET['fromTimeMM'], 2, '0', STR_PAD_LEFT);
			$timeAM = strtoupper($_GET['fromTimeAM']);
			$numGuest = $_GET['numOfPeople'];
			if (!empty($date) && !empty($timeHH) && !empty($timeMM) && !empty($timeAM) && !empty($numGuest)){
				if (($timeAM == "AM") || ($timeAM == "PM")){
					if ($timeHH >=0 && $timeHH < 13 && $timeMM >= 0 && $timeMM < 60) {
			$time = "$timeHH:$timeMM$timeAM";
			$randNum = rand(0, 999999);
			executePlainSQL("insert into Reservation values (".$randNum.", '".$time."', '".$date."', ".$numGuest.", '".$restName."', '".$restAddress."', 1, '".$username."')");
			OCICommit($db_conn);
			echo "<script language='javascript'>alert('Reservation Made Successfully!');window.location.href='restaurant.php';</script>"; 
					} else {
						?>
			<script>
			alert("Error: Invalid Time Detected!");
			</script>
			<?php
		}
				}else {
						?>
			<script>
			alert("Error: Invalid Time Detected!");
			</script>
			<?php
			}
			} else {
			?>
			<script>
			alert("Error: Empty Field Detected!");
			</script>
			<?php
		}
		
	}else if (isset($_GET['cancel'])){
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
	function printRSVP($result){
		echo "<table>";
		echo "<tr>
		<th>Conformation Number</th>
		<th>from</th>
		<th>date</th>
		<th>Num of Guests</th>
		<th>Restaurant</th>
		<th>Address</th>
		<th>Table Number</th>
		<th>User Name</th>
		</tr>";

		while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
			echo "<tr>
			<td>" . $row["CONFNUMBER"] . "</td>
			<td>" . $row["FROMTIME"] . "</td>
			<td>" . $row["DATERESERVED"] . "</td>
			<td>" . $row["NUMOFPEOPLE"] . "</td>
			<td>" . $row["RESTNAME"] . "</td>
			<td>" . $row["RESTADDRESS"] . "</td>
			<td>" . $row["TABLENUMBER"] . "</td>
			<td>" . $row["USERNAME"] . "</td>
			</tr>"; //or just use "echo $row[0]" 
		}
		echo "</table>";
	}
?>
</html>