<?php
	/*
	 * Error reporting config
	 */
	error_reporting(0);


	/*
	 * Require classes
	 */
	require('classes/db.php');
	require('classes/csv.php');


	/*
	 * Configuration details
	 */
	require('config.php');

	//Check details have been entered
	if ( DBHOST == null && DBUSER == null && DBPASS == null && DBPASS == null ) {
		die("Error: Please provide DB login details in db.php");

	//Else, continue to create a new DB instance
	} else {
		//Initiate new $db instance
		$db = new db(DBHOST, DBUSER, DBPASS, DBPASS);
	}


	/*
	 * Set commonly used variables
	 */
	//Find the site name
	$site_name = $db->query('SELECT * FROM config')->fetchArray()['monitor_name'];
?>