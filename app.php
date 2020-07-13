<?php
	/*
	 * Error reporting config
	 */
	error_reporting(0);


	/*
	 * Require classes
	 */
	require(__DIR__ . '/classes/db.php');
	require(__DIR__ . '/classes/csv.php');


	/*
	 * Database connection details
	 */
	require(__DIR__ . '/config.php');

	//Check details have been entered
	if ( $dbhost == null && $dbuser == null && $dbpass == null && $dbname == null ) {
		die("Error: Please provide DB login details in db.php");

	//Else, continue to create a new DB instance
	} else {
		//Initiate new $db instance
		$db = new db($dbhost, $dbuser, $dbpass, $dbname);
	}


	/*
	 * Set commonly used variables
	 */
	//Find the site name
	$site_name = $db->query('SELECT * FROM config')->fetchArray()['monitor_name'];
?>