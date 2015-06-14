<?php
session_start();
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';
require './collection.php';
require './collectionHandler.php';
require '../movie/movie.php';
require '../genre/genre.php';
require '../person/person.php';

require '../includes/api/imdbphp/imdb.class.php';
require '../includes/api/imdbphp/imdb_person.class.php';

$cid = $_POST['cid'];
$imdbNumberId = $_POST['imdbId'];
$imdbId = 'tt' . $imdbNumberId;

/*
 * Make this file only call omdbapi, save only title and poster (together with imdbid)
 * in the db, and after that call a new function (via async ajax) that makes use
 * of imdbphp and saves as much data as possible, together with two new tables
 * with all different titles for a movie (movie_title) and all releases (movie_release).
 * Both tables should also have the country code 
 */


$collection = new Collection($cid);

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

if ($movieExists === false)
{
	$json = file_get_contents("http://www.omdbapi.com/?i=$imdbId&plot=short&r=json");
	
	$movieData = json_decode($json, true);
	
	$title = $movieData['Title'];
	$poster = $movieData['Poster'];
	$year = $movieData['Year'];

	$answer = $movie->createMovie($title, $imdbId, $poster, $year);
	
	if ($answer === true)
	{
		?>
	<script>
		$.get('<?php echo $path ?>collection/getLatestDataForMovie.php', {imdb: <?php echo $imdbId ?>}, function () {
		});
	</script>
		<?php
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
