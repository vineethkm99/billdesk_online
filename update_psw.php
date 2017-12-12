<?php 
require_once("sessions.php"); 
require_once("connection.php"); 
// form submitted
if(isset($_POST['password'])){
	$hashed_old_pass = sha1($_POST['password']);
	$hashed_new_pass = sha1($_POST['npassword']);
	$uid = $_SESSION['user_id'];
	try{ 
		$sql = "UPDATE accounts SET hashed_pass = '$hashed_new_pass' WHERE id = $uid AND hashed_pass = '$hashed_old_pass'";
        $q=$conn->prepare($sql);
        $q->execute();
		header("Location: bill.php");
		exit;
    }
	catch(PDOException $e)
    {
    	$msg= "Original Password is incorrect";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Update password</title>
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
            },
            npassword: {
              identifier  : 'npassword',
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
        Update your password
      </div>
    </h2>
    <form class="ui large form" action="update_psw.php" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Current Password" value=<?php echo ""; ?>>
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="npassword" placeholder="New Password" value=<?php echo ""; ?>>
          </div>
        </div>
        <submit class="ui fluid large teal submit button">Submit</submit>
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