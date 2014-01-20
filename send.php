<?php
	
$to = $_POST["to"];
$fromname = $_POST["fromname"];
$fromemail = $_POST["fromemail"];
//$from = "StockRun.co";
$subject = "Basket from StockRun.co";
$basket = $_POST["basket"];
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$fromname.' <'.$fromemail.'>' . "\r\n";
$headers .= 'Bcc: allankh@icloud.com' . "\r\n";

mail($to, $subject, $basket, $headers);

print "sent.";