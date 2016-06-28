<?php

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require_once './tagHandler.php';
require_once '../movie/movie.php';

$th = new TagHandler();
if (!isset($_GET['mi']) || !isset($_GET['ui']) || !isset($_GET['ti']))
{
	die("Missing arguments");
}
$movie_id = $_GET['mi'];
$user_id = $_GET['ui'];
$tag_id = $_GET['ti'];

$movie = new Movie($movie_id);
if ($movie->remove_tag_from_movie($tag_id, $user_id))
{
	$result['status'] = 1;
	$result['tag_id'] = $tag_id;
	$result['error'] = NULL;
}
else
{
	$result['status'] = 0;
	$result['tag_id'] = NULL;
	$result['error'] = "Tag not removed from movie..";
}
exit(json_encode($result));
