<?php

require_once '../config/config.php';
require_once '../config/database.php';

require_once '../../movie/movieHandler.php';
require_once '../../movie/movie.php';

$mh = new MovieHandler();
$all_movie_ids = $mh->getAllMovieIds();

foreach($all_movie_ids as $movie_id)
{
	$movie = new Movie($movie_id);
	
	$movie->get_current_imdb_rating_from_api();
}
echo 'Done...';


