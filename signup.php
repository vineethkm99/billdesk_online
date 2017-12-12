<?php 
	session_start();
	require_once("connection.php");?>
<?php 
// START FORM PROCESSING
if (isset($_POST['name'])) { // Form has been submitted.
	$name = $_POST['name'];
	$email = $_POST['email'];
	$pass = $_POST['password'];
	$hashed_pass = sha1($pass);

	// check if already created
	$sql = "SELECT id FROM accounts WHERE email= '$email'";
	$q=$conn->prepare($sql);
	$q->execute();
	// if no problems with db add to db
	if($q->rowCount()==0){
		$sql = "INSERT INTO accounts (name, email, hashed_pass)
	    VALUES ('$name' , '$email', '$hashed_pass')";
	    // use exec() because no results are returned
	    $conn->exec($sql);
	    // redirect
	    $_SESSION['user_id'] = $conn->lastInsertId();
	    $_SESSION['user_name'] = $name;
	    header("Location: bill.php");
	    exit;
	}
	else{
		$msg= 'This email or mobile is already registered';
	}
} else { // Form has not been submitted.
	$name = "";
	$email = "";
	$mobile = "";
	$gender = "";
	$pass = "";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.css">
	<script src="https://cdn.jsdelivr.net/semantic-ui/2.2.4/semantic.min.js"></script>
        <link rel="shortcut icon" href="/favicon.ico" />
	<style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <script>
  $(document)
    .ready(function() {
      $('.ui.form')
        .form({
          fields: {
            name:{
              identifier: 'name',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your name'
                }
                ]
            },
          	email: {
              identifier  : 'email',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your e-mail'
                },
                {
                  type   : 'email',
                  prompt : 'Please enter a valid e-mail'
                }
              ]
            },
            password: {
              identifier  : 'password',
              rules: [
                {
                  type   : 'empty',
                  prompt : 'Please enter your password'
                },
                {
                  type   : 'length[6]',
                  prompt : 'Your password must be at least 6 characters'
                }
              ]
            }
          }
        })
      ;
    })
  ;
  </script>
</head>
<body>
<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <div class="content">
        Create an account
      </div>
    </h2>
    <form class="ui large form" action="signup.php" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="name" placeholder="User name" value=<?php echo $name; ?>>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail address" value=<?php echo $email; ?>>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password" value=<?php echo ""; ?>>
          </div>
        </div>
        <submit class="ui fluid large teal submit button">Login</submit>
      </div>

      <div class="ui info message">
      	<?php if (isset($msg)) {
      		echo $msg;
      	}?>
      </div>

    </form>

    <!-- <div class="ui message">
      New to us? <a href="#">Sign Up</a>
    </div> -->
  </div>
</div>
</body>
</html>