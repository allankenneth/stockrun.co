<?php
	
$to = $_GET["to"];	
$from = "StockRun.co";
$subject = "New Basket";
$basket = $_GET["basket"];

mail($to, $from, $subject, $basket);

print "sent.";