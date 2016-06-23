<?php
require './collection/collectionHandler.php';
require './collection/collection.php';
require_once './movie/movieHandler.php';
require_once './movie/movie.php';
?>
<body>
	<div class="main-container">
		<?php
		require_once './includes/navbar.php';
		?>
		<div class="container">
			<div class="row"> 
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<div style="width: 100%;">
						<h3>Your Popular Collections</h3>
						<?php
						$most_popular_collections = $active_user->get_most_popular_collections();
						if ($most_popular_collections != false)
						{
							foreach ($most_popular_collections as $most_popular_collection)
							{
								$mpc = new Collection($most_popular_collection);
								$numberOfMoviesInCollection = count($mpc->getAllMoviesInCollection());
								?>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-box">
										<a href="<?php echo $path ?>collection/<?php echo $mpc->getId() ?>/<?php echo $mpc->getSlug() ?>/" title="<?php echo $mpc->getDescription(); ?>">
											<div class="collection" style="position: relative; height: 200px; background-position: center !important; background-size: cover !important; background: url(<?php echo $mpc->get_image_name() ?>);">
												<h3 style="width: 100%; padding-right: 10px; position: absolute; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); padding-left: 10px; padding-bottom: 5px; margin-bottom: 0; margin-left: 0;">
													<?php echo $mpc->getName() ?><br/>
													<small style="color: white;">
														<?php
														if ($mpc->getPrivacy())
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
														Created on: <b><?php echo formatTextDate($mpc->getCreatedDatetime()) ?> - <?php echo $numberOfMoviesInCollection ?> Movies</b>
													</small>
												</h3>
											</div>
										</a>
									</div>
								</div>
								<?php
							}
						}
						else
						{
							echo "OH NO!";
						}
						//Require a collection_handler
						//Get that handler to select all collections
						//Display these within another require
						?>
					</div>
				</div>
				<div class="ocl-lg-8 col-md-8 col-sm-12 col-xs-12">
					<h3>Popular movies</h3>
					<div class="row">
						<?php
						$mh = new MovieHandler();
						$movie_ids = $mh->get_most_popular_movies(3);
						foreacH ($movie_ids as $movie_id)
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
					<h3>Latest added movies</h3>
					<div class="row">
						<?php
						$mh = new MovieHandler();
						$movie_ids = $mh->get_latest_added_movies(3);
						foreacH ($movie_ids as $movie_id)
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
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<?php
	require_once './includes/footer.php';
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