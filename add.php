<?php
$file = 'files/skulist.csv';
$data = $_GET["category"].",".$_GET["name"].",".$_GET["sku"].",".$_GET["price"].PHP_EOL;
file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
//print $data;
header("Location:./");