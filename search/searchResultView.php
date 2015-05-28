<?php
$searchString = $_GET['searchString'];
$collectionId = $_GET['cid'];

//Replaces all spaces with + (for search)
$title = preg_replace("/ /", '+', $searchString);

//HTTP request to OMDb API with JSON answer
$json = file_get_contents("http://www.omdbapi.com/?s=$title&r=json&type=movie");

//JSON decode of answer
$data = json_decode($json, true);
?>
<div style="height: 400px; overflow: auto" id="movieSearchResultAreaHold">
	<?php
	foreach ($data as $result)
	{
		foreach ($result as $movie)
		{
			?>
			<form method="post" role="form" action="./addMovie/">
				<div class="col-lg-6 movie-box">
					<?php
					$title = $movie['Title'];
					$year = $movie['Year'];
					$imdbId = $movie['imdbID'];
					$type = $movie['Type'];
					$json = file_get_contents("http://www.omdbapi.com/?i=$imdbId&plot=full&r=json");

					//JSON decode of answer
					$movieData = json_decode($json, true);
					if (isset($movieData))
					{
						$imageUrl = $movieData['Poster'];
						$plot = $movieData['Plot'];
						$release = $movieData['Released'];
						$runtime = $movieData['Runtime'];
						$language = $movieData['Language'];
						$genre = $movieData['Genre'];
						if ($imageUrl != "N/A")
						{
							?>
							<img class="thumbnail img-responsive" src="<?php echo $imageUrl ?>">
							<?php
						}
						?>
						<input type="hidden" name="plot" value="<?php echo $plot ?>">
						<input type="hidden" name="release" value="<?php echo $release ?>">
						<input type="hidden" name="poster" value="<?php echo $imageUrl ?>">
						<input type="hidden" name="runtime" value="<?php echo $runtime ?>">
						<input type="hidden" name="language" value="<?php echo $language ?>">
						<?php
					}
					?>
					<h4>
						<?php echo $title ?> (<?php echo $year ?>) 
						<a href="http://www.imdb.com/title/<?php echo $imdbId ?>/" target="_blank">
							<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
						</a>
					</h4>
					<input type="hidden" name="title" value="<?php echo $title ?>">
					<input type="hidden" name="imdbId" value="<?php echo $imdbId ?>">
					<input type="hidden" name="genre" value="<?php echo $genre ?>">
					<input type="hidden" name="collectionId" value="<?php echo $collectionId ?>">
					<input type="submit" name="submit" class="btn btn-success" value="Select">
				</div>
			</form>
			<?php
		}
	}
	?>
</div>
<div class="clearfix"></div>