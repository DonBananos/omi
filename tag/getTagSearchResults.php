<?php
session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require_once './tagHandler.php';

$tag_name = $_GET['tn'];
$limit = 5;

$th = new TagHandler();
$tags = $th->search_for_tag($tag_name, $limit);
exit(json_encode($tags));
