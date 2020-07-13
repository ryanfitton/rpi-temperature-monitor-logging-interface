<?php
	/*
	 * Admin: Options post handler
	 */

	//If post data
	if ($_POST) {

		//Default variables
		$errors = array();
		$success = null;


		//Check which submit button has been submitted

		/*
			Saved options
		*/
		if (isset($_POST['submit_options'])) {

			//Remove button from Post array - This will affect saving if not removed
			unset($_POST['submit_options']);

			//Setup error messages for options
			$validation['monitor_name'] = 'Please enter the name for this monitor.';
			$validation['refresh_time'] = 'Please enter the alue for how often data is to be pulled (per minute)';
			$validation['offset_temp'] = 'Please enter a value for the temperature offset. You can enter +/- values.';
			$validation['offset_humidity'] = 'Please enter a value for the humidity offset. You can enter +/- values.';
			$validation['results_per_page'] = 'Please enter a value for the how many results should be shown per page.';

			//Run each field against validation
			foreach($_POST as $key => $value) {
				if(array_key_exists($key, $validation)) {
					if(trim($_POST[$key]) === '') {
						$errors[$key] = $validation[$key];
					}
				}
			}

			//If no errors from validation - Start updating in DB
			if(empty($errors)) {

				//Loop through each field and update in SQL
				foreach($_POST as $key => $value) {

					//Sanitise Key and Value
					$key = filter_var($key, FILTER_SANITIZE_STRING);
					$value = filter_var($value, FILTER_SANITIZE_STRING);

					//If the new value to update is no the eaxt same as what is currently stored in the DB
					if ( $db->query('SELECT ' . $key . ' FROM config')->fetchArray()[$key] != $value ) {
						
						//Update value in DB
						$update = $db->query("UPDATE config set " . $key . "='" . $value . "' WHERE id = '1'");

						//If there are no affected rows, this may be an error
						if ( $update->affectedRows() == 0 ) {
							$errors["update_failed_" . $key] = "DB save failed for " . $key;
						}

					}

				}

				//If no errors from SQL Queries
				if(empty($errors)) {
					$success = "Options updated successfully.";
				}
				
			}

		}


		/*
			Clear DB button
		*/
		if (isset($_POST['submit_clear'])) {

			//Remove button from Post array - This will affect saving if not removed
			unset($_POST['submit_clear']);

			//Run query to delete all record data
			$delete_records = $db->query('DELETE FROM records;');

			//If rows affected are greater than 1
			if ( $delete_records->affectedRows() > 0 ) {
				//If no errors from SQL Query
				$success = "Options updated successfully.";
			
			//If there is an error, or no records deleted
			} else {
				$errors["update_failed_" . $key] = "No Temperature data deleted.";
			}

		}


		/*
			Export as .csv button
		*/
		if (isset($_POST['submit_csv'])) {

			//Remove button from Post array - This will affect saving if not removed
			unset($_POST['submit_csv']);

			//New CSV export instance
			$exporter = new ExportDataExcel('browser', $site_name . ' ' . date("Y-m-d") . '.xls');

			//Initialise
			$exporter->initialize(); // starts streaming data to web browser

			
			/*
			 * Retrieve data for .csv export
			 */
			//Set column titles
			$exporter->addRow(array("ID", "temperature (℃)", "humidity (%)", "datetime (YYYY-MM-DD HH:MM:SS)")); 

			//Find and Loop through the CSV data
			foreach ($db->query('SELECT * FROM records')->fetchAll() as $record) {
				$exporter->addRow(array($record['id'], $record['temp'], $record['humidity'], $record['datetime'])); 
			}
			

			//Finalize: Writes the footer, flushes remaining data to browser.
			$exporter->finalize();

			//Complete
			exit();
			
		}

	}
?>