<?php
session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';
require '../collection/collection.php';

if (!isset($_GET['id']))
{
	if (!isset($_SESSION['user_id']))
	{
		?>
		<script>
			window.location = "<?php echo BASE_URL ?>";
		</script>
		<?php
		die();
	}
	?>
	<script>
		window.location = "<?php echo BASE_URL ?>user/<?php echo $_SESSION['user_id'] ?>/";
	</script>
	<?php
	die();
}
$user_id = $_GET['id'];

$user = new User($user_id);
?>
<html lang="en">
	<head>
		<title><?php echo $user->getUsername() ?> | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php
			if (isset($_SESSION['signed_in']))
			{
				$active_user = new User($_SESSION['user_id']);
				require '../includes/navbar.php';
			}
			else
			{
				require '../includes/loginBar.php';
			}
			?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="page-header">
							<h1><?php echo $user->getUsername() ?></h1>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
						
					</div>
					<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
						<div id="user-collections">
							<h3><?php echo $user->get_possessed_username() ?> Collections</h3>
							<?php
							$collection_ids = $active_user->get_most_popular_collections(500);
							foreach ($collection_ids as $collection_id)
							{
								$collection = new Collection($collection_id);
								$numberOfMoviesInCollection = count($collection->getAllMoviesInCollection());
								?>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collection-box">
										<a href="<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/" title="<?php echo $collection->getDescription(); ?>">
											<div class="collection" style="background: url(<?php echo $collection->get_image_name() ?>);">
												<div class="overlay"></div>
												<div class="collection-title">
													<h3>
														<?php echo $collection->getName() ?><br/>
													</h3>
													<small>
														<?php
														if ($collection->getPrivacy())
														{
															?>
															<span class="fa fa-lock fa-omi-blue" title="Private Collection"></span>
															<?php
														}
														else
														{
															?>
															<span class="fa fa-unlock fa-omi-blue" title="Public Collection"></span>
															<?php
														}
														?>
														Created on: <b><?php echo formatTextDate($collection->getCreatedDatetime()) ?> - <?php echo $numberOfMoviesInCollection ?> Movies</b>
													</small>
												</div>
											</div>
										</a>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		require '../includes/footer.php';
		?>
	</body>
</html>