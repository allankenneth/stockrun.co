<?php

include('../config.php');
define("UPLOAD_DIR", $syspath);

if (!empty($_FILES["myFile"])) {
    $myFile = $_FILES["myFile"];
    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }
    // ensure a safe filename
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
    $parts = pathinfo($name);
	$fullname = $parts["filename"] . '.' . $parts["extension"];


		// Rename the existing skulist.csv
		// $now = date("U");
		// $current = $syspath . "skulist.csv";	
		// $archive = $syspath . "skulist-" . $now . ".csv";	
		// rename($current, $archive);

	    while (file_exists(UPLOAD_DIR . $fullname)) {
			// If there is more than one file, the last one is the one that
			// gets used; I'd limit it, but why bother?
	        $name = $parts["filename"] . "." . $parts["extension"];
	    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        UPLOAD_DIR . $name);
    if (!$success) { 
        echo "<p>Unable to save file.</p>";
        exit;
    } else {
    	//echo "<p>SUCCESS! <a href=\"./\">Back</a></p>";
		// TODO don't hardcode the below
		$goto = "Location: /store5/shipping/process.php?file=".$name;
		//print $goto;
		header($goto);
		//header("Location: /store5/shipping/");
    }
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
}
