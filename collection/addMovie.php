<?php
session_start();
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';
require './collection.php';
require './collectionHandler.php';
require '../movie/movie.php';
require '../genre/genre.php';

$title = $_POST['title'];
$imdbId = $_POST['imdbId'];
$plot = $_POST['plot'];
$release = $_POST['release'];
$poster = $_POST['poster'];
$runtime = $_POST['runtime'];
$language = $_POST['language'];
$collectionId = $_GET['cid'];
$genreCollection = $_POST['genre'];

$collection = new Collection($collectionId);

if ($_SESSION['signed_in'])
{
	$active_user = new User($_SESSION['user_id']);
	if ($active_user->getId() == $collection->getUserId())
	{
		$proceed = true;
	}
	else
	{
		$proceed = false;
	}
}
else
{
	$proceed = false;
}

if (!$proceed)
{
	?>
	<script>
		alert("You are not allowed to alter this collection!");
		window.location = '<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/';
	</script>
	<?php
	die();
}
$movie = new Movie();
$movieExists = $movie->checkIfMovieAlreadyExists($imdbId);
if ( $movieExists === false)
{
	$answer = $movie->createMovie($title, $plot, $release, $runtime, $imdbId, $poster, $language);
	if ($answer === true)
	{
		$genre = new Genre();
		$genres = explode(',', $genreCollection);
		foreach ($genres as $genreName)
		{
			$answer = $genre->createGenre(trim($genreName));
			if ($answer === true)
			{
				$answer = $genre->saveGenreToMovie($movie->getId());
			}
		}
	}
}
else
{
	$movie->setValuesWithId($movieExists);
}
$answer = $movie->saveMovieToCollection($collection->getId());
if ($answer !== true)
{
	?>
	<script>
		alert("Error saving the movie <?php echo $movie->getTitle() ?> to the collection <?php echo $collection->getName() ?>");
	</script>
	<?php
}
?>
<script>
	window.location = '<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/';
</script>
