<?php
	//Run this file as a cron job every minute
	//* * * * * /usr/bin/php /var/www/html/cron/run.php


	/*
	 * Require app core files
	 */
	require(__DIR__ . '/../app.php');

	
	/*
	 * Temperature Monitor Cron
	 */
	//Retieve length of time set for sensor data poll
	$refresh_time = $db->query('SELECT refresh_time FROM config')->fetchArray()['refresh_time'];

	//Find the latest DB record's time
	$last_record = $db->query('SELECT * FROM records ORDER BY ID DESC LIMIT 1')->fetchArray();

	//Check if the last record's datetime older or equal to the 'refresh' minute value set in the admin
	if(strtotime(date('Y-m-d H:i:s')) >= strtotime("+{$refresh_time} minutes", strtotime($last_record['datetime']))) {
		
		//Yes - update the record as x minutes has passed since the last sensor data poll
		
		/*
		 * Run PHP Shell Exec command against the 'python.py' file
		 * 
		 * This file should be chmod:
		 * 		chmod +x cron/python.py
		 * 
		 * The Apache2 user (www-data) should be added to the 'gpio' group:
		 * 		sudo adduser www-data gpio
		 * 
		 * Assume you're using Python3 and it's version can be accessed:
		 * 		/usr/bin/env python3 --version
		 */
		$command = escapeshellcmd('/usr/bin/env python3 ' . __DIR__ . '/python.py');
		$output = shell_exec($command);

		//If the output array is not empty
		if (!empty($output)) {

			//Explode
			$output = explode(" ", $output);

			//Run query to insert data as a DB record
			$insert = $db->query('INSERT INTO records (temp,humidity) VALUES (?,?)', $output[0], $output[1]);

			//If rows affected are greater than 1
			if ( $insert->affectedRows() > 0 ) {
				//If no errors from SQL Query
				echo "Sensor Data added to DB.";
			
			//If there is an error inserting
			} else {
				die("Error: Unable to add Sensor Data to DB");
			}

		//If the output array is empty
		} else {
			die("Error: run.php is having difficulties retrieving data sensor values from python.py");
		}

	//If the time to poll has not reached threashold
	} else {
		echo "Too soon to poll new data based on 'refresh' minute value set in the admin.";
	}
?>