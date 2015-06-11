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

set_time_limit(300);

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
	$selectedMovie = new imdb($imdbNumberId);
	$title = $selectedMovie->title();
	$origTitle = $selectedMovie->orig_title();
	$plot = $selectedMovie->plotoutline();
	$runtime = $selectedMovie->runtime();
	$poster = $selectedMovie->photo();
	$thumbnail = $selectedMovie->photo(true);
	$language = $selectedMovie->language();
	$year = $selectedMovie->year();

	$answer = $movie->createMovie($title, $origTitle, $plot, $runtime, $imdbId, $poster, $thumbnail, $language, $year);
	if ($answer === true)
	{
		$star = new Person();
		foreach ($selectedMovie->cast() as $cast)
		{
			$movieStar = new imdb_person($cast['imdb']);
			$inDb = $star->checkIfPersonIsInDb($cast['imdb']);
			if ($inDb === false)
			{
				$person_bio = "";
				foreach ($movieStar->bio() as $bio)
				{
					$person_bio .= $bio['desc'];
				}
				if (isset($movieStar->born()['year']))
				{
					$born = $movieStar->born()['year'] . '-' . $movieStar->born()['mon'] . '-' . $movieStar->born()['day'];
					$bornPlace = $movieStar->born()['place'];
				}
				else
				{
					$born = null;
					$bornPlace = null;
				}
				if (isset($cast['thumb']))
				{
					$thumbnail = $cast['thumb'];
				}
				else
				{
					$thumbnail = null;
				}
				$star_id = $star->createPerson($cast['name'], $cast['imdb'], $person_bio, $born, $bornPlace, $thumbnail);
			}
			else
			{
				$star_id = $star->setValuesAccordingToId($inDb);
			}
			$star->savePersonToMovie($star_id, $movie->getId(), $cast['role'], 'Cast');
		}
		$theDirector = new Person();
		foreach ($selectedMovie->director() as $director)
		{
			$movieDirector = new imdb_person();
			$inDb = $theDirector->checkIfPersonIsInDb($director['imdb']);
			if ($inDb === false)
			{
				$person_bio = "";
				foreach ($movieDirector->bio() as $bio)
				{
					$person_bio .= $bio['desc'];
				}
				if (isset($movieDirector->born()['year']))
				{
					$born = $movieDirector->born()['year'] . '-' . $movieDirector->born()['mon'] . '-' . $movieDirector->born()['day'];
					$bornPlace = $movieDirector->born()['place'];
				}
				else
				{
					$born = null;
					$bornPlace = null;
				}
				$thumbnail = $movieDirector->photo();
				$director_id = $star->createPerson($director['name'], $director['imdb'], $person_bio, $born, $bornPlace, $thumbnail);
			}
			else
			{
				$director_id = $theDirector->setValuesAccordingToId($inDb);
			}
			$theDirector->savePersonToMovie($director_id, $movie->getId(), 'Director', 'Crew');
		}
		$genre = new Genre();
		foreach ($selectedMovie->genres() as $genreName)
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
