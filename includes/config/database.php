<?php
	$dbCon = new mysqli('85.119.155.19', 'phplogin', 'Mowgli42', 'omi');
	if ($dbCon->connect_errno) 
	{
  		printf("Connect failed: %s\n", $mysqli->connect_error);
   		exit();
	}
?>