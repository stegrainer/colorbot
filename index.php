<?
	session_start();

	include_once '-/inc/functions.php';
	
	if($_REQUEST) {
		if($_REQUEST['clear'] == 1) {
			clearPalette();
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<title>Hello, I&rsquo;m ColorBot: Your personal color assistant.</title>
	<link rel="stylesheet" href="-/css/styles.min.css">
	<link rel="shortcut icon" href="-/img/favicon.ico" type="image/x-icon" />
	<link rel="icon" type="image/png" href="-/img/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="-/img/favicon-16x16.png" sizes="16x16" />
	<link rel="apple-touch-icon" sizes="180x180" href="-/img/apple-icon.jpg">
	<link rel="icon" type="image/png" href="-/img/android-icon.jpg" sizes="192x192">
	<meta name="twitter:title" property="og:title" content="ColorBot: Your personal color assistant.">
	<meta name="description" content="I can convert colors between Hex, RGBa, and HSLa, create and share color palettes, and more.">
	<meta name="twitter:description" property="og:description" content="I can convert colors between Hex, RGBa, and HSLa, create and share color palettes, and more.">

	<meta name="twitter:image" property="og:image" content="<?= "http://$_SERVER[HTTP_HOST]" ?><?= strtok($_SERVER["REQUEST_URI"],'?') ?>-/img/colorbot-social.jpg">
	<meta name="twitter:url" property="og:url" content="<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">
	<meta name="twitter:card" content="summary_large_image">
</head>

<body>
	<div class="page">
		<div id="palette"><? include '-/inc/palette.php'; ?></div>
		<div id="picker"><? include '-/inc/picker.php'; ?></div>
	</div>
	<script type="text/javascript" src="-/js/scripts.min.js"></script>
	<link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:300,800" as "style">
	<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,800"></noscript>
</body>
</html>