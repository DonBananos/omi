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
	<body>

		<!--
		This makes sure that all content has a margin of 50px below navbar. 
		Without this "tweek" in mobile viewport the content will slide up behind 
		the navbar.
		-->
		<div class="main-container">
			<?php
			if (isset($_SESSION['signed_in']))
			{
				require './includes/navbar.php';
				?>
				<div class="container">
					<?php
					require './includes/memberStart.php';
					require './includes/footer.php';
					?>
				</div>
				<?php
			}
			else
			{
				require './includes/loginBar.php';
				?>
				<div class="container">
					<?php
					require './includes/start.php';
					require './includes/footer.php';
					?>
				</div>
				<?php
			}
			?>
