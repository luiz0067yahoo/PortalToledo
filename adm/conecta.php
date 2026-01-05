
<?php

	$my_Db_Connection=null;
	$servername		=	"localhost";
	$username		=	"u455891610_dogecoin";
	$password	=	"G/1PN~$[4Uc";
	$database	=	"u455891610_elonmusk";
	$str_connecta = "mysql:host=$servername;dbname=$database;";
	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
	// Create a new connection to the MySQL database using PDO, $my_Db_Connection is an object
	
	try { 
	  $my_Db_Connection = new PDO($str_connecta, $username, $password	, $dsn_Options);
	  //echo "Connected successfully";
	} catch (PDOException $error) {
	   //echo 'Connection error: ' . $error->getMessage();
	}
	

?>
