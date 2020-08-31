<?php
	/*
	 * Require app core files
	 */
  	require(__DIR__ . '/app.php');
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?php echo $site_name; ?></title>

	<link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/splash.css" rel="stylesheet">
</head>

<body class="text-center">
	<div class="splash-content">

		<h1 class="h2 mb-4 font-weight-normal">Temperature Monitor</h1>
		<h1 class="h3 mb-6 font-weight-normal"><?php echo $site_name; ?></h1>
		<br>
		<a href="admin/" class="btn btn-lg btn-primary btn-block">Access</a>
		
	</div>
  </body>
</html>