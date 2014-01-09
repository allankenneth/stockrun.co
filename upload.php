<?php
include('config.php');

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

	if($fullname == "skulist.csv") {
		// Rename the existing skulist.csv
		$now = date("U");
		$current = $syspath . "skulist.csv";	
		$archive = $syspath . "skulist-" . $now . ".csv";	
		rename($current, $archive);	 

	    while (file_exists(UPLOAD_DIR . $name)) {
			// If there is more than one file, the last one is the one that
			// gets used; I'd limit it, but why bother?
	        $name = $parts["filename"] . "." . $parts["extension"];
	    }
	} else {
		echo "<p>Sorry, but you can't upload that file. Make sure it's got the right name.";
		echo $fullname;
		exit;
	}
    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        UPLOAD_DIR . $name);
    if (!$success) { 
        echo "<p>Unable to save file.</p>";
        exit;
    } else {
    	//echo "<p>SUCCESS! <a href=\"./\">Back</a></p>";
		header("Location: ./");
    }
    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
}
