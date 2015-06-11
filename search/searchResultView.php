<?php
/*
 * This function receives a search term, calls the intern search api, and prints
 * the results in a list.
 */

require '../includes/config/config.php';

$searchString = $_GET['s'];
$cid = $_GET['cid'];

//Replaces all spaces with + (for search)
$title = preg_replace("/ /", '+', $searchString);

//HTTP request to OMDb API with JSON answer
$json = file_get_contents("http://www.omdbapi.com/?s=$title&r=json&type=movie");

//JSON decode of answer
$data = json_decode($json, true);
?>
<div id="externSearchResults">
	<?php
	foreach ($data as $result)
	{
		if ($result == "Movie not found!")
		{
			
		}
		elseif ($result != "False")
		{
			?><small id="externSearchIntro">External results:</small><br><?php
			foreach ($result as $movie)
			{
				?>
				<div class="col-lg-12">
					<?php
					$title = $movie['Title'];
					$year = $movie['Year'];
					if ($year == null)
					{
						$year = "N/A";
					}
					$imdbId = $movie['imdbID'];
					$type = $movie['Type'];
					?>
					<h4 class="movie-title-search-link movie-title" id="<?php echo $imdbId ?>">
						<?php echo $title ?> (<?php echo $year ?>)
						<a href="http://www.imdb.com/title/<?php echo $imdbId ?>/" target="_blank">
							<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
						</a>
					</h4>
				</div>
				<?php
			}
		}
		else
		{
			?><small id="externSearchIntro">No results. Please try another search term</small><br><?php
		}
	}
	?>
</div>
<div class="clearfix"></div>
<script>
	$(".movie-title").on("click", function () {
		$('#movieSelectedArea').hide();
		$('#MovieWaiting').show();
		var selection = $(this).attr('id');
		$.get('<?php echo $path ?>search/movieSelectView.php', {i: selection, cid: <?php echo $cid ?>}, function (respons) {
			$('#MovieWaiting').hide();
			$('#movieListingArea').html(respons);
		});
	});
</script>