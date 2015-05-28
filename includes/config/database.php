<?php
$dbCon = new mysqli('localhost', 'root', 'mik89jen', 'omi');
if ($dbCon->connect_errno) 
{
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}