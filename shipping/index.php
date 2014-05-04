<?php
$canpost ='http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=';
try {
	// Create (connect to) SQLite database in file
	$file_db = new PDO('sqlite:db/shipments.sqlite3');
	// Set errormode to exceptions
	$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Retrieve the last tracking update logged so we can show when 
	// things were last updated from Canada Post. We echo this out below.
	$logs = $file_db->query('SELECT * FROM tracklog ORDER BY logdate DESC LIMIT 1');
	foreach($logs as $log) {
		$ldate = date('jS \a\t g:i', $log['logdate']);
	}

} catch(PDOException $e) {
	// Print PDOException message
	echo $e->getMessage();
	exit();
}
?>
<!DOCTYPE html>
<html lang="en" manifest="/cache.manifest">
<head>
	<title>Store 5 - Shipments</title>
	<link rel="apple-touch-icon" sizes="120x120" href="../rabbit.png">
	<link rel="icon" type="image/x-icon" href="/favicon.ico" >
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="../css/style.css">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
	<script src="../js/respond.js"></script>

</head>
<body id="body">
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
	<div class="buttons">
		<a class="btn btn-primary" href="update-tstatus.php" title="Update Tracking Statuses">Track Update</a>
		<a class="btn btn-primary refresh" href="#" title="Refresh the page"><span class="glyphicon glyphicon-refresh"></span></a>
		<a class="btn btn-primary showman" href="#manage" title="Download and upload new lists"><span class="glyphicon glyphicon-cog"></span></a>	
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

	<table class="table">
		<thead>
		<tr>
			<th></th>
			<th width="90">Shipped</th>
			<th>Client</th>
			<th>Tracking #</th>
			<th>Status <!--(as of the <?php echo $ldate; ?>)--></th>
			<th>Transaction</th>
			<th>Shipment Type</th>
			<th>Weight</th>
			<!-- <th>Ship Cost</th> -->
		</tr>
	</thead>
	<tbody class="list">
		<?php
		
		
		// Select all data from shipments table 
		$result = $file_db->query('SELECT * FROM shipments ORDER BY shipdate DESC LIMIT 1000');
		$statusClass = '';
		$counter = 0;
		foreach($result as $row) {
			$counter++;
			$shippedon = new DateTime($row['shipdate']);
			$shippedon = $shippedon->format('M jS');
			//$updatedon = new DateTime($row['tstatdate']);
			$statusClass = $row['flag'];
			echo "<tr id=\"s-".$counter."\" class=\"". $statusClass ."\">\n";
			echo "<td class=\"rowid\">";
			if($row['flag']=='investigate') $flag = 'intransit';
			else $flag = 'investigate';
			echo "<a class=\"flagit btn btn-sm btn-default\" href=\"flag.php?shipid=".$row['id']."&flag=".$flag."\"><span class=\"glyphicon glyphicon-flag\"></span></a>";
			//echo "<a href=\"remove.php?sid=".$row['id']."\">x</a>";
			echo "</td>\n";
			echo "<td class=\"ship-date\">" . $shippedon . "</td>\n";
			echo "<td class=\"ship-client\"><strong>" . $row['client'] . "</strong></td>\n";
			$url = $canpost . $row['tracking'];
			echo "<td class=\"track\">";
			echo "<a class=\"\" href=\"".$url."\" target=\"_blank\">";
			echo $row['tracking'];
			echo "</a></td>\n";
			echo "<td class=\"tstatus\">";
			echo "<div contenteditable=\"true\" id=\"tstat-".$row['id']."\" data-key=\"tstat-".$row['id']."\">" . $row['tstatus'] . "</div>";
			//echo $row['tstatdate'];
			echo "</td>\n";
			echo "<td class=\"tranasction\">" . $row['transid'] . "</td>\n";
			echo "<td class=\"ship-type\">" . $row['shiptype'] . "</td>\n";
			echo "<td class=\"ship-weight\">" . number_format($row['shipweight'],2) . "kg</td>\n";
			//echo "<td class=\"ship-cost\">$" . number_format($row['shipcost'],2) . "</td>\n";
			echo "</tr>\n";
		}
		/**************************************
		* Close db connections                *
		**************************************/
		// Close file db connection
		$file_db = null;	

?>
	</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--<script src="../js/jquery-2.0.3.min.js"></script>-->
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="../js/list-1.0.2.js"></script>
<script src="js/jquery.contenteditable.js"></script>
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
		page: 300,
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

	// execute once on the container
	$("#shipments").contentEditable().change(function(e){
		// what to do when the data has changed
		//console.log(e);
		//console.log(e.action);
		if(e.action == "save"){
			for(i in e.changed){
				datakey = i;
			}
			//console.log(datakey);
			sid = datakey.split('-');
			shipid = sid[1];
			keyid = '#'+datakey;
			updatetext = $(keyid).text();
			shortenedup = updatetext.substring(0, 140);
			upurl = 'statup.php?shipid='+shipid+'&newstat='+shortenedup;
			$.get(upurl, function( data ) {
				//LOL IE8 doesn't even have a console to log to
				//console.log(data);
			});
		}
	});
	$('.flagit').click(function(e){
		e.preventDefault();
		var that = this;
		flagurl = $(that).attr('href');
		$.get(flagurl, function( data ) {
			//LOL IE8 doesn't even have a console to log to
                        console.log(data);
			newclass = data;
			newurl = flagurl.split('&');
			newflag = newurl[0] + '&flag=' + newclass;
			$(that).removeAttr('href').attr('href',newflag);
			$(that).parent().parent().removeClass().addClass(newclass);
		});
	});
});
</script>
</body>
</html>
