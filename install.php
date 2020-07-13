<?php
	/*
	 * Error reporting config
	 */
	error_reporting(0);


	/*
	 * Require files
	 */
	require(__DIR__ . '/config.php');


	/*
	 * Require classes
	 */
	require(__DIR__ . '/classes/db.php');


	/*
	 * Temperature Monitor Installation
	 */
	//Setup default variables
	$status = array();
	$status_message = array();
	$continue = array();
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Installation</title>

	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
	<div class="wrapper" style="padding:30px 0px">
		<div class="container">

			<div class="row">
				<div class="col-sm-12">
					<h1 class="h2 mb-4">Temperature Monitor</h1>
					<h1 class="h3 mb-6">Installation</h1>
					<br>
				</div>
			</div>

			
			<!-- System details -->
			<div class="row">
				<div class="col-sm-12">
					<p><strong>Details about your system:</strong></p>
					
					<div class="alert alert-secondary" role="alert">
						<ul>
							<li><strong>Hostname:</strong> <?php echo htmlspecialchars($_SERVER['SERVER_NAME']); ?></li>
							<li><strong>IP Address:</strong> <?php echo htmlspecialchars($_SERVER['SERVER_ADDR']); ?></li>
							<li><strong>PHP Installation Location:</strong> <?php echo htmlspecialchars(PHP_BINDIR); ?></li>
							<li><strong>PHP Version:</strong> <?php echo htmlspecialchars(phpversion()); ?></li>
							<li><strong>Web root:</strong> <?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?></li>
							<li><strong>Location of the Temp Monitor:</strong> <?php echo htmlspecialchars(__DIR__); ?></li>
						</ul>
					</div>
				</div>
			</div>


			<!-- Check to see if DB details have been provided -->
			<div class="row">
				<div class="col-sm-12">
					<p><strong>Checking DB login details:</strong></p>
					<?php 
						//Check details have been entered
						if ( DBHOST == null && DBUSER == null && DBPASS == null && DBNAME == null ) {
							$status['db_login_detail_check'] = 'danger';
							$status_message['db_login_detail_check'] = 'Please provide DB login details in config.php';

						} else {
							$status['db_login_detail_check'] = 'success';
							$status_message['db_login_detail_check'] = 'Login details exist';

							//Continue to next step
							$continue['db_connect_test'] = true;
						}
					?>

					<?php if ( $status['db_login_detail_check'] && $status_message['db_login_detail_check'] ) { ?>
						<div class="alert alert-<?php echo $status['db_login_detail_check']; ?>" role="alert">
							<?php echo $status_message['db_login_detail_check']; ?>
						</div>
					<?php } ?>
				</div>
			</div>


			<?php if ($continue['db_connect_test'] == true) { ?>
				<!-- Attempt to connect to DB -->
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Attempting to connect to DB:</strong></p>
						<?php 
							//Initiate new $db instance
							if ( $db = new db(DBHOST, DBUSER, DBPASS, DBNAME) ) {
								$status['db_connect_test'] = 'success';
								$status_message['db_connect_test'] = 'Connected to DB';

								//Continue to next step
								$continue['db_tables_check'] = true;
							}
						?>

						<?php if ( $status['db_connect_test'] && $status_message['db_connect_test'] ) { ?>
							<div class="alert alert-<?php echo $status['db_connect_test']; ?>" role="alert">
								<?php echo $status_message['db_connect_test']; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>


			<?php if ($continue['db_tables_check'] == true) { ?>
				<!-- Check if Tables are already setup -->
				<div class="row">
					<div class="col-sm-12">
						<p><strong>Checking if DB tables are already installed:</strong></p>

						<?php 
							//Check if the 'Config' and 'Records table are found == 2
							if ( $db->query('SELECT COUNT(*) AS tables_found_count FROM `information_schema`.`tables` WHERE `TABLE_SCHEMA` = "' . DBNAME . '" AND `TABLE_NAME` IN ("config", "records")')->fetchArray()['tables_found_count'] != 2 ) {
								$status['db_tables_check'] = 'danger';
								$status_message['db_tables_check'] = 'Not all tables exist, they will be created in the next step.';

								//Continue to next step
								$continue['db_table_install'] = true;

							} else {
								$status['db_tables_check'] = 'success';
								$status_message['db_tables_check'] = 'DB tables already created';
							}
						?>

						<?php if ( $status['db_tables_check'] && $status_message['db_tables_check'] ) { ?>
							<div class="alert alert-<?php echo $status['db_tables_check']; ?>" role="alert">
								<?php echo $status_message['db_tables_check']; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>


			<?php if ($continue['db_table_install'] == true) { ?>
				<!-- Install Tables and Data -->
				<div class="row">
					<div class="col-sm-12">	
						<p><strong>Installing DB tables:</strong></p>

						<?php 
							//Hold array for DB insertation data
							$install_tables = array();

							//Create 'config' table
							$install_tables['create_config'] = $db->query("CREATE TABLE config (id int(11) NOT NULL, refresh_time int(11) NOT NULL DEFAULT '1', monitor_name varchar(255) NOT NULL DEFAULT 'Temperature Monitor', offset_temp int(255) NOT NULL, offset_humidity int(255) NOT NULL, results_per_page int(255) NOT NULL DEFAULT '100') ENGINE=InnoDB DEFAULT CHARSET=utf8;");

							//Create 'records' table
							$install_tables['create_records'] = $db->query("CREATE TABLE records (id bigint(20) NOT NULL, temp decimal(5,2) NOT NULL, humidity decimal(5,2) NOT NULL, datetime datetime NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

							//Set 'config' table primary key
							$install_tables['set_config_primary_key'] = $db->query("ALTER TABLE config ADD PRIMARY KEY (id);");

							//Set 'config' table primary key auto increment
							$install_tables['set_config_primary_key_ai'] = $db->query("ALTER TABLE config MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

							//Set 'records' table primary key
							$install_tables['set_records_primary_key'] = $db->query("ALTER TABLE records ADD PRIMARY KEY (id);");

							//Set 'records' table primary key auto increment
							$install_tables['set_records_primary_key_ai'] = $db->query("ALTER TABLE records MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");

							//Installing data for 'config' table
							$install_tables['install_config_table_data'] = $db->query("INSERT INTO config (id, refresh_time, monitor_name, offset_temp, offset_humidity, results_per_page) VALUES (1, 1, 'Temperature Monitor', 0, 0, 100);");

							//Installing data for 'records' table
							$install_tables['install_records_table_data'] = $db->query("INSERT INTO records (id, temp, humidity, datetime) VALUES
							(1, 0, 0, '2020-06-07 00:00:00');");
						?>
						
						<div class="alert alert-info" role="alert">
							<?php 
								//Create 'config' table
								echo '<p>';if ( $install_tables['create_config']->affectedRows() > 0 ) { echo "'config' table created"; } else { echo "'config' table creation error"; }echo '</p>';

								//Create 'records' table
								echo '<p>';if ( $install_tables['create_records']->affectedRows() > 0 ) { echo "'records' table created"; } else { echo "'records' table creation error"; }echo '</p>';


								//Set 'config' table primary key
								echo '<p>';if ( $install_tables['set_config_primary_key']->affectedRows() > 0 ) { echo "'config' primary key set"; } else { echo "'config' primary key could not be set"; }echo '</p>';

								//Set 'config' table primary key AI
								echo '<p>';if ( $install_tables['set_config_primary_key_ai']->affectedRows() > 0 ) { echo "'config' primary key auto increment set"; } else { echo "'config' primary key auto increment could not be set"; }echo '</p>';

								//Set 'records' table primary key
								echo '<p>';if ( $install_tables['set_records_primary_key']->affectedRows() > 0 ) { echo "'records' primary key set"; } else { echo "'records' primary key could not be set"; }echo '</p>';

								//Set 'records' table primary key AI
								echo '<p>';if ( $install_tables['set_records_primary_key_ai']->affectedRows() > 0 ) { echo "'records' primary key auto increment set"; } else { echo "'records' primary key auto increment could not be set"; }echo '</p>';

								//Installing data for 'config' table
								echo '<p>';if ( $install_tables['install_config_table_data']->affectedRows() > 0 ) { echo "'config' table data installed"; } else { echo "'config' table data not installed"; }echo '</p>';

								//Installing data for 'records' table
								echo '<p>';if ( $install_tables['install_records_table_data']->affectedRows() > 0 ) { echo "'records' table data installed"; } else { echo "'records' table data not installed"; }echo '</p>';
							?>
						</div>
					</div>
				</div>
			<?php } ?>


			<!-- Enable PHP to gather sensor data -->
			<div class="row">
				<div class="col-sm-12">	
					<p><strong>Please run the commands below if you have not already done so. Enter these in your webserver terminal to allow access to the data returned by the <code>/cron/python.py</code> file:</strong></p>

					<div class="alert alert-secondary" role="alert">
						<ol>
							<li>
								<p>Install the Python packages required:</p>
								<ol>
									<li>
										<p>Install both 'python3-dev' and 'python3-pip' packages:</p>
										<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo apt-get install python3-dev python3-pip"); ?></code></pre>
									</li>
									<li>
										<p>Ensure you have the latest versions of the 'setuptools', 'wheel' and 'pip' Python packages.</p>
										<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo python3 -m pip install --upgrade pip setuptools wheel"); ?></code></pre>
									</li>
									<li>
										<p>Install <a href="https://github.com/adafruit/DHT-sensor-library" target="_blank">Adafruit's DHT library. This Python library  is used to interact with the DHT22 Humidity/Temperature sensor.</a></p>
										<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo pip3 install Adafruit_DHT"); ?></code></pre>
									</li>
									<li>
										<p>Run the following command to install the DHT library.</p>
										<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo pip3 install Adafruit_DHT"); ?></code></pre>
									</li>
								</ol>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo adduser www-data gpio"); ?></code></pre>
							</li>

							<li>
								<p>In order for <code>/cron/python.py</code> to run properly, add your Apache2 user (usally 'www-data') to the 'gpio' group:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo adduser www-data gpio"); ?></code></pre>
							</li>

							<li>
								<p>Make the <code>/cron/python.py</code> file executable:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo chmod +x " . __DIR__ . "/cron/python.py"); ?></code></pre>
							</li>

							<li>
								<p>Restart Apache2:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo service apache2 restart"); ?></code></pre>
							</li>
						</ol>
					</div>
				</div>
			</div>


			<!-- Enable Cron -->
			<div class="row">
				<div class="col-sm-12">	
					<p><strong>Enable Cron if not already done so. Enter these in your webserver terminal:</strong></p>

					<div class="alert alert-secondary" role="alert">
						<ol>
							<li>
								<p>Enable Cron to automatically retrieve sensor data:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("sudo crontab -e"); ?></code></pre>
							</li>

							<li>
								<p>Add this line:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars("* * * * * " . PHP_BINDIR . "/php " . __DIR__ . "/cron/run.php"); ?></code></pre>
							</li>
						</ol>
					</div>
				</div>
			</div>


			<!-- Update location in /admin/.htaccess -->
			<div class="row">
				<div class="col-sm-12">	
					<p><strong>Update the location of the .htpasswd file if not already done so:</strong></p>

					<div class="alert alert-secondary" role="alert">
						<ol>
							<li>
								<p>Edit the <code><?php echo htmlspecialchars("/admin/.htaccess"); ?></code> file.</p>
							</li>

							<li>
								<p>Update the location of <code><?php echo htmlspecialchars(".htpasswd"); ?></code> to match your server's setup. Please check, but it is likely to be:</p>
								<pre class="pre-scrollable"><code><?php echo htmlspecialchars(__DIR__ . "/admin/.htpasswd"); ?></code></pre>
							</li>
						</ol>
					</div>
				</div>
			</div>


			<!-- Setup completed -->
			<div class="row">
				<div class="col-sm-12">	
					<div class="alert alert-success" role="alert">
						<p><strong>Setup complete</strong></p>
						<p>You should be able to access the admin area <a href="admin/">here</a>. Default .htpasswd login details:</p>
						<ul>
							<li>Admin: <code><?php echo htmlspecialchars("rpi-temp"); ?></code></li>
							<li>Password: <code><?php echo htmlspecialchars("J*eoX3Xo6!0)"); ?></code></li>
						</ul>
						<p><strong>Once you confirmed data is being retrieved properly remember to delete this file (install.php)</strong></p>
					</div>
				</div>
			</div>

		</div>
	</div>
  </body>
</html>