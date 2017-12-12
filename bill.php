<?php 
require_once("sessions.php"); 
require_once("connection.php");
$uid= $_SESSION['user_id'];
$str = file_get_contents("cate.json");
$json = json_decode($str, true);
function add_newcat($json, $cat='', $subcat=''){
	$json[$cat] = [$subcat];
        $newJsonString = json_encode($json);
	file_put_contents('cate.json', $newJsonString);
}
function add_newsubcat($json, $cat='', $subcat=''){
	array_push($json[$cat], $subcat);
	$newJsonString = json_encode($json);
	file_put_contents('cate.json', $newJsonString);
}
if(isset($_GET['category'])){
	$note = $_GET['note'];
$subcategory='';
	if ($_GET['category']=='other') {
		$category = $_GET['newcat'];
$subcategory = $_GET['newsubcat'];
		add_newcat($json, $category, $subcategory);
	}else{$category = $_GET['category'];}

	if ($_GET['subcategory']=='other') {
		$subcategory = $_GET['newsubcat'];
		add_newsubcat($json, $category, $subcategory);
	}else{
	$subcategory = $_GET['subcategory'];
	}

	$amount = $_GET['amount'];
	//$paidby = $_GET['paid_by'];
	$paid_thru = $_GET['paid_thru'];
	$timestamp = strtotime($_GET['date']);
	$d = date("Y-m-d H:i:s", $timestamp);

	$sql = "INSERT INTO em (uid, note, category, subcategory , amount, paid_thru, date_of_bill)
    VALUES ('$uid', '$note' , '$category', '$subcategory' , '$amount' , '$paid_thru', '$d')";

    $conn->exec($sql);
    //array_push($msg, 'Your response is added');
    header("Location:bill.php?add=1");
    exit;
}
if(isset($_GET['sortby'])){
	if($_GET['sortby']=='category'){
		$sqlbill = "SELECT * FROM em WHERE uid = '$uid' AND amount!=0 ORDER BY category";
		$result = $conn->query($sqlbill);
	}
	elseif($_GET['sortby']=='amount'){
		$sqlbill = "SELECT * FROM em WHERE uid = '$uid' ORDER BY amount";
		$result = $conn->query($sqlbill);
	}elseif($_GET['sortby']=='date'){
		$sqlbill = "SELECT * FROM em WHERE uid = '$uid' ORDER BY date_of_bill";
		$result = $conn->query($sqlbill);
	}
}else{
	$sqlbill = "SELECT * FROM em WHERE uid = '$uid' AND amount!=0 ORDER BY id DESC";
	$result = $conn->query($sqlbill);
}
if(isset($_GET['categorizeby'])){
	$getdata = $_GET['categorizeby'];
        $gd = $getdata;
if($getdata == "month"){$gd = "date_of_bill";};
	
	$uid= $_SESSION['user_id'];
	$sql = "SELECT * FROM em WHERE uid = '$uid' ORDER BY ".$gd;
	$categorizedresult = $conn->query($sql);
	$i=0;
	$res = array();
	if($getdata=="month"){
		foreach ($categorizedresult as $row) {
			$monthdata = strtotime($row['date_of_bill']);
			$md = date("F Y", $monthdata);
			if($i==0||$md!=$j){
				$res[$md] = 0;
			}
			$res[$md]+=$row['amount'];
			$j = $md;
			$i++;
		}
	}
	else{
		foreach ($categorizedresult as $row) {
			if($i==0||$row[$getdata]!=$j){
				$res[$row[$getdata]] = 0;
			}
			$res[$row[$getdata]]+=$row['amount'];
			$j = $row[$getdata];
			$i++;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Index</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.css">
	<script src="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.js"></script>
        <link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>
	<?php require_once("head.php");?>
	<div class="ui stackable grid" style="margin-top: 40px;">
	<div class="ui fluid container"><h1 class="ui header center aligned">Expense Manager</h1></div>
	<div class="ui row">
	<div class="six wide column"><div class="ui segment">
	<?php if(isset($_GET['add'])&&$_GET['add']==1){echo "<div class=\"ui positive message\"><i class=\"close icon\"></i><p>Your response is added</p></div>";}?>
	<h2 class="ui header">Bill Desk</h2>
	<form action="bill.php" method="get" class="ui form">
		<div class="field">
			<label>Category</label>
			<select name = "category" id="cat" onchange="myfunc()" class="ui fluid dropdown">
			<?php foreach($json as $key=>$value){
				echo "<option value = \"".$key."\">".$key."</option>";
				}  
			?>
			<option value="other">Other</option>
			</select>
		</div>
		<div id="newcat"></div>
		<div class="field"><label>Sub category:</label>
		<select name="subcategory" id="subcat" onchange="others_subcat()" class="ui fluid dropdown"></select></div>
		<div id="newsubcat"></div>
		<div class="field"><label>Amount:</label>
		<input type="text" name="amount"></div>
		<div class="field">
			<label>Note</label>
			<input type="text" name="note" placeholder="Note">
		</div>
		<div class="field">
			<label>Date</label>
			<input type="date" name="date" placeholder="Date" id="datepicker">
		</div>
		<div class="field"><label>Paid through:</label>
		<select name="paid_thru">
			<option value="wallet">Wallet</option>
			<option value="card">Card</option>
		</select></div>
		<br />
		 <button class="ui button" type="submit">Submit</button>
	</form></div>
	</div>
	<div class="ten wide column">
	<div class="ui stacked segment">
	<h2 class="ui header">Data</h2>
		<table class="ui celled unstackable table">
		<thead>
			<tr><th><a href="bill.php?categorizeby=date_of_bill">Date</a> <a href="bill.php?sortby=date"><i class="sort icon"></i></a>  <a href="bill.php?categorizeby=month"><i class="calendar icon"></i></a></th>
			<th><a href="bill.php?categorizeby=category">Category </a><a href="bill.php?sortby=category"><i class="sort icon"></i></a></th>
			<th><a href="bill.php?categorizeby=subcategory">Subcategory</a></th>
			<th><a href="bill.php?cateorizeby=amount">Amount</a> <a href="bill.php?sortby=amount"><i class="sort icon"></i></a></th>
			<th>Note</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($result as $row) {
				echo "<tr>";
				$date = strtotime($row['date_of_bill']);
				echo "<td>".date("j F Y", $date)."</td>";
				echo "<td>".$row['category']."</td><td>".$row['subcategory']."</td><td>".$row['amount']."</td><td>".$row['note']."</td></tr>";
			}?>
		</tbody>
	</table></div>

	<?php if(isset($_GET['categorizeby'])){ ?>
	<div class="ui segment">
	<h2 class="ui header">Statistics</h2>
		<table class="ui celled table">
		<thead>
			<tr>
			<th><?php echo $_GET['categorizeby'];?></th>
			<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($res as $key =>$value) {
				echo "<tr>";
				echo "<td>".$key."</td><td>".$value."</td><tr>";
			}

			?>
		</tbody>
		</table>
	</div>
	<?php }?>
	</div></div>
	</div>
	<?php //require_once("foot.php");?>
	<script type="text/javascript">
		<?php echo "var categoryObj = ".$str.";"; ?>
		function myfunc() {
			var sv = document.getElementById('cat').value;
			//document.getElementById('subcat').innerHTML="";
			if (sv == "other") {others_cat();}
			else{
				document.getElementById('subcat').innerHTML+="<option value=\"\"></option"
				for(i=0; i<categoryObj[sv].length; i++){
					document.getElementById('subcat').innerHTML+= "<option value = \"" + categoryObj[sv][i] + "\">"+categoryObj[sv][i]+"<\/option>";
				}
				document.getElementById('subcat').innerHTML+="<option value = \"other\">Other</option>";
			}
		}
		myfunc();
		function others_cat() {
			// send php data to create a new category
			document.getElementById('newcat').innerHTML = "<div class = \"field\"><label>Name of new category:</label><input type=\"text\" name= \"newcat\"></div>";
			document.getElementById('newsubcat').innerHTML = "<div class=\"field\"><label>Name of new sub category:</label><input type=\"text\" name= \"newsubcat\"></div>";
			document.getElementById('subcat').innerHTML = "<option value = \"other\">Other</option>";
		}
		function others_subcat() {
			// send php data to create a new sub category
			if(document.getElementById('subcat').value == 'other'){
				document.getElementById('newsubcat').innerHTML = "<div class=\"field\"><label>Name of new sub category:</label><input type=\"text\" name= \"newsubcat\"></div";
			}
		}
		$('select.dropdown')
		  .dropdown()
		;
		$('.ui.form')
		  .form({
		  	on:'blur',
		    fields: {
		      amount:['number','empty'],
		      note: 'empty',
		      date: 'empty',
		      category: 'empty',
		      subcategory: 'empty',
		      newcat: 'empty',
		      newsubcat: 'empty'
		    }
		  })
		;
		$('.message .close')
		  .on('click', function() {
		    $(this)
		      .closest('.message')
		      .transition('fade')
		    ;
		  })
		;
	</script>
</body>
</html>