<?php

$data = array();
if(isset($_COOKIE['c_salt'])) {
	$data["c_salt"] = $_COOKIE['c_salt'];
	$data["c_partner_name"] = $_COOKIE['c_partner_name'];
} else {
	$data["c_salt"] = '';
	$data["c_partner_name"] = 'Guest';
}

print_r(json_encode($data));

?>