<!DOCTYPE html>
<html lang="en">
<head>
	<title>StockRun.co - Store 5</title>
	<link rel="apple-touch-icon" sizes="120x120" href="rabbit.png">
	<meta name="viewport" content="width=device-width">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/respond.js"></script>
</head>
<body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
  		<div class="buttons">
  			<a class="btn btn-primary basket" href="#basket" title="Basket">&#9776; <span>0</span></a>
  			<a class="btn btn-primary refresh" href="#" title="Refresh the page">&#8635;</a>
  			<a class="btn btn-primary showman" href="#manage" title="Download and upload new lists">&#9881;</a>
  		</div>
        <div class="navbar-header">
          <span class="navbar-brand">Store 5</span>
        </div>


      </div>
    </div>
	
<div id="wrap">
<div class="top container">
<div id="manage">

<div class="row">

<div class="col-4 col-lg-4">
	<div class="panel">
	<form action="add.php" role="form">
		<div class="form-group">

			<div>
				<label for="category">Category:</label> <br>
				<input type="text" name="category" id="cat">
			</div>
			<div>
				<label for="sku">SKU:</label><br>
				<input type="text" name="sku" id="sku">
			</div>
			<div>
				<label for="name">Name:</label> <br>
				<input type="text" name="name" id="name">
			</div>
			<div>
				<label for="price">Price:</label> <br>
				<input type="text" name="price" id="price"></label>
			</div>
			<button class="btn btn-success" type="submit">Add Product</button>

		</div>
	</form>
</div>
</div>
<div class="col-4 col-lg-4">
	<p><a class="btn btn-primary" href="files/skulist.csv">Download CSV</a></p>
	<hr>
	<form action="upload.php" method="post" enctype="multipart/form-data" class="up"> 
		<div class="form-group">
			<input size="30" type="file" name="myFile"> 
			<button type="submit" class="btn btn-success" value="Upload">Upload CSV</button>
		</div>
	</form>
</div>
</div> <!--/.row -->

</div> <!--/#manage-->
<div class="row">
<div class="col-12 col-lg-12">
	<ul id="basket">
	</ul>
</div>
</div>
<!-- <a href="#" class="subtotal">Subit</a> -->
<div class="row">
	<div class="col-12 col-lg-12">
	<div id="stock">

	<form role="form">
	  <div class="form-group">
		  <input type="input" class="search" placeholder="Search">
	  </div>
  </form>
<?php
// TODO make this account for " in item names! Dumbass.
$labels = Array("category", "name", "sku", "price");
$f = fopen("files/skulist.csv", "r");
echo "<ul class='list'>\n";
$itemcount = 0;
while (($line = fgetcsv($f)) !== false) {
	// skip the first line so we don't see the column headers
	if($itemcount > 0) {
		$liclass='';
		if ($itemcount % 2 == 0) { 
			$liclass='alt '; 
		} 
        echo "<li>\n";
        echo "<div class=\"".$liclass."row\">\n";
		echo "<div class=\"col-12 col-xs-12\">\n";
        $count = 0; //reset to 0 for each inner loop
		$items = '1;';
        foreach ($line as $cell) {
				$itembit = htmlspecialchars($cell);
				$items .= $itembit.";";
				
                echo "<div class=\"col-md-2 col-lg-2 ".$labels[$count]."\"";
				if ($count==2) echo " id=\"".$cell."\"";
				echo ">";
				if($count==3) echo '$';

				echo $itembit;
				echo "</div>\n";
                $count++; //go up one each loop
        }
		echo "<div class=\"col-md-2 col-lg-2\">";
		echo " <a class=\"addtolist btn btn-default\" id=\"minus\" data-item=\"".$items."\" href=\"#\">-</a>";
		echo " <span>0</span>";
		echo "<a class=\"addtolist btn btn-default\" id=\"plus\" data-item=\"".$items."\" href=\"#\">+</a>";
		echo "</div>\n";
        echo "</div></div>\n";
        echo "</li>\n";
	}
	$itemcount++;
}
echo "\n\t</ul>\n";
fclose($f);
?>
</div>
</div>
</div>
<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
<script src="js/jquery-1.4.2.min.js"></script>
<!-- <script src="../../dist/js/bootstrap.min.js"></script> -->
<script src="js/list.min.js"></script>
<script>
$(function() {
	
    if (typeof(localStorage) == 'undefined' ) {
        alert('Your browser does not support HTML5 localStorage. Try upgrading.');
    } else {
        getAllItems(); //load the items
		// foo = sortLocal();
		// console.log(foo[0][2]);
	}

	$('.refresh').click(function() {
	    location.reload();
	});
	
	var options = {
		valueNames: [ 'category', 'name', 'sku', 'price' ]
	};
	var contactList = new List('stock', options);
	
	$('.showman').click(function(){
		$('#manage').slideToggle();
		return false;
	});
	$('.basket').click(function(){
		$('#basket').slideToggle();
		return false;
	});
	$(".search").focus();
	
	$(".addtolist").click(function(){
		var action = $(this).attr("id");
		var items = $(this).attr("data-item");
		item = items.split(";");
		sku = item[3];
		// Check that the item doesn't already exist
		var addto = 0;
		var updateKey = 0;
		var updateQty = 0;
		var local = sortLocal();
		var totalQty = local.length;
		$(".basket span").html(totalQty);
		//console.log(totalQty);
		local.forEach(function(entry) {		
			if (item[3] == entry[4]) {
				addto = 1;
				updateKey = entry[0];
				updateQty = entry[1];	
			} 
		});
		if(action == "plus") {
			newQty = parseInt(item[0]) + parseInt(updateQty);
			$(this).prev("span").html(newQty);
		} else {
			newQty = parseInt(updateQty) - parseInt(item[0]);
			$(this).next("span").html(newQty);
		}
		if(addto != 0) {
			var key = updateKey;
			var newItems = newQty + ";" + item[1] + ";" + item[2] + ";" + item[3] + ";" + item[4] + ";";
		} else {
	    	var newDate = new Date();
	        var key = newDate.getTime();	
			var newItems = items;
		}
		try {
			localStorage.setItem(key, newItems);
		} catch(e) {
			if (e == QUOTA_EXCEEDED_ERR) {
				console.log('Quota exceeded!');
			}
		}
		getAllItems();
		return false;	
	});

	$(".remove").live('click', function() {
		var removeId = $(this).attr("href");
		removeId = removeId.split("#");
		removeId = removeId[1];
		localStorage.removeItem(removeId);
		getAllItems();
		return false;
	});
	
});

function sortLocal() {
	var i = 0;
	var logLength = localStorage.length-1; //how many items are in the database starting with zero
	db = [];
	//now we are going to loop through each item in the database
	for (i = 0; i <= logLength; i++) {
		//lets setup some variables for the key and values
		var itemKey = localStorage.key(i);
		//console.log(itemKey);
		//var dadate = (new Date(parseInt(itemKey))).toUTCString();
		var values = localStorage.getItem(itemKey);
		values = values.split(";"); //create an array of the values
		//console.log(values);
		var qty = values[0];
		var cat = values[1];
		var name = values[2];
		var sku = values[3];
		var price = parseFloat(values[4]);
		db.push([itemKey,qty,cat,name,sku,price]);
	}
	db.sort(function(a,b) {
	  //assuming distance is always a valid integer
	  return parseInt(a,10) - parseInt(b,10);
	});
	return db;
	
}

function getAllItems() {
	var local = sortLocal();
	//console.log(foo[0][2]);
	var stockList = ""; //the variable that will hold our html
	var subtotal = 0;
	// TODO update this to not use forEach, since that breaks in IE8
	local.forEach(function(entry) {
	    //console.log(entry[4]);
		var itemKey = entry[0];
		var qty = parseInt(entry[1]);
		var cat = entry[2];
		var name = entry[3];
		var sku = entry[4];
		var price = parseInt(entry[5]);
		sub = qty * price;
		subtotal = subtotal + sub;
		//console.log(subtotal);
		//now that we have the item, lets add it as a list item
		stockList += '<li class="dd-item" data-id="'+itemKey+'">';
		stockList += '<a href="#'+itemKey+'" class="remove btn btn-xs btn-default">x</a> 	';
		stockList += ''+qty+' x '+cat+' - '+name+' - '+sku+' - $'+price;
		stockList += '</li>';
	});
	subtotal = subtotal.toFixed(2);
	var taxrate = .12;
	var tax = (subtotal * taxrate).toFixed(2);
	var totalorder = parseFloat(subtotal) + parseFloat(tax);
	totalorder = totalorder.toFixed(2);
	stockList += '<hr>';
	stockList += '<li>Subtotal: $'+subtotal+'</li>';
	stockList += '<li>Tax: $'+tax+'</li>';
	stockList += '<li>Total: $'+totalorder+'</li>';
	basket = stockList.replace(/(<([^>]+)>)/ig,"");
	stockList += '<li>';
	// stockList += '<form action="send.php">';
	// stockList += '<input type="text" size="20" name="sendto">';
	// stockList += '<button class="send btn btn-sm btn-success" href="send.php">Email Basket</button>';
	// stockList += '</form>';
	stockList += '<li>';
	
	//if there were no items in the database
	if (subtotal == 0) {
		stockList = '<li class="empty">List Currently Empty</li>';
	}
	$("#basket").html(stockList); //update the ul with the list items
}


</script>
</body>
</html>