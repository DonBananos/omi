<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$searchImdbId = $_GET['i'];
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
$movie = new imdb($imdbId);
$year = $movie->year();
?>
<div id = "movieSelectedArea">
	<p>
		<?php
		
		?>
	</p>
	<div class = "col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<label><span class="label-title">Title: </span><?php echo $movie->title();?></label>
		<label><span class="label-title">Original: </span><?php echo $movie->orig_title();?></label>
		<label><span class="label-title">Released: </span><?php echo $year ?></label>
		<label><span class="label-title">Runtime: </span><?php echo $movie->runtime() ?> minutes</label>
		<label><span class="label-title">Language: </span><?php echo $movie->language() ?></label>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
<img class="thumbnail img-responsive" src="<?php echo $movie->photo(true) ?>">
	</div>
	<div class="clearfix"></div>
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

	</div>
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

	</div>
</div>