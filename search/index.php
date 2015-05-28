<!--
Author: R. Mike Jensen, Heini L. Ovason
-->
<?php
session_start();
require '../includes/config/config.php';
require '../includes/config/database.php';
require '../user/user.php';
$logged_in = false;
if (isset($_SESSION['signed_in']))
{
	if ($_SESSION['user_id'] > 0)
	{
		$logged_in = true;
		$active_user = new User($_SESSION['user_id']);
	}
}
else
{
	?><script>
	window.location = '<?php echo $path ?>';
	</script><?php
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Search | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>

		<!--
		This makes sure that all content has a margin of 50px below navbar. 
		Without this "tweek" in mobile viewport the content will slide up behind 
		the navbar.
		-->
		<div class="main-container">
			<?php
				require '../includes/navbar.php';
				require './movieSearchView.php';
			require '../includes/footer.php';
			?>


