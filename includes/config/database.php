<?php
$dbCon = new mysqli('localhost', 'omi', '8aHsad8aja0vhajscSIa8asijioIS79A', 'omi');
if ($dbCon->connect_errno)
{
	printf("Connect failed: %s\n", $dbCon->connect_error);
	exit();
}
$dbCon->set_charset("utf8");
