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
	$json = file_get_contents("http://www.omdbapi.com/?i=$imdbId&plot=full&r=json");

	//JSON decode of answer
	$data = json_decode($json, true);
	$selectedMovie = new imdb($imdbNumberId);
	$title = $data['Title'];
	$plot = $data['Plot'];
	$runtime = $selectedMovie->runtime();
	$poster = $data['Poster'];
	$thumbnail = $poster;
	$language = $selectedMovie->language();
	$year = $selectedMovie->year();

	$answer = $movie->createMovie($title, $plot, $runtime, $imdbId, $poster, $thumbnail, $language, $year);

	if ($answer === true)
	{
		$fmt = array();
		$foreignTitles = array();
		$languageCodes = array_keys($countryLanguages);
		foreach ($selectedMovie->alsoknow() as $titles)
		{
			$country = $titles['country'];
			$title = $titles['title'];
			$foreignTitles[$country] = $title;
		}
		foreach ($foreignTitles as $c => $t)
		{
			$code = array_search($c, $countryLanguages);
			if (!empty($code))
			{
				$fmt[$code] = $t;
				$movie->saveAlternativeTitleToDb($t, $code);
			}
		}
		$star = new Person();
		foreach ($selectedMovie->cast() as $cast)
		{
			$movieStar = new imdb_person($cast['imdb']);
			$inDb = $star->checkIfPersonIsInDb($cast['imdb']);
			if ($inDb === false)
			{
				if (isset($cast['thumb']))
				{
					$thumbnail = $cast['thumb'];
				}
				else
				{
					$thumbnail = null;
				}
				$star_id = $star->createPerson($cast['name'], $cast['imdb'], $thumbnail);
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
			$movieDirector = new imdb_person($director['imdb']);
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
