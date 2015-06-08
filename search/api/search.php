<?php
/*
 * This file is the intern search api, which receives a search String, and 
 * returns a series of json objects which fits most the search String.
 */

require '../../includes/config/config.php';
require '../../includes/config/database.php';

$searchString = '%'.$_GET['s'].'%';

$movie = array();
$result = array();

global $dbCon;
$sql = "SELECT movie_title, movie_imdb_id, movie_year FROM movie WHERE movie_title LIKE ?;";
$stmt = $dbCon->prepare($sql); //Prepare Statement
if ($stmt === false)
{
	trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
}
$stmt->bind_param('s', $searchString); //Bind parameters.
$stmt->execute(); //Execute
$stmt->bind_result($title, $imdbId, $year); //Get ResultSet
while($stmt->fetch())
{
	$movie['Title'] = $title;
	$movie['Year'] = $year;
	$movie['imdbID'] = $imdbId;
	$movie['Type'] = 'movie';
	
	array_push($result, $movie);
}
$stmt->close();

$searchResult = array();
$searchResult['Search'] = $result;

return json_encode($searchResult);

