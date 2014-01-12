<?php
	
$to = $_POST["to"];	
//$from = "StockRun.co";
$subject = "Basket from StockRun.co";
$basket = $_POST["basket"];
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
// $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
$headers .= 'From: StockRun.co <no-reply@stockrun.co>' . "\r\n";
// $headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
// $headers .= 'Bcc: allankh@icloud.com' . "\r\n";

mail($to, $subject, $basket, $headers);

print "sent.";