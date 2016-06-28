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

$movie = new Movie();

set_time_limit(900);

$imdbId = $_GET['imdb'];
$id = $movie->checkIfMovieAlreadyExists($imdbId);
if($id === false)
{
	echo 'Movie is not in DB!';
	die();
}

$movie->setValuesWithId($id);

$imdbNumber = substr($imdbId, -7);
$imdb = new imdb($imdbNumber);

$runtime = $imdb->runtime();
$plot = $imdb->plotoutline();
$thumbnail = $imdb->photo();
$language = $imdb->language();

$movie->updateMovieWithFullData($plot, $runtime, $thumbnail, $language);

$fmt = array();
$foreignTitles = array();
$languageCodes = array_keys($countryLanguages);
foreach ($imdb->alsoknow() as $titles)
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

/*
 * Insert Release info, in table movie_release
 * $imdb->releaseInfo()
 * Same procedure as with the foreign titles above.
 */

$star = new Person();
foreach ($imdb->cast() as $cast)
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
foreach ($imdb->director() as $director)
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
foreach ($imdb->genres() as $genreName)
{
	$answer = $genre->createGenre(trim($genreName));
	if ($answer === true)
	{
		$answer = $genre->saveGenreToMovie($movie->getId());
	}
}
?>