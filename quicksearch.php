<?php
if (isset($_GET['q']))
{
	$search_term = $_GET['q'];
}

session_start();

require './includes/config/config.php';
require './includes/config/database.php';

require './user/user.php';

require './movie/movieHandler.php';
require './movie/movie.php';
?>
<html lang="en">
	<head>
		<title>OMI Search | Online movie Index</title>
		<?php require './includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php
			if (isset($_SESSION['signed_in']))
			{
				$active_user = new User($_SESSION['user_id']);
				require './includes/navbar.php';
			}
			else
			{
				require './includes/loginBar.php';
			}
			?>
			<div class="container">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row top-mar-25" id="quicksearchtop">
						<div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
							<form action="" method="GET" name="quicksearchform" role="search">
								<input type="text" class="form-control input-lg" name="q" placeholder="Search" value="<?php echo $search_term ?>">
								<div class="row top-mar-20">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<button type="submit" class="btn btn-omi btn-lg btn-full-width"><span class="fa fa-search"></span> Search</button>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
										<button type="button" class="btn btn-default btn-lg btn-full-width" id="history-search-btn"><span class="fa fa-history fa-omi-blue" id="history-search-btn-fa"></span><span class="hidden-xs"> Search</span> History</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="row" id="quicksearchresults">
						<?php
						if (isset($search_term))
						{
							$mh = new MovieHandler();
							$movie_ids = $mh->get_movies_from_search_string($search_term, 0, 50);
							?>
							<div class="col-lg-3 col-md-2 col-sm-1 col-xs-12">
								<ul class="list-unstyled">
									<li><a href="#"><span class="fa fa-film"></span> Movies</a></li>
									<li><a href="#"><span class="fa fa-star"></span> People</a></li>
									<li><a href="#"><span class="fa fa-list"></span> Collections</a></li>
									<li><a href="#"><span class="fa fa-users"></span> Users</a></li>
								</ul>
							</div>
							<div class="col-lg-9 col-md-10 col-sm-11 col-xs-12">
								<h3>Results on: '<?php echo $search_term ?>'</h3>
								<?php
								foreach ($movie_ids as $movie_id)
								{
									$movie = new Movie($movie_id);
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
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 movie-cover">
										<div class="owned movie-cover-inside">
											<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
												<div class="mbb mbb-sm" style="background: url(<?php echo $movie->getPosterUrl() ?>)" id="movie-<?php echo $movie->getImdbId() ?>">
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
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		require './includes/footer.php';
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
