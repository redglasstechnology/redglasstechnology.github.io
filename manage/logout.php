<?php

ini_set('display_errors', 'On');
if(!isset($_COOKIE["c_partner_name"])) {
	if (!isset($_COOKIE["c_salt"])) {
		unset($_COOKIE['c_salt']);
		setcookie('c_salt', null, -1, '/');
	}
} else {
	echo "Cookie deleted.";
    unset($_COOKIE['c_partner_name']);
    unset($_COOKIE['c_salt']);
    setcookie('c_partner_name', null, -1, '/');
    setcookie('c_salt', null, -1, '/');
}
echo "Redirecting to login page.";
header("Location: login.php"); /* Redirect browser */

exit();

?>