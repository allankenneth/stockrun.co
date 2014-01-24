<?php
function getShipments() {
	$f = fopen("files/Customer-Detail-Report.csv", "r");
	$shipments = Array();
	while (($line = fgetcsv($f)) !== false) {
        $count = 0;
        foreach ($line as $cell) {
			if($count > 0) {
				$itembit = htmlspecialchars($cell);
				if($count == 3) $date = $itembit;
				$dateformed = new DateTime($date);
				$dateformed = $dateformed->format('M jS');
				if($count == 4) $track = $itembit;
				if($count == 5) $client = $itembit;
				if($count == 8) $sku = $itembit;
				if($count == 9) $type = $itembit;
				if($count == 10) $weight = $itembit;
				if($count == 17) $cost = $itembit;
			}
			$count++; //go up one each loop
        }
		array_push($shipments,[$dateformed,$client,$track,$sku,$type,$weight,$cost]);
	}
	fclose($f);
	return $shipments;
}
function displayShipments($shipments) {
	$shipments = array_reverse($shipments);
	$ship = '';
	$canpost ='http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=';
	foreach($shipments as $shipment) {
		$ship .= '<tr>';
		$ship .= '<td class="ship-date">'.$shipment[0].'</td>';
		$ship .= '<td class="ship-client"><strong>'.$shipment[1].'</strong></td>';
		$url = $canpost . $shipment[2];
		$ship .= '<td class="track"><a target="_blank" href="'.$url.'">'.$shipment[2].'</a></td>';
		$ship .= '<td class="transaction">'.$shipment[3].'</td>';
		$ship .= '<td class="ship-type">'.$shipment[4].'</td>';
		$ship .= '<td class="ship-weight">'.$shipment[5].'kg</td>';
		$ship .= '<td class="ship-cost">$'.$shipment[6].'</td>';
		$ship .= '</tr>';
	}
	print $ship;
}
?>
<!DOCTYPE html>
<html lang="en" manifest="/cache.manifest">
<head>
	<title>Store 5 - Shipments</title>
	<link rel="apple-touch-icon" sizes="120x120" href="rabbit.png">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/respond.js"></script>

</head>
<body id="shipmentsBody">
<!-- <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
	<div class="buttons">
		
	</div>
  </div>
</div> -->
	
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
							<th class="sort" data-sort="ship-date">Date</th>
							<th class="sort" data-sort="ship-client">Client</th>
							<th class="sort" data-sort="track">Tracking #</th>
							<th class="sort" data-sort="transaction">Transaction ID</th>
							<th class="sort" data-sort="ship-type">Shipping Type</th>
							<th class="sort" data-sort="ship-weight">Weight</th>
							<th class="sort" data-sort="ship-cost">Cost</th>
						</tr>
						</thead>
						<tbody class="list">
						<?php $shipments = getShipments() ?>
						<?php displayShipments($shipments) ?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="js/jquery-1.4.2.min.js"></script>
<script src="js/list-1.0.2.js"></script>
<!-- <script src="js/jquery.scrollTo.js"></script> -->
<script>
$(function() {
	$(".reset").click(function(e){
		$(".search").val("").focus();
		e.preventDefault();
		//$('#searchform').submit();
	});
	$(".search").focus();
	var options = {
		valueNames: [ 'ship-date', 'ship-client','track','transaction','ship-type','ship-weight','ship-cost']
	};
	var shipmentList = new List('shipments', options);
});
</script>
</body>
</html>