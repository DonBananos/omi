<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$searchImdbId = $_GET['i'];
$cid = $_GET['cid'];
$imdbId = substr($searchImdbId, -7);
//$collectionId = $_GET['cid'];
/*
 * Check if the cache folder exists in the imdbphp api.
 * if it doesn't - then create it!
 */
if (!file_exists('../includes/api/imdbphp/cache'))
{
	mkdir('../includes/api/imdbphp/cache', 0777, true);
}
require '../includes/api/imdbphp/imdb.class.php';
require '../includes/api/imdbphp/imdb_person.class.php';
require '../includes/config/config.php';

require '../includes/config/database.php';

require '../collection/collection.php';
$collection = new Collection($cid);
$movie = new imdb($imdbId);
$year = $movie->year();
?>
<div id = "movieSelectedArea">
	<hr class="visible-xs">
	<p>
		<?php
		?>
	</p>
	<div class = "col-lg-8 col-md-8 col-sm-8 col-xs-12" style="height: 30vh; overflow-y: auto">
		<label><span class="label-title">Title: </span><?php echo $movie->title(); ?></label><br>
		<label><span class="label-title">Original: </span><?php echo $movie->orig_title(); ?></label><br>
		<label><span class="label-title">Released: </span><?php echo $year ?></label><br>
		<label><span class="label-title">Runtime: </span><?php echo $movie->runtime() ?> minutes</label><br>
		<label><span class="label-title">Language: </span>
			<?php
			echo $movie->language();
			?></label><br>
		<label><span class="label-title">Genres: </span>
			<?php
			$numberOfGenres = count($movie->genres());
			$count = 0;
			foreach ($movie->genres() AS $genre)
			{
				echo $genre;
				$count++;
				if ($count < $numberOfGenres)
				{
					echo ', ';
				}
			}
			?>
		</label><br>
		<label><span class="label-title">Director: </span>
			<?php
			foreach ($movie->director() as $director)
			{
				$person = new imdb_person($director['imdb']);
				echo '<a href="http://www.imdb.com/name/nm' . $person->imdbid() . '/">' . $person->name() . '</a><br>';
			}
			?>
		</label><br>
		<a href="http://www.imdb.com/title/tt<?php echo $movie->imdbid() ?>/" target="_blank">
			<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
		</a>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<img class="thumbnail img-responsive pull-right movie-search-poster" src="<?php echo $movie->photo(true) ?>">
	</div>
	<div class="clearfix"></div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<label><span class="label-title">Plot: </span></label><br>
		<p><?php echo $movie->plotoutline() ?></p>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
		<p>
			<label><span class="label-title">Cast: </span></label><br></p>
		<div style="height: 28vh; overflow-y: auto"><p>
				<?php
				foreach ($movie->cast() as $star)
				{
					if ($star['thumb'] != null)
					{
						?>
						<img src="<?php echo $star['thumb'] ?>">
						<?php
						echo $star['name'] . ':<i> ' . $star['role'] . '</i><br>';
						$movieStar = new imdb_person($star['imdb']);
					}
				}
				?></p>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<form action="<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getName() ?>/addMovie/" method="post">
			<input type="hidden" value="<?php echo $movie->imdbid() ?>" name="imdbId">
			<input type="hidden" value="<?php echo $cid ?>" name="cid">
			<input type="submit" value="Save to Collection" class="btn btn-success">
		</form>
	</div>
</div>