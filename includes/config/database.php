<?php
$dbCon = new mysqli('localhost', 'root', 'mik89jen', 'omi');
if ($dbCon->connect_errno) 
{
	printf("Connect failed: %s\n", $dbCon->connect_error);
	exit();
}
$dbCon->set_charset("utf8");