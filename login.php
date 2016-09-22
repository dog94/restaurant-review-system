<html>
<h1 size="30"><b>Welcome to CS304 Bistro</b></h1>
<p><b>Please Login.</b></p>
<form method="POST" action="login.php"> 
Username: <input type="text" name="user" size="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Password: <input type="password" name="pass" size="6">   
<input type="submit" value="Login" name="login"> 
</form>
------------------------------------------------------------------------------------
<p><b>OR, Create your User Account Below:</b></p>

<form method="POST" action="login.php">
Username: 		<input type="text" placeholder="username" name="username" size="25">&nbsp;&nbsp;
Password: 		<input type="password" placeholder="xxxxxxx" name="password" size="25"><br><BR>
First Name: 	<input type="text" placeholder="John" name="firstName" size="25">&nbsp;&nbsp;
Last Name: 		<input type="text" placeholder="Appleseed" name="lastName" size="25"><br><BR>
Email:			<input type="text" placeholder="johnappleseed@gmail.com" name="email" size="25">&nbsp;&nbsp;
Address:		<input type="text" placeholder="eg. 123 1st Ave, Vancouver, BC" name="address" size="25"><br><BR>
Phone Number:	<input type="text" placeholder="XXX-XXX-XXXX" name="phoneNumber" size="25"><br><BR>
<input type="submit" value="Create Account" name="createAccount">
</form>

<?php
include 'functions.php';
// Connect Oracle...
if ($db_conn) {
	session_save_path('PHPSessions');
	session_start();
	
	if (array_key_exists('login', $_POST)) {
		// username and password sent from form
		$username = $_POST['user'];
		$password = $_POST['pass'];
		$userQuery = "select * from users where username = '".$username."' and password = '".$password."'";
		$userResult = OCIParse($db_conn, $userQuery);
		OCIExecute($userResult);
		
		// If result matched username and password,
		if (OCIfetch($userResult)) {
			// Register $username, $password and redirect to file "user.php"
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
			header("location: user.php");
		}
		else {
			echo "Wrong Username or Password";
		}
	} else if (array_key_exists('createAccount', $_POST)) {
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			$firstName = $_POST['firstName'];
			$lastName = $_POST['lastName'];
			$email = $_POST['email'];
			$address = $_POST['address'];
			$phoneNumber = $_POST['phoneNumber'];
			if (!empty($username) && !empty($password) && !empty($firstName) && !empty($lastName) && !empty($email) && !empty($address) && !empty($phoneNumber)){
			executePlainSQL("insert into users values ('".$username."', '".$firstName."', 
				'".$lastName."', '".$address."', '".$phoneNumber."', '".$email."', '".$password."')");
			
			OCICommit($db_conn);
			echo "Account successfully registered!";
			}
		else {
			?>
			<script>
			alert("Error: Empty field detected!");
			</script>
			<?php
		}
			
	} else if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: user-test.php");
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