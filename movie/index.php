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
							<h1>Movies in Users' Collections</h1>
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
								if ($movie->getLocalTitleIfExists() != false)
								{
									$title = $movie->getLocalTitleIfExists();
								}
								else
								{
									$title = $movie->getTitle();
								}
								?>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div class="owned">
										<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
											<div class="mbb" style="background: url(<?php echo $movie->getPosterUrl() ?>)" id="movie-<?php echo $movie->getImdbId() ?>">
												<div class="mbbt">
													<h4>
														<?php echo $title ?>
													</h4>
													<h5>
														<?php echo $movie->getYear() ?>
														<span class="pull-right">
															<span class="fa fa-heart fa-fw fa-clr-warning"></span>
															<span class="fa fa-plus fa-fw fa-clr-success"></span>
														</span>
													</h5>
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
		<script>
			$(".mbb").mouseenter(function () {
				$(this).find(".mbbt").slideToggle();
			});
			$(".mbb").mouseleave(function () {
				$(this).find(".mbbt").slideToggle();
			});
		</script>
	</body>
</html>