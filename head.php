<div class="ui fixed inverted stackable menu">
	<div class="ui container">
	<a href="http://www.billdesk.esy.es" class="header item">
                <img class="logo" src="logo.png" style="margin-right: 1.5em;">
		Bill Desk
	</a>
	
	 <div class="right menu">
		<a class="ui item" href="bill.php">Bill</a>
		<a class="ui item" href="debt.php">Debt</a>

		<div class="ui simple dropdown item">
		<?php echo $_SESSION['user_name'];?> <i class="dropdown icon"></i>
		<div class="menu">
		<a class="item" href="update_psw.php">Update Password</a>
		<a class="item" href="logout.php">Log Out</a>
		<!-- <div class="divider"></div>
		<div class="header">Header Item</div>
		<div class="item">
			<i class="dropdown icon"></i>
			Sub Menu
			<div class="menu">
			<a class="item" href="#">Link Item</a>
			<a class="item" href="#">Link Item</a>
			</div>
		</div> -->
		</div>
	 
	</div>
	</div>
	</div>
</div>