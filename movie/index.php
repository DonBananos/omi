<?php
session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require './movie.php';
require '../user/user.php';
require '../person/person.php';
require '../genre/genre.php';
require './movieHandler.php';
?>
<html lang="en">
	<head>
		<title>Your Collections | Online movie Index</title>
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
							<h1>Movies in Users Collections</h1>
						</div>
						<div class="row">
							<?php
							$movie = new Movie();
							$mh = new MovieHandler();
							$movieIds = $mh->getAllMovieIds();
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
								<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
									<div class="owned">
										<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
											<div class="movie-box movie-box-miniature">
												<div class="image-box" id="<?php echo $movie->getImdbId() ?>">
													<img src="<?php echo $movie->getPosterUrl() ?>" class="img-responsive thumbnail">
													<div class="clearfix"></div>
												</div>
												<div class="clearfix"></div>
												<h4 class="movie-title"><?php echo $movie->getTitle() ?> (<?php echo $movie->getYear() ?>)</h4>
											</div>
										</a>
									</div>
									<div class="clearfix"></div>
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