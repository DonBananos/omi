<!--
Author: R. Mike Jensen, Heini L. Ovason
-->
<?php
session_start();
require './includes/config/config.php';
require './includes/config/database.php';
require './user/user.php';
$logged_in = false;
if(isset($_SESSION['signed_in']))
{
	if($_SESSION['user_id'] > 0)
	{
		$logged_in = true;
		$active_user = new User($_SESSION['user_id']);
	}
}
if (isset($_POST['search'])) {
    //Replaces all spaces with + (for search)
    $title = preg_replace("/ /", '+', $_POST['search_field']);

    //HTTP request to OMDb API with JSON answer
    $json = file_get_contents("http://www.omdbapi.com/?t=$title&y=&plot=short&r=json&type=movie");

    //JSON decode of answer
    $data = json_decode($json, true);
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
if(isset($_SESSION['signed_in']))
{
	require './includes/navbar.php';
	?>
		<h1>Welcome Back <?php echo $active_user->getUsername() ?></h1>
	<?php
}
else
{
	require './includes/loginBar.php'; 
	require './includes/start.php';
}
require './includes/footer.php'; ?>
