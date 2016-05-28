<!--
Author: Heini L. Ovason
-->

<div class="container">
    <div class="row"> 
		<?php
		require './collection/collectionHandler.php';
		require './collection/collection.php';
		require_once './movie/movieHandler.php';
		require_once './movie/movie.php';
		?>
		<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
			<div style="width: 100%;">
				<h3>Dine mest brugte collections</h3>
				<?php
				$most_popular_collection = $active_user->get_most_popular_collection();
				if ($most_popular_collection != false)
				{
					$mpc = new Collection($most_popular_collection);
					$numberOfMoviesInCollection = count($mpc->getAllMoviesInCollection());
					?>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;">
						<a href="<?php echo $path ?>collection/<?php echo $mpc->getId() ?>/<?php echo $mpc->getSlug() ?>/" title="<?php echo $mpc->getDescription(); ?>">
							<div class="collection" style="position: relative; height: 200px; background-position: center !important; background-size: cover !important; background: url(http://cdn.home-designing.com/wp-content/uploads/2011/11/home-movie-collection.jpg);">
								<h3 style="width: 100%; padding-right: 10px; position: absolute; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); padding-left: 10px; padding-bottom: 5px; margin-bottom: 0; margin-left: 0;">
									<?php echo $mpc->getName() ?><br/>
									<small style="color: white;">
										Created on: <b><?php echo formatTextDate($mpc->getCreatedDatetime()) ?> - <?php echo $numberOfMoviesInCollection ?> Movies</b>
									</small>
								</h3>
							</div>
						</a>
					</div>
					<div class="clearfix"></div>
					<?php
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
		<div class="ocl-lg-5 col-md-5 col-sm-5 col-xs-12">
			<h3>Latest added movies</h3>
			<?php
			$mh = new MovieHandler();
			$movie_ids = $mh->get_latest_added_movies(6);
			foreacH ($movie_ids as $movie_id)
			{
				$movie = new Movie($movie_id);
				?>
				<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/"><h5><?php echo $movie->getTitle() ?> (<?php echo $movie->getYear() ?>)</h5></a>
				<?php
			}
			?>
		</div>
		<div class="clearfix"></div>