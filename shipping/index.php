<!DOCTYPE html>
<html lang="en" manifest="/cache.manifest">
<head>
	<title>Store 5 - Shipments</title>
	<link rel="apple-touch-icon" sizes="120x120" href="rabbit.png">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
	<script src="../js/respond.js"></script>

</head>
<body id="body">
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
	<div class="buttons">
		<a class="btn btn-primary refresh" href="#" title="Refresh the page">&#8635;</a>
		<a class="btn btn-primary showman" href="#manage" title="Download and upload new lists">&#9881;</a>	
	</div>
  </div>
</div>
<div id="manage">
	<div class="container">
	<div class="row">

		<div class="col-4 col-lg-4">

			<form action="upload.php" method="post" enctype="multipart/form-data" class="up"> 
				<div class="form-group">
					<input size="30" type="file" name="myFile"> 
					<button type="submit" class="btn btn-success" value="Upload">Upload CSV</button>
				</div>
			</form>
		</div>
	</div>
</div>	
</div>
<div id="wrap">
	<div class="top container">
		<div class="row">
			<div class="col-12 col-lg-12">
				<div id="shipments">
					<form id="searchform" role="form">
					  <div class="form-group">
						  <input type="input" class="search" placeholder="Search">
						  <a href="#" class="reset btn btn-default" title="Reset">x</a>
					  </div>
					</form>
					<div class="table-responsive">

	<table class="table table-striped">
		<thead>
		<tr>
			<th class="sort" data-sort="id">ID</th>
			<th class="sort" data-sort="ship-date">Shipped</th>
			<th class="sort" data-sort="ship-client">Client</th>
			<th class="sort" data-sort="track">Tracking #</th>
			<th class="sort" data-sort="transaction">Transaction ID</th>
			<th class="sort" data-sort="ship-type">Shipment Type</th>
			<th class="sort" data-sort="ship-weight">Ship Weight</th>
			<th class="sort" data-sort="ship-cost">Ship Cost</th>
		</tr>
	</thead>
	<tbody class="list">
	<?php
	  // Set default timezone
	  // date_default_timezone_set('UTC');
	  try {
		// Create (connect to) SQLite database in file
		$file_db = new PDO('sqlite:shipments.sqlite3');
		// Set errormode to exceptions
		$file_db->setAttribute(PDO::ATTR_ERRMODE, 
		                        PDO::ERRMODE_EXCEPTION);
		// Select all data from file db messages table 
		$result = $file_db->query('SELECT * FROM shipments ORDER BY shipdate DESC');
		foreach($result as $row) {
			$dateformed = new DateTime($row['shipdate']);
			$dateformed = $dateformed->format('M jS');
		  echo "<tr>\n";
		  echo "<td class=\"rowid\">" . $row['id'] . "</td>\n";
		  echo "<td class=\"ship-date\">" . $dateformed . "</td>\n";
		  echo "<td class=\"ship-client\">" . $row['client'] . "</td>\n";
		  echo "<td class=\"track\">" . $row['tracking'] . "</td>\n";
		  echo "<td class=\"tranasction\">" . $row['transid'] . "</td>\n";
		  echo "<td class=\"ship-type\">" . $row['shiptype'] . "</td>\n";
		  echo "<td class=\"ship-weight\">" . number_format($row['shipweight'],2) . "kg</td>\n";
		  echo "<td class=\"ship-cost\">$" . number_format($row['shipcost'],2) . "</td>\n";
		  echo "</tr>\n";
		}
	/**************************************
	* Close db connections                *
	**************************************/
	//$file_db->exec("DROP TABLE shipments");
	// Close file db connection
	$file_db = null;	
  } catch(PDOException $e) {
    // Print PDOException message
    echo "<tr><td colspan=8>".$e->getMessage()."</td></tr>";
  }
?>
	</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../js/jquery-1.4.2.min.js"></script>
<script src="../js/list-1.0.2.js"></script>
<!-- <script src="js/jquery.scrollTo.js"></script> -->
<script>
$(function() {
	$(".reset").click(function(e){
		$(".search").val("").focus();
		e.preventDefault();
		//$('#searchform').submit();
	});
	//$(".search").focus();
	var options = {
		valueNames: [ 'rowid', 'ship-date', 'ship-client','track','transaction','ship-type','ship-weight','ship-cost']
	};
	var shipmentList = new List('shipments', options);
	$('.showman').click(function(){
		$('#manage').slideToggle();
		return false;
	});
	$('.refresh').click(function() {
	    location.reload();
	});
});
</script>
</body>
</html>