<?php
//echo exec('whoami');
include("canpost/GetTrackingSummary.php");
try {
	// Create (connect to) SQLite database in file
	$file_db = new PDO('sqlite:/home/allan/fairmont/stockrun.co/store5/shipping/db/shipments.sqlite3');
	// Set errormode to exceptions
	$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// First let's make sure the tracking log table has been set up
	// so that we can log each update as it happens, building a history 
	// and making it so we can tell when the last update was made
	
	// Select all data from file db messages table 
	$result = $file_db->query("SELECT * FROM shipments WHERE tstatus NOT LIKE '%delivered%'	AND tracking NOT LIKE '%null%'");
	$now = date('U');
	foreach($result as $row) {
		$sid = $row['id'];
		$trackid = $row['tracking'];
		$newstat = getTrackingStatus($trackid);
		$oldstat = $row['tstatus'];
		if($oldstat != $newstat) {
			// Now update the shipments table with the new info
			$updateOrder = 'UPDATE shipments SET tstatus = "'.$newstat.'", tstatdate = "'.$now.'" WHERE id='.$sid;
			$file_db->exec($updateOrder);
			
			$updatemsg = "Updated and logged.";
		} else {
			$updatemsg = "No change.";
		}	
		echo "" . $row['id'] . " - " . $row['client'] . " - " . $row['tracking'];
		echo  " - " . $newstat . " - " . $updatemsg . "\n";
	}
	$markdelivered = $file_db->query("SELECT * FROM shipments WHERE tstatus LIKE '%delivered%' AND flag = 'intransit'");
	foreach($markdelivered as $mark){
		$updateFlag = 'UPDATE shipments SET flag = "delivered" WHERE id='.$mark['id'];
		$file_db->exec($updateFlag);
	}	
	// Close db connection
	$file_db = null;	
} catch(PDOException $e) {
	// Print PDOException message
	echo "".$e->getMessage()."";
}
