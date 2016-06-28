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
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-options" id="movie-index-menu">
						<ul>
							<li><a href="#" data-target="movie-popular" class="toggleMovieOverview">Popular</a></li>
							<li><a href="#" data-target="movie-latest" class="toggleMovieOverview">Newest Added</a></li>
							<li><a href="#" data-target="movie-highest" class="toggleMovieOverview">Highest Rated (IMDb)</a></li>
							<li><a href="#" data-target="movie-recommended" class="toggleMovieOverview">Recommended</a></li>
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-overview" id="movie-popular">
						<?php
						$mh = new MovieHandler();
						$popular_movie_ids = $mh->get_most_popular_movies(60);
						foreach($popular_movie_ids as $popular_movie_id)
						{
							$movie = new Movie($popular_movie_id);
							if ($movie->getLocalTitleIfExists() != false)
							{
								$title = $movie->getLocalTitleIfExists();
							}
							else
							{
								$title = $movie->getTitle();
							}
							?>
							<div class="movie-cover">
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
														<span class="fa fa-heart fa-fw fa-orange"></span>
														<span class="fa fa-plus fa-fw fa-green"></span>
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
						<br/>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-overview" id="movie-latest">
						<?php
						$newest_movie_ids = $mh->get_latest_added_movies(60);
						foreach($newest_movie_ids as $newest_movie_id)
						{
							$movie = new Movie($newest_movie_id);
							if ($movie->getLocalTitleIfExists() != false)
							{
								$title = $movie->getLocalTitleIfExists();
							}
							else
							{
								$title = $movie->getTitle();
							}
							?>
							<div class="movie-cover">
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
														<span class="fa fa-heart fa-fw fa-orange"></span>
														<span class="fa fa-plus fa-fw fa-green"></span>
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
						<br/>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-overview" id="movie-highest">
						<?php
						$highest_rated_imdb = $mh->get_highest_imdb_rated_movies(60);
						foreach($highest_rated_imdb as $highest_rated_imdb_movie_id)
						{
							$movie = new Movie($highest_rated_imdb_movie_id);
							if ($movie->getLocalTitleIfExists() != false)
							{
								$title = $movie->getLocalTitleIfExists();
							}
							else
							{
								$title = $movie->getTitle();
							}
							?>
							<div class="movie-cover">
								<div class="owned movie-cover-inside">
									<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
										<div class="mbb mbb-sm" style="background: url(<?php echo $movie->getPosterUrl() ?>)" id="movie-<?php echo $movie->getImdbId() ?>">
											<div class="mbbt" style="text-align: left">
												<h4>
													<?php echo $title ?> (<?php echo $movie->getYear() ?>)
												</h4>
												<h5>
													<img src="<?php echo $path ?>includes/img/imdblogo.png" alt="IMDb logo"> <span class="fa fa-star fa-orange"></span> <?php echo $movie->get_latest_imdb_rating() ?>
													<span class="pull-right">
														<span class="fa fa-heart fa-fw fa-orange"></span>
														<span class="fa fa-plus fa-fw fa-green"></span>
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
						<br/>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-overview" id="movie-recommended">
						<p class="lead">We're working hard on a tight Recommender System, 
						that'll use your collections and ratings to recommend your
						next favorite movies for you!</p>
						<?php
						/*
						$newest_movie_ids = $mh->get_latest_added_movies(60);
						foreach($newest_movie_ids as $newest_movie_id)
						{
							$movie = new Movie($newest_movie_id);
							if ($movie->getLocalTitleIfExists() != false)
							{
								$title = $movie->getLocalTitleIfExists();
							}
							else
							{
								$title = $movie->getTitle();
							}
							?>
							<div class="movie-cover">
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
														<span class="fa fa-heart fa-fw fa-orange"></span>
														<span class="fa fa-plus fa-fw fa-green"></span>
													</span>
												</h5>
											</div>
										</div>
									</a>
								</div>
							</div>
							<?php
						}*/
						?>
						<br/>
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
			
			$(".toggleMovieOverview").click(function(){
				var target = $(this).attr("data-target");
				$(".movie-overview").hide();
				var target = "#" + target;
				$(target).fadeIn();
			});
		</script>
	</body>
</html>