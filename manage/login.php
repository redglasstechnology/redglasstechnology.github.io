<!DOCTYPE html>
<html >
	<head>
		<meta charset="UTF-8">
		<title>Partner Management Login</title>
		<link rel="stylesheet" href="partnerLogin.css">
		<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.cyan-light_blue.min.css">
		
		<?php
			ini_set('display_errors', 'On');

			include('../lib/config.php');

			if ($_POST && $_POST['login']) {
				if ($_POST['partner_name'] && $_POST['password']) {
					$partner_name = mysqli_real_escape_string($con, $_POST['partner_name']);
					$password = mysqli_real_escape_string($con, $_POST['password']);
					$user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM partner WHERE partner_name='".$partner_name."'"));
					
					if (!$user) {
						die("That partner name doesn't exist! Try making <i>$partner_name</i> today! <a href='index.php'>&larr; Back</a>");
					}
					if ($user['password'] != $password) {
						die("Incorrect password! <a href='index.php'>&larr; Back</a>");
					}
					$salt = hash("sha512", rand() . rand() . rand());
					setcookie("c_partner_name", $partner_name, time() + 24 * 60 * 60, "/");
					setcookie("c_salt", $salt, time() + 24 * 60 * 60, "/");
					
					$partner_id = $user['partner_id'];
					mysqli_query($con, "UPDATE partner SET `Salt`='$salt' WHERE partner_id='$partner_id'");
					
					header("Location: index.php"); /* Redirect browser */
					
					exit();
				}
			}
		?>
	</head>
	<body>
		<hgroup>
			<h1>Partner Management Login</h1>
		</hgroup>
		<div class="input-form">
			<form action='' method='post' id="login-form">
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-input">
					<input name="partner_name" class="mdl-textfield__input" type="text" id="sample3" />
					<label class="mdl-textfield__label" for="sample3">Partner Name</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-input">
					<input name="password" class="mdl-textfield__input" type="password" id="sample3" />
					<label class="mdl-textfield__label" for="sample3">Password</label>
				</div>
				
				<button type="submit" value='Login' name='login' class="button buttonBlue">
					<input type='submit' value='Login' name='login' style="background-color: transparent; border: none; color: #fff; padding: 0; font-size: 25px;">
					<div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
				</button>
			</form>
		</div>
		
		<script src="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.min.js"></script>
	</body>
</html>