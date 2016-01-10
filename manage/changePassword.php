<!DOCTYPE html>
<html >
	<head>
		<meta charset="UTF-8">
		<title>Partner Management Change Password</title>
		<link rel="stylesheet" href="partnerLogin.css">
		<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.cyan-light_blue.min.css">
		
		<?php
			ini_set('display_errors', 'On');

			include('../lib/config.php');

			if(isset($_COOKIE['c_partner_name'])) {
				$partner_name = $_COOKIE['c_partner_name'];
			} else {
				die("Please login first. <a href='login.php'>&larr; Login</a>");
			}

			if ($_POST && $_POST['currentPassword'] && $_POST['newPassword'] && $_POST['reEnterPassword']) {
				$currentPassword = mysqli_real_escape_string($con, $_POST['currentPassword']);
				$newPassword = mysqli_real_escape_string($con, $_POST['newPassword']);
				$reEnterPassword = mysqli_real_escape_string($con, $_POST['reEnterPassword']);
				
				if ($newPassword !== $reEnterPassword) {
					die("New password miss-match with re-enter new password! <a href='changePassword.php'>&larr; Back</a>");
				}
				
				$user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM partner WHERE partner_name='".$partner_name."'"));

				if (!$user) {
					die("That partner name doesn't exist! Please contact your system administration.");
				}
				if ($user['password'] != $currentPassword) {
					die("Incorrect password! <a href='changePassword.php'>&larr; Back</a>");
				}
				
				$partner_id = $user['partner_id'];
				mysqli_query($con, "UPDATE partner SET `password`='".$newPassword."' WHERE partner_id='".$partner_id."'");
				
				echo "Password change successfully, redirecting to logout page.";
				header("Location: logout.php");
				
				exit();
			}
		?>
	</head>
	<body>
		<hgroup>
			<h1>Partner Management Change Password</h1>
		</hgroup>
		
		<div class="input-form">
			<form action='' method='post' id="change-password-form" name="changePasswordForm">
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-input">
					<input name="currentPassword" class="mdl-textfield__input" type="password" id="changePassword-currentPassword" required>
					<label class="mdl-textfield__label" for="changePassword">Current Password</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-input">
					<input name="newPassword" class="mdl-textfield__input" type="password" id="changePassword-newPassword" required>
					<label class="mdl-textfield__label" for="changePassword">New Password</label>
				</div>
				<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-input">
					<input name="reEnterPassword" class="mdl-textfield__input" type="password" id="changePassword-reEnterPassword" required>
					<label class="mdl-textfield__label" for="changePassword">Re-enter New Password</label>
				</div>

				<button type="submit" value='Change Password' name='changePassword' class="button buttonBlue">
					<input type='submit' value='Change Password' name='changePassword' style="background-color: transparent; border: none; color: #fff; padding: 0; font-size: 25px;">
					<div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
				</button>
			</form>

			<button type="cancel" value='Cancel' name='cancel' class="button buttonRed" onclick="backToIndex()">
				<input type='button' value='Cancel' name='cancel' style="background-color: transparent; border: none; color: #fff; padding: 0; font-size: 25px;">
				<div class="ripples buttonRipples"><span class="ripplesCircle"></span></div>
			</button>
		</div>
		
		<script>
			var backToIndex = function backToIndex() {
				window.location.href = "index.php";
			}
		</script>
		
		<script src="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.min.js"></script>
	</body>
</html>