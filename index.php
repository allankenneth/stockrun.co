<?php
function getStockList() {
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
			$skuId = '';
	        foreach ($line as $cell) {
					$itembit = htmlspecialchars($cell);
					$items .= $itembit.";";
	                echo "<div class=\"col-md-2 col-lg-2 ".$labels[$count]."\">";
					if ($count==2) $skuId = htmlspecialchars($cell);
					if($count==3) echo '$';
					echo $itembit;
					echo "</div>\n";
	                $count++; //go up one each loop
	        }
			echo "<div id=\"".$skuId."\" class=\"col-md-2 col-lg-2\">";
			echo " <a class=\"addtolist btn btn-default\" id=\"minus\" data-item=\"".$items."\" href=\"#\">-</a>";
			echo "<span class=\"qty btn btn-default\">0</span>";
			echo "<a class=\"addtolist btn btn-default\" id=\"plus\" data-item=\"".$items."\" href=\"#\">+</a>";
			echo "</div>\n";
	        echo "</div></div>\n";
	        echo "</li>\n";
		}
		$itemcount++;
	}
	echo "\n\t</ul>\n";
	fclose($f);
}
function getColleagues() {
	$f = fopen("files/colleagues.csv", "r");
	$colleagues = '';
	while (($line = fgetcsv($f)) !== false) {
        $count = 0;
        foreach ($line as $cell) {
				$itembit = htmlspecialchars($cell);
				if($count == 0) $colleagueId = $itembit;
				if($count == 1) $name = $itembit;
				if($count == 2) $title = $itembit;
				if($count == 3) $colleagueEmail = $itembit;
                $count++; //go up one each loop
        }
		$colleagues .= '<input type="radio" id="colleagueId'.$colleagueId.'"';
		$colleagues .= ' value="'.$name.'-'.$title.'-'.$colleagueEmail.'"';
		$colleagues .= ' name="colleagueId"> '; // value="'.$colleagueId.'"
		$colleagues .= '<label for="colleagueId'.$colleagueId.'">'.$name.'</label> ';
		// .'-'.$title.'-'.$colleagueEmail.'
	}
	print $colleagues;
	fclose($f);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Store 5</title>
	<link rel="apple-touch-icon" sizes="120x120" href="rabbit.png">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/respond.js"></script>

</head>
<body id="body">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
  		<div class="buttons">
  			<a class="btn btn-primary refresh" href="#" title="Refresh the page">&#8635;</a>
  			<a class="btn btn-primary showman" href="#manage" title="Download and upload new lists">&#9881;</a>
  		</div>
        <div class="navbar-header">
			<a class="btn btn-success basket" href="#basket" title="Basket">&#9776; <span>0</span></a>
		</div>
      </div>
    </div>
	
<div id="wrap">
<div class="top container">
<div id="manage">
	<div class="panel panel-default">
	  <div class="panel-body">
<div class="row">

<div class="col-4 col-lg-4">
	<div class="panel">
	<form action="add.php" role="form">
		<div class="form-group">

			<div class="form-group">
				<label for="category">Category:</label> <br>
				<input type="text" name="category" id="category">
			</div>
			<div class="form-group">
				<label for="sku">SKU:</label><br>
				<input type="text" name="sku" id="sku">
			</div>
			<div class="form-group">
				<label for="name">Name:</label> <br>
				<input type="text" name="name" id="name">
			</div>
			<div class="form-group">
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
</div></div>
</div> <!--/#manage-->
<div id="basketBox" class="row">
<div class="col-12 col-lg-12">
	<div class="panel panel-info">
	    <div class="panel-heading">
	      <h2 class="panel-title">Basket</h2>
	    </div>
	  <div class="panel-body">
	<ul id="basket">
	</ul>
</div></div>
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
<?php getStockList(); ?>
</div>
</div>
</div>
<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
<script src="js/jquery-1.4.2.min.js"></script>
<!-- <script src="../../dist/js/bootstrap.min.js"></script> -->
<script src="js/list.min.js"></script>
<script src="js/jquery.scrollTo.js	"></script>
<script>
$(function() {
	
    if (typeof(localStorage) == 'undefined' ) {
        alert('Your browser does not support HTML5 localStorage. Try upgrading.');
    } else {
        getAllItems();
		applyQtys();
	}
	$(".search").focus();
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

	$('.basket').click(function(e){
		$('#basketBox').slideToggle();
		//window.location.href = "#basket";
		var target = "#basketBox";
		// var targetOffset = $(target).offset().top - 60;
		// $("#basket").animate({scrollTop: targetOffset}, 400, function(event) {
		// 	event.preventDefault();
		e.preventDefault();
		//location.hash = target;
		//console.log(target);
		$(target).scrollTo(300);
		// });

	});
	
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
	
	$("#sendbasket").live("submit",function(e){
		
		var emailTo = $("#sendto").val();
		var colleague = $("input:radio[name=colleagueId]:checked").val();
		//console.log(colleague);
		var bit = colleague.split("-");
		var fromName = bit[0];
		var fromEmail = bit[2];
		//console.log(fromName);
		data = sendBasket(emailTo, fromName, fromEmail);
		$.ajax({
		    type: "POST",
		    url: "send.php",
		    data: data,
		    success: function(){
				alert("Basket sent!");
		        //$('.success').fadeIn(1000);
		    }
		});
		e.preventDefault();
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
// TODO refactor this to integrate it and the above function
function applyQtys() {
	var i = 0;
	var logLength = localStorage.length-1; //how many items are in the database starting with zero
	uppers = [];
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
		var sku = values[3];
		uppers.push([qty,sku]);
	}
	uppers.forEach(function(entry) {
		var skuid = '#'+entry[1]+' .qty';
		$(skuid).html(entry[0])
	});
	return uppers;	
}

function getAllItems() {
	var local = sortLocal();
	//console.log(foo[0][2]);
	var stockList = ""; //the variable that will hold our html
	var subtotal = 0;
	var totalQty = 0;
	// TODO update this to not use forEach, since that breaks in IE8
	local.forEach(function(entry) {
	    //console.log(entry[4]);
		itemKey = entry[0];
		qty = parseInt(entry[1]);
		cat = entry[2];
		name = entry[3];
		sku = entry[4];
		price = parseInt(entry[5]);
		sub = qty * price;
		subtotal = subtotal + sub;
		//console.log(subtotal);
		//now that we have the item, lets add it as a list item
		stockList += '<li class="dd-item" data-id="'+itemKey+'">';
		stockList += '<a href="#'+itemKey+'" class="remove btn btn-xs btn-default">x</a> 	';
		stockList += ''+qty+' x '+cat+' - '+name+' - '+sku+' - $'+price;
		stockList += '</li>';
		totalQty = totalQty + qty;
	});
	subtotal = subtotal.toFixed(2);
	taxrate = .12;
	tax = (subtotal * taxrate).toFixed(2);
	totalorder = parseFloat(subtotal) + parseFloat(tax);
	totalorder = totalorder.toFixed(2);
	stockList += '<hr>';
	stockList += '<li>Subtotal: $'+subtotal+'</li>';
	stockList += '<li>Tax: $'+tax+'</li>';
	stockList += '<li>Total: $'+totalorder+'</li>';
	stockList += '<li>';
	stockList += '<form method="post" id="sendbasket" action="send.php">';
	stockList += '<div>';
	stockList += '<?php getColleagues() ?>';
	stockList += '</div>';	
	stockList += '<input type="text" size="20" id="sendto" name="sendto">';
	stockList += '<button class="btn btn-sm btn-success" href="send.php">Send Basket</button>';
	stockList += '</form>';
	stockList += '</li>';
	
	//if there were no items in the database
	if (subtotal == 0) {
		stockList = '<li class="empty">List Currently Empty</li>';
	}
	//var totalQty = local.length;
	$(".basket span").html(totalQty);
	$("#basket").html(stockList); //update the ul with the list items
}

function sendBasket(sendTo, fromName, fromEmail) {
	var local = sortLocal();
	//if there were no items in the database
	// if (local.length-1 == 0) {
	// 	message = 'List Currently Empty';
	// } else {
		//console.log(foo[0][2]);
		var message = '<style>* {font-size: 22px}</style>'; //the variable that will hold our html
		var subtotal = 0;
		var totalQty = 0;
		// TODO update this to not use forEach, since that breaks in IE8
		local.forEach(function(entry) {
		    //console.log(entry[4]);
			itemKey = entry[0];
			qty = parseInt(entry[1]);
			cat = entry[2];
			name = entry[3];
			sku = entry[4];
			price = parseInt(entry[5]);
			sub = qty * price;
			subtotal = subtotal + sub;
			//console.log(subtotal);
			//now that we have the item, lets add it as a list item
			//stockList += '<li class="dd-item" data-id="'+itemKey+'">';
			//stockList += '<a href="#'+itemKey+'" class="remove btn btn-xs btn-default">x</a> 	';
			message += ''+qty+' x '+cat+' - '+name+' - '+sku+' - $'+price+'<br>';
			totalQty = totalQty + qty;
		});
		subtotal = subtotal.toFixed(2);
		taxrate = .12;
		tax = (subtotal * taxrate).toFixed(2);
		totalorder = parseFloat(subtotal) + parseFloat(tax);
		totalorder = totalorder.toFixed(2);
		message += '<hr>';
		message += 'Subtotal: $'+subtotal+'<br>';
		message += 'Tax: $'+tax+'<br>';
		message += 'Total: $'+totalorder+'<br>';
//	}

	var data = {
		to: sendTo,
		fromname: fromName,
		fromemail: fromEmail,
	    basket: message
	};
	return data;
	
}
</script>
</body>
</html>