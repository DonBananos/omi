<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';

//Instantiate new Collection object
$collection = new Collection($_GET['id']);

$owner = new User($collection->getUserId());

$signed_in = false;
$own_collection = false;
$collection_private = true;

//Check if user is signed in
if (isset($_SESSION['signed_in']))
{
	$userId = $_SESSION['user_id'];
	if ($userId > 0)
	{
		$active_user = new User($userId);
		$signed_in = true;
		if ($active_user->getId() == $owner->getId())
		{
			$own_collection = true;
		}
	}
}
if ($collection->getPrivacy() != 1)
{
	$collection_private = false;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Your Collections | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php require '../includes/navbar.php'; ?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="page-header">
							<h1><?php echo $collection->getName() ?><br>
								<small>
									Created on: <?php echo formatShortDateTime($collection->getCreatedDatetime()) ?> by <?php echo $owner->getUsername() ?>
								</small>
							</h1>
						</div>
						<?php
						if ($own_collection)
						{
							?>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<?php require '../search/movieSearchView.php'; ?>
								</div>
							</div>
							<?php
						}
						if ($collection_private AND ! $own_collection)
						{
							?>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<p>This Collection is private, and you are not allowed to see it</p>
							</div>
							<?php
						}
						elseif (!$collection_private OR $own_collection)
						{
							if (!$collection_private)
							{
								?>
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										Share on Social Media buttons!
									</div>
								</div>
								<?php
							}
							?>
						<hr>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php
										$movieIds = $collection->getAllMoviesInCollection();
										if(count($movieIds) < 1)
										{
											?>
									<p>
										There's no movies in this collection yet.
									</p>
											<?php
										}
									?>
								</div>
							</div>
							<?php
						}
						?>
