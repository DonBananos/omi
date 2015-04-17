<?php
session_start();
require '../includes/config/database.php';
require '../includes/config/config.php';
require './user.php';

if ($_SESSION['signed_in'] === true)
{
	$active_user = new User($_SESSION['user_id']);
	$active_user->logout();
	
	$logout = true;
}
else
{
	?>
	<script>window.location = '/onlineMovieIndex/';</script>
	<?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Logout | Online movie Index</title>
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
if(isset($_SESSION['signed_in']))
{
	require '../includes/navbar.php';
}
else
{
	require '../includes/loginBar.php'; 
}

?>
		<div class="col-lg-12" style="margin: 0auto">
			<?php
			if($logout)
			{
				?>
			<h3 style="text-align: center">
				You were succesfully logged out.<br>
				Please come back soon for more awesome content!
			</h3>
			<script>
			window.setTimeout(function()
			{
				window.location.href = "/onlineMovieIndex/";
			}, 5000);
			</script>
				<?php
			}
			else
			{
				?>
			<h3 style="text-align: center">
				Something went wrong. Please try again.<br>
				If this keeps happening, please contact an administrator.
			</h3>
				<?php
			}
			?>
		</div>
<?php require '../includes/footer.php'; ?>