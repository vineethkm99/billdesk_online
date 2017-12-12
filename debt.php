<?php
require_once("sessions.php"); 
require_once("connection.php");
$uid = $_SESSION['user_id'];
if(isset($_GET['to'])){
	$amount=$_GET['amount'];
	$to=$_GET['to'];
	if ($to =='other') {
		$newto = $_GET['newto'];
		$sql = "INSERT INTO debt (name,amount,uid)
	    VALUES ('$newto' , '$amount','$uid')";
	    $conn->exec($sql);
	    header("Location:debt.php?add=1");
	    exit;
	}
	else{
		$sql= "UPDATE debt SET amount = amount + '$amount' WHERE id = '$to' AND uid = '$uid'";
		$conn->exec($sql);
		header("Location:debt.php?add=1");
	    exit;
	}
}

$sql = "SELECT * FROM debt WHERE uid = '$uid'";
$debt_result = $conn->query($sql);
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Debts</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.css">
	<script src="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.js"></script>
        <link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>

<?php require_once("head.php");?>
<div class="ui grid" style="margin-top: 40px;"><div class="row">


<div class="eight wide mobile eight wide computer column"><div class="ui segment">
<?php if(isset($_GET['add'])&&$_GET['add']==1){
echo "<div class=\"ui message\"><p>Your response is added</p></div>";
}?>
<form class="ui form" method="get" action="debt.php">
	<div class="field">
		<label>Given To</label>
		<select name = "to" onchange="myfunc()" id="to" class="ui dropdown">
			<option value=""></option>
			<?php foreach($result as $row){
				echo "<option value = \"".$row['id']."\">".$row['name']."</option>";
				}  
			?>
			<option value="other">Add a Person</option>
		</select>
	</div>
	<div class="field" style="display: none;" id="newto">
		<label>Name of the person</label>
		<input type="text" name="newto" placeholder="Name">
	</div>
	<div class="field">
		<label>Amount</label>
		<input type="text" name="amount" placeholder="Amount">
	</div>
	<button class="ui button" type="submit">Submit</button>
</form>
</div>
</div>
<div class="eight wide mobile eight wide computer column"><div class="ui segment">
	<table class="ui celled table">
		<thead>
			<tr>
			<th>Name</th>
			<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($debt_result as $row) {
if($row['amount']!=0){
				echo "<tr><td>".$row['name']."</td><td>".$row['amount']."</td></tr>";}
			}?>
		</tbody>
	</table>
</div>
</div>

</div>
<script type="text/javascript">
	function myfunc() {
		var sv = document.getElementById('to').value;
		if (sv == "other") {
			document.getElementById('newto').style.display = "initial";
		}
	}
	$('select.dropdown')
	  .dropdown()
	;
</script>
</body>
</html>