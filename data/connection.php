<?php 
error_reporting('E_ALL');
function connect_db()
{
		$dbhost = "localhost" ;
		$dbuser = "abhijit_lsl1";
		$dbpass = "sqllsl1";
		$dbname = "abhijit_ces";
		
		$conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
		$conn->host = $dbhost;
		$conn->user = $dbuser;
		$conn->password = $dbpass;
		$conn->dbname = $dbname;

	if($conn){
		//'connected success.!';
	}
	else{
		 echo 'not connected successfully.!';
	}
	return $conn;
}
 
?>