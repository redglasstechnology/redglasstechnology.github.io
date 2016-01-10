<!doctype html>
<?php
	header("Access-Control-Allow-Origin: *");

	if(!isset($_COOKIE["c_partner_name"])) {
		header("Location: login.php"); /* Redirect browser */
	}
?>
<html lang="en" ng-app="partnerApp">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="Partner management page for RedGlass display device.">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Partner Management</title>
		<meta name="mobile-web-app-capable" content="yes">
		<!--<link rel="icon" sizes="192x192" href="images/touch/chrome-touch-icon-192x192.png">-->

		<!-- Add to homescreen for Safari on iOS -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="Material Design Lite">
		<link rel="apple-touch-icon-precomposed" href="apple-touch-icon-precomposed.png">

		<!-- Tile icon for Win8 (144x144 + tile color) -->
		<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
		<meta name="msapplication-TileColor" content="#3372DF">

		<!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
		<!--
		<link rel="canonical" href="http://www.example.com/">
		-->

		<link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link href="http://netdna.bootstrapcdn.com/font-awesome/2.0/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.cyan-light_blue.min.css">
		<link href="../lib/angular-file-upload.css" rel="stylesheet">
		<link rel="stylesheet" href="../lib/mainStyle.css">
		<link rel="stylesheet" href="style.css">
		
		<!-- load angular and angular route via CDN -->
		<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.6/angular.min.js"></script>
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-route.js"></script>
		<script src="../lib/angular-file-upload.min.js"></script>
		<script src="app.js"></script>
		
	</head>
	<body ng-controller="mainController">
		<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
			<header class="header-bar-with-burger demo-header mdl-layout__header mdl-color--white mdl-color--grey-100 mdl-color-text--grey-600">
				<div class="mdl-layout__header-row">
					<span class="mdl-layout-title"></span>
					<div class="mdl-layout-spacer"></div>
				</div>
			</header>
			<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<header class="demo-drawer-header">
					<!--<img src="images/user.jpg" class="demo-avatar">-->
					<div class="demo-avatar-dropdown">
						<span>{{ partner_name }}</span>
						<div class="mdl-layout-spacer"></div>
						<button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
							<i class="material-icons" role="presentation">arrow_drop_down</i>
							<span class="visuallyhidden">Accounts</span>
						</button>
						<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
							<li class="mdl-menu__item" ng-click="logout()">Log Out</li>
							<li class="mdl-menu__item" ng-click="changePassword()">Change Password</li>
						</ul>
					</div>
				</header>
				<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
				<a class="mdl-navigation__link" href="#"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Main</a>
				<div class="mdl-layout-spacer"></div>
				<a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><spna class="visuallyhidden">Help</spna></a>
				</nav>
			</div>
			<main class="mdl-layout__content mdl-color--grey-100">
				<div class="mdl-grid" ng-view>
				</div>
			</main>
		</div>
		<script src="https://storage.googleapis.com/code.getmdl.io/1.0.3/material.min.js"></script>
	</body>
</html>
