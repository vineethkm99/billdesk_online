<?php  
$servername = "mysql.hostinger.in";
$username = "u218143657_root";
$password = "maruvin8";
$dbname = "u218143657_exm";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

?>