<html>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
		<a href="user.php">User</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="home.php">Search</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="fun.php">Fun</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="login.php">Logout</a>
    </ul>
</div>
<h1>Find Fun Facts!</h1>

<form method="GET" action="fun.php">
	<p style="font-size:120%">
		Find total price of all Foods. <input type="submit" value="Go" name="find2">
	</p>
</form>

<form method="GET" action="fun.php">
	<p style="font-size:120%">
		Find the cheapest and most expensive food. <input type="submit" value="Go" name="find3">
	</p>
</form>

<form method="GET" action="fun.php">
	<p style="font-size:120%">
		Find restaurant reviews with rating >= 	<input type="text" name="rating" size="1">
										<input type="submit" value="Go" name="find1">
	</p>
</form>

<form method="GET" action="fun.php">
	<p style="font-size:120%">
		Find number of reviews for each rating. <input type="submit" value="Go" name="find4">
	</p>
</form>

<form method="GET" action="fun.php">
	<p style="font-size:120%">
		Find the most talked about restaurants. <input type="submit" value="Go" name="find5">
	</p>
</form>

<?php
	include 'functions.php';			
	// Connect Oracle...
	if ($db_conn) {
		session_save_path('PHPSessions');
		session_start();
		
		if(array_key_exists('find1', $_GET)) {
			$rating = $_GET['rating'];
			if (!empty($rating)) {
			$result = executePlainSQL("select R.restName, R.restAddress, V2.rating, V2.comments
										from Restaurant R, Review V2
										where V2.restName=R.restName AND V2.restAddress=R.restAddress AND
										NOT EXISTS (select V.restName, V.restAddress
													from review V
													where V.restAddress=R.restAddress AND 
														V.restName=R.restName AND 
														V.rating<'".$rating."')");
			printRestaurant($result);
			}else {
				?>
			<script>
			alert("Error: Empty field detected!");
			</script>
			<?php
			}
		} else if(array_key_exists('find2', $_GET)){
			$result = executePlainSQL("select sum(price), count(foodname) from food");
			printRestaurant2($result);
			
		} else if(array_key_exists('find3', $_GET)){
			$result = executePlainSQL("select r.restname, r.foodname, max(r.price), a.restname, a.foodname, min(a.price)
										from food r, food a
										group by r.restname, r.foodname, a.restname, a.foodname");
			printRestaurant3($result);
			
		} else if(array_key_exists('find4', $_GET)){
			$result = executePlainSQL("select rating, count(comments) 
									from review 
									group by rating
									order by rating desc");
			printRestaurant4($result);
			
		} else if(array_key_exists('find5', $_GET)){
			$result = executePlainSQL("select restname, restAddress, count(comments) as c
									from review 
									group by restname, restaddress
									order by c desc");
			printRestaurant5($result);
			
		} else if (isset($_POST['restName']) && isset($_POST['restAddress'])) {
			$_SESSION['restName'] = $_POST['restName'];
			$_SESSION['restAddress'] = $_POST['restAddress'];
			header("location: restaurant.php");
		}
		
		OCILogoff($db_conn);
	} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

function printRestaurant($result) { //prints results from a select statement
	echo "<table>";
	echo "<tr>
	<th>Restaurant Name</th>
	<th>Address</th>
	<th>Rating</th>
	<th>Review</th>
	</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		$restName = $row["RESTNAME"];
		$restAddress = $row["RESTADDRESS"];
		$rating = number_format((float)$row[3], 1, '.', '');
		$price = number_format((float)$row[4], 2, '.', '');
		echo "<tr>
		<td align='center'>" . $row["RESTNAME"] . "</td>
		<td align='center'>" . $row["RESTADDRESS"] . "</td>
		<td align='center'>" . $row["RATING"] . "</td>
		<td>" . $row["COMMENTS"] . "</td>
		</tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";

}

function printRestaurant2($result) { 
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	$n = $row[1];
	$price = $row[0];
	
	echo"<font size='5'>Total price for all <b>$n</b> food items is <b>$$price</b></font>";
}

function printRestaurant3($result) { 
	$row = OCI_Fetch_Array($result, OCI_BOTH);
	$rmax = $row[0];
	$maxprice = $row[2];
	$maxname = $row[1];
	$rmin = $row[3];
	$minprice = $row[5];
	$minname = $row[4];
	
	echo"<font size='5'>Min: <b>$rmin</b> has <b>$minname</b> for <b>$$minprice</b><br>
						Max: <b>$rmax</b> has <b>$maxname</b> for <b>$$maxprice</b></font>";
}

function printRestaurant4($result) { 
	echo "<table>";
	echo "<tr>
	<th>Rating</th>
	<th># of Reviews</th>
	</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
		<td align='center'>" . $row[0] . "</td>
		<td align='center'>" . $row[1] . "</td>
		</tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
}

function printRestaurant5($result) { 
	echo "<table>";
	echo "<tr>
	<th>Restaurant</th>
	<th>Address</th>
	<th># of Reviews</th>
	</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		echo "<tr>
		<td align='center'>" . $row[0] . "</td>
		<td align='center'>" . $row[1] . "</td>
		<td align='center'>" . $row[2] . "</td>
		</tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";
}
?>
</html>
