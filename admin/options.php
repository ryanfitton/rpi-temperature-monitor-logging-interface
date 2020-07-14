<?php
	/*
	 * Require app core files
	 */
	require(__DIR__ . '/../app.php');
	  

	/*
	 * Handle form post submissions
	 */
	require(__DIR__ . '/handler/post_options.php');


	/*
	 * Header
	 */
	require(__DIR__ . '/partials/header.php');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Options</h1>
</div>

<?php 
	//Display success message of errors
	if($success || !empty($errors)) { 
?>
	<div class="row">
		<div class="col-sm-12">

			<?php 
				//If success message
				if($success) {
			?>
					<div class="alert alert-success" role="alert">
						<?php echo htmlspecialchars($success); ?>
					</div>
			<?php } ?>


			<?php 
				//If error messages
				if(!empty($errors)) {
			?>
					<div class="alert alert-danger" role="alert">
						<p><strong>Please check:</strong></p>
						<ul>
							<?php
								foreach($errors as $value) {         
									echo "<li>" . htmlspecialchars($value) . "</li>";   
								}
							?>
						</ul>
					</div>
			<?php } ?>
			
		</div>
	</div>
<?php } ?>


<h2 class="h4">Configuration options</h2>
<?php 
	//Retieve the config options
	$config_options = $db->query('SELECT * FROM config')->fetchArray();
?>
<div class="row">
	<div class="col-sm-12">
		<form name="admin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<div class="form-group">
				<label for="monitor_name">Monitor Name</label>
				<input type="text" class="form-control" name="monitor_name" id="monitor_name" value="<?php echo (!empty($errors) ? $_POST['monitor_name'] : $config_options['monitor_name']); ?>">
			</div>

			<div class="form-group">
				<label for="refresh_time">How often to poll new data</label>

				<div class="input-group">
					<input type="number" class="form-control" name="refresh_time" id="refresh_time" min="1" max="10080" value="<?php echo (!empty($errors) ? $_POST['refresh_time'] : $config_options['refresh_time']); ?>">
					<div class="input-group-append">
						<span class="input-group-text">minutes</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="offset_temp">Temperature Offset</label>
				<small class="form-text text-muted">
					You can enter +/- values.
				</small>
				
				<div class="input-group">
					<input type="number" class="form-control" name="offset_temp" id="offset_temp" min="-20" max="20" step=".01" value="<?php echo (!empty($errors) ? $_POST['offset_temp'] : $config_options['offset_temp']); ?>">
					<div class="input-group-append">
						<span class="input-group-text">&#8451;</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="offset_humidity">Humidity Offset</label>
				<small class="form-text text-muted">
					You can enter +/- values.
				</small>
				<div class="input-group">
					<input type="number" class="form-control" name="offset_humidity" id="offset_humidity" min="-20" max="20" step=".01" value="<?php echo (!empty($errors) ? $_POST['offset_humidity'] : $config_options['offset_humidity']); ?>">
					<div class="input-group-append">
						<span class="input-group-text">&#8451;</span>
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<label for="results_per_page">How many results should be shown per page?</label>
				<div class="input-group">
					<input type="number" class="form-control" name="results_per_page" id="results_per_page" min="1" max="250" value="<?php echo (!empty($errors) ? $_POST['results_per_page'] : $config_options['results_per_page']); ?>">
					<div class="input-group-append">
						<span class="input-group-text">result(s)</span>
					</div>
				</div>
			</div>

			<!-- Buttons -->
			<button type="submit" name="submit_options" id="submit_options" class="btn btn-primary mb-3 mr-3">Save options</button>

			<button type="submit" name="submit_csv" id="submmit_csv" class="btn btn-secondary mb-3 mr-3">Export as .csv</button>

			<button class="btn btn-light mb-3 mr-3" type="button" data-toggle="collapse" data-target="#other_options" aria-expanded="false" aria-controls="other_options">
				Other options
			</button>

			<div class="collapse" id="other_options">
				<div class="card card-body">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="h5">Please be careful with these options:</h3>
							<p>Remember to backup your MySQL database before proceeding with any of the options below:</p>

							<button type="submit" name="submit_clear" id="submit_clear" class="btn btn-danger">Delete all Temperature data</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<?php
	//Footer
	require(__DIR__ . '/partials/footer.php');
?>