<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
		<a href="user.php">User</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="home.php">Search</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="fun.php">Fun</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="login.php">Logout</a>
    </ul>
</div>
<h1>Search To Find Restaurants</h1>

<form method="GET" action="home.php">
	<p>
		Restaurant Name: <input type="text" name="restName" size="15">
		Address:		<input type="text" name="restAddress" size="15">
		Cuisine Type: 	<input type="text" name="cuisineType" size="8">
						<input type="submit" value="Search" name="search">
						<input type="submit" value="Display All" name="all">
	</p>
</form>

<?php
	include 'functions.php';			
	// Connect Oracle...
	if ($db_conn) {
		session_save_path('PHPSessions');
		session_start();
		
		if(array_key_exists('all', $_GET)) {
			$result = executePlainSQL("select r.restName, r.restAddress, r.cuisineType, avg(a.rating), avg(f.price) 
										from restaurant r, review a, food f
										where r.restname=a.restname and 
												r.restaddress=a.restaddress and
												r.restname=f.restname and
												r.restaddress=f.restAddress
										group by r.restName, r.restAddress, r.cuisineType
										order by r.restname");
			// $result = executePlainSQL("select * from restaurant");
			printRestaurant($result);
			echo '<form method="POST" action="home.php">
			<p><input type="submit" value="Hide The Restaurants"</p>
			</form>
			';
		} else if(array_key_exists('search', $_GET)){
			$cuisineType = $_GET['cuisineType'];
			$restName = $_GET['restName'];
			$restAddress = $_GET['restAddress'];
			// $result = executePlainSQL("select * 
										// from restaurant 
										// where (lower(cuisineType) like lower('%".$cuisineType."%')) 
										// and (lower(restName) like lower('%".$restName."%')) 
										// and (lower(restAddress) like lower('%".$restAddress."%'))");
			$result = executePlainSQL("select r.restName, r.restAddress, r.cuisineType, avg(a.rating), avg(f.price) 
										from restaurant r, review a, food f
										where r.restname=a.restname
										and r.restname=f.restname
										and (lower(r.cuisineType) like lower('%".$cuisineType."%')) 
										and (lower(r.restName) like lower('%".$restName."%')) 
										and (lower(r.restAddress) like lower('%".$restAddress."%'))
										group by r.restName, r.restAddress, r.cuisineType
										order by r.restname");
			printRestaurant($result);
		} else if (isset($_POST['restName']) && isset($_POST['restAddress'])) {
			$_SESSION['restName'] = $_POST['restName'];
			$_SESSION['restAddress'] = $_POST['restAddress'];
			header("location: restaurant.php");
		}
		// Drop old table...
		// echo "<br> dropping table <br>";
		// executePlainSQL("Drop table restaurant");
		// OCICommit($db_conn);
		OCILogoff($db_conn);
	} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

function printRestaurant($result) { //prints results from a select statement
	// echo "<br>Got data from table tab1:<br>";
	echo "<table>";
	echo "<tr>
	<th align='left'>Restaurant Name</th>
	<th align='left'>Address</th>
	<th>Cuisine Type</th>
	<th>AVG(rating)</th>
	<th>AVG(price)</th>
	</tr>";

	while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
		$restName = $row["RESTNAME"];
		$restAddress = $row["RESTADDRESS"];
		$rating = number_format((float)$row[3], 1, '.', '');
		$price = number_format((float)$row[4], 2, '.', '');
		echo "<tr>
		<td>" . $row["RESTNAME"] . "</td>
		<td>" . $row["RESTADDRESS"] . "</td>
		<td align='center'>" . $row["CUISINETYPE"] . "</td>
		<td align='center'>".$rating."</td>
		<td align='center'>$" . $price . "</td>
		<td><form method='POST' action='home.php'>
			<input type='hidden' value='$restName' name='restName'>
			<input type='hidden' value='$restAddress' name='restAddress'>
			<input type='submit' value='View'></form></td>
		</tr>"; //or just use "echo $row[0]" 
	}
	echo "</table>";

}

?>