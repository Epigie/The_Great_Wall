<?php session_start();
	  session_destroy();
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>The wall</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<!-- <meta http-equiv="refresh" content="1"> -->
</head>
<body>
	<div id="container">
		<h2>CodingDojo Wall</h2>

		<div id="register">
			<h2>Register</h2>			
			<form action="process.php" method="post">
				<input type='hidden' name='submitted_form' value='register'>
				<p>First name: </p><input type='text' name='first_name' placeholder='First name'></br>
				<p>Last name: </p><input type='text' name='last_name' placeholder='Last name'></br>
				<p>Email: </p><input type='text' name='email' placeholder='Email'></br>
				<p>Password: </p><input type='password' name='password' placeholder='Password'></br>
				<p>Confirm password:</p><input type='password' name='confirm_password' placeholder='Confirm password'></br>
				<input type='submit' value='Register' class='button'>
			</form>
		</div>

		<div id="login">
			<h2>Login</h2>
			<form action="process.php" method="post" id='login_form'>
				<input type='hidden' name='submitted_form' value='login' id='login'><br>
				<p>Email: </p><input type='text' name='email' placeholder='Email'><br>
				<p>Password: </p><input type='password' name='password' placeholder='Password'><br>
				<input type='submit' value='Login' class='button '>
			</form>
		</div>
		<div id="errors">
			<?php 
				if(!empty($_SESSION['errors'])) {
					foreach($_SESSION['errors'] as $error) {
						echo "<p class='error'> * $error </p>";
					}
				}
			 ?>	
		</div>
	</div> <!-- End Container -->
</body> 
</head>