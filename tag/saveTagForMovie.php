<?php

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require_once './tagHandler.php';
require_once '../movie/movie.php';

$th = new TagHandler();
if(!isset($_GET['mi']) || !isset($_GET['ui']))
{
		die("Missing arguments");
}
$movie_id = $_GET['mi'];
$user_id = $_GET['ui'];

if(!isset($_GET['ti']))
{
	if(!isset($_GET['tn']))
	{
		die("Missing arguments");
	}
	$tag_name = $_GET['tn'];
	$tag_id = $th->create_new_tag($tag_name, $user_id);
}
elseif(!isset($_GET['tn']))
{
	if(!isset($_GET['ti']))
	{
		die("Missing arguments");
	}
	$tag_id = $_GET['ti'];
}
if(isset($_GET['ci']))
{
	$collection_id = $_GET['ci'];
}
else
{
	$collection_id = NULL;
}

$movie = new Movie($movie_id);
$result = array();
if ($movie->save_tag_for_movie($tag_id, $user_id, $collection_id))
{
	$result['status'] = 1;
	$result['tag_id'] = $tag_id;
	$result['error'] = NULL;
}
else
{
	$result['status'] = 0;
	$result['tag_id'] = NULL;
	$result['error'] = "Tag not saved for movie..";
}
exit(json_encode($result));