<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';

require '../movie/movie.php';

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
						<div class="row">
							<?php
							if ($own_collection)
							{
								?>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<?php require '../search/movieSearchView.php'; ?>
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
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="pull-right">
											<button class="btn btn-warning"><span class="fa fa-heart"></span> Favorite</button>
											<button class="btn btn-facebook"><span class="fa fa-facebook"></span> Share</button>
											<button class="btn btn-twitter"><span class="fa fa-twitter"></span> Tweet</button>
											<button class="btn btn-primary"><span class="fa fa-envelope"></span> Share</button>
										</div>
									</div>
									<?php
								}
								?>
							</div>
							<hr>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php
									$movieIds = $collection->getAllMoviesInCollection();
									if (count($movieIds) < 1)
									{
										?>
										<p>
											There's no movies in this collection yet.
										</p>
										<?php
									}
									else
									{
										$movie = new Movie();
										foreach ($movieIds as $movieId)
										{
											$movie->setValuesWithId($movieId);
											if (strlen($movie->getPlot()) > 300)
											{
												$plot = substr($movie->getPlot(), 0, 297) . ' (...)';
											}
											else
											{
												$plot = $movie->getPlot();
											}
											?>
											<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
												<div class="owned">
													<div class="movie-box" style="margin-bottom: -35px;">
														<img src="<?php echo $movie->getPosterUrl() ?>" class="thumbnail img-responsive">
														<h4><?php echo $movie->getTitle() ?></h4>
													</div>
													<p>
														<a href="<?php echo $movie->getImdbLink() ?>" target="_blank"><img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png"></a><br>
														<b>Release</b>: <?php echo $movie->getRelease() ?><br>
														<b>Runtime</b>: <?php echo $movie->getRuntime() ?><br>
														<?php echo $plot ?><br>
													</p>
												</div>
											</div>
											<?php
										}
									}
									?>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>

				<?php
				require '../includes/footer.php';
			}
			?>
