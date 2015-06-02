<?php
$searchString = $_GET['searchString'];
//$collectionId = $_GET['cid'];

require '../includes/api/imdbphp/imdb.class.php';
require '../includes/api/imdbphp/imdbsearch.class.php';
$search = new imdbsearch();
$results = $search->search($searchString, [imdbsearch::MOVIE])

?>
<div style="height: 400px; overflow: auto" id="movieSearchResultAreaHold">
	<?php
	foreach ($results as $movie)
	{
		?>
			<div class="col-lg-6 movie-box"style="margin-bottom: 40px;">
				<img class="thumbnail img-responsive" src="<?php echo $movie->photo() ?>">
				<h4>
					<?php echo $movie->title() ?> (<?php echo $movie->year() ?>) 
					<a href="http://www.imdb.com/title/<?php echo 'tt'.$movie->imdbid() ?>/" target="_blank">
						<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
					</a>
				</h4>
			</div>
		<?php
	}
	?>
</div>
<div class="clearfix"></div>

<!--
<p>
			<b>Genres:</b><br>
			<?php
			foreach($movie->genres() AS $genre)
			{
				echo $genre.'<br>';
			}
			?>
			<b>Cast:</b><br>
			<?php
			foreach($movie->cast() as $star)
			{
				if($star['thumb'] != null)
				{
					?>
			<img src="<?php echo $star['thumb'] ?>">
				<?php
				echo $star['name'].' playing '.$star['role'].'<br>';
				}
			}
			?>
		</p>