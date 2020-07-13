<?php
	/*
	 * Require app core files
	 */
	require(__DIR__ . '/../app.php');


	/*
	 * Header
	 */
	require(__DIR__ . '/partials/header.php');
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
	<h1 class="h2">Dashboard</h1>
</div>

<h2 class="h4">Records</h2>
<?php 
	/*
	 * Retrieve Records
	 */
	$default_page = 1;

	//Find page variable from URL
	if(!empty($_GET['page'])) {
		//Sanitise value
		$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
		
		//If not numberic, set default page value
		if (!is_numeric($page )) {
			$page = $default_page;
		}

	//If no variable in URL, set default page value
	} else {
		//Default first page
		$page = $default_page;
	}

	//Find how many results should be shown per page
	$results_per_page = $db->query('SELECT results_per_page FROM config')->fetchArray()['results_per_page'];

	//Build SQL query to retrieve the correct data based on the page being viewed
	$offset = ($page - 1) * $results_per_page;
	$records = $db->query('SELECT * FROM records LIMIT ' . $offset . ',' . $results_per_page)->fetchAll();


	//Build pagination
		//Find total number of records in DB
		$total_records = $db->query('SELECT COUNT(*) AS row_count FROM records')->fetchArray()['row_count'];

		//If there are records in the DB
		if ($total_records > 0) {  

			//Determine how many pages should be shown
			$page_count = (int)ceil($total_records / $results_per_page);
		}


	//If there are records, display as a table
	if (!empty($records)) {
?>
		<div class="table-responsive">
			<table class="table table-striped table-sm">
				<thead>
					<tr>
						<th>ID</th>
						<th>Temperature (&#8451;)</th>
						<th>Humidity (&#37;)</th>
						<th>Date and Time</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						//Loop through records
						foreach ($records as $record) {
							echo '<tr>';

							echo '<td>' . $record['id'] . '</td>';
							echo '<td>' . $record['temp'] . '</td>';
							echo '<td>' . $record['humidity'] . '</td>';
							echo '<td>' . $record['datetime'] . '</td>';

							echo '</tr>';
						}
					?>
				</tbody>
			</table>

			<?php 
				//If more than one page is to be shown
				if ($page_count > 1) {

					echo '<nav aria-label="Records pagination"><ul class="pagination justify-content-center">';

					//Loop through how many pages should be shown
					for ($i = 1; $i <= $page_count; $i++) {
						//If current page
						if ($i === $page) {
							echo '<li class="page-item active"><a class="page-link" href="#">' . $i . ' <span class="sr-only">(current)</span></a></li>';

						//Else, show a link
						} else { 
							echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
						}
					}

					echo '</ul></nav>';

				}
			?>
						
	<?php 
		//If there are no records
		} else { 
	?>

		<div class="alert alert-warning" role="alert">
			There are no records found.
		</div>

	<?php } ?>
</div>


<?php
	//Footer
	require(__DIR__ . '/partials/footer.php');
?>