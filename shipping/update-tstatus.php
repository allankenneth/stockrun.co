<?php
include("canpost/GetTrackingSummary.php");
try {
	// Create (connect to) SQLite database in file
	$file_db = new PDO('sqlite:shipments.sqlite3');
	
	// Set errormode to exceptions
	$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Select all data from file db messages table 
	//$result = $file_db->query("SELECT * FROM shipments WHERE tstatus IS NULL ORDER BY id DESC LIMIT 10");
	$result = $file_db->query("SELECT * FROM shipments WHERE tstatus NOT LIKE '%delivered%'");
	foreach($result as $row) {
		
		$trackid = $row['tracking'];
		if($trackid != "null") {
			$newstatus = getTrackingStatus($trackid);
		}else {
			$newstatus = "No tracking provided.";
		}
		$file_db->quote($newstatus);
		$sid = $row['id'];
		if($row['tstatus'] != $newstatus) {
			$update = 'UPDATE shipments SET tstatus = "'.$newstatus.'" WHERE id='.$sid;
			$file_db->exec($update); 
			$updatemsg = "Updated.";
		} else {
			$updatemsg = "No change.";
		}
		echo "<p>" . $row['id'] . " - " . $row['client'] . " - " . $row['tracking'] . " - " . $newstatus . " - " . $updatemsg . "</p>\n";
		//echo "<p>" . $row['id'] . " - " . $row['tracking'] . "</p>";
	}
	// Close db connection
	$file_db = null;	
} catch(PDOException $e) {
	// Print PDOException message
	echo "".$e->getMessage()."";
}