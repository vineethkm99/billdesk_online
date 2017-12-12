<?php 
  session_start();
?>
<?php 
require_once("connection.php"); 

if (isset($_POST['password'])) {
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $hashed_pass = sha1($pass);
  $sql = "SELECT id,name FROM accounts WHERE email= '$email' AND hashed_pass= '$hashed_pass' LIMIT 1";
    $q=$conn->prepare($sql);
    $q->execute();
    $row = $q->fetch(PDO::FETCH_ASSOC);
    if(empty($row)){
      $msg = "Email or password is incorrect";
    }
    else{
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      header("Location: bill.php");
      exit;
    }





  // if ($hashed_pass == "932f7257c82e340e4083664e137aa0d863b7214e") {
  //  $_SESSION['status']= 1;
  //  header("Location:bill.php");
  //  exit;
  // }
  // else{
  //  $msg = "Password is incorrect";
  // }
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
        Log-in to your account
      </div>
    </h2>
    <form class="ui large form" action="login.php" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="email" placeholder="E-mail address">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" placeholder="Password">
          </div>
        </div>
        <submit class="ui fluid large teal submit button">Login</submit>
      </div>
      <div class="ui error message"></div>
      <div class="ui info message">
        <?php if (isset($msg)) {
          echo $msg;
        }?>
        <?php
        if (isset($_GET['logout'])&&$_GET['logout']==1) {
          echo "You are now logged out";
        }
        ?>
      </div>

    </form>

     <div class="ui message">
      New to us? <a href="signup.php">Sign Up</a>
    </div> 
  </div>
</div>
</body>
</html> 