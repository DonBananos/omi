<!--
Author: R. Mike Jensen, Heini L. Ovason
-->
<?php
session_start();
require './includes/config/config.php';
require './includes/config/database.php';
require './user/user.php';
$logged_in = false;
if (isset($_SESSION['signed_in']))
{
	if ($_SESSION['user_id'] > 0)
	{
		$logged_in = true;
		$active_user = new User($_SESSION['user_id']);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Online movie Index</title>
		<?php require './includes/header.php'; ?>
	</head>
	<?php
	if ($logged_in)
	{
		require_once './includes/memberStart.php';
	}
	else
	{
		require_once './includes/landing.php';
	}
	?>
</html>