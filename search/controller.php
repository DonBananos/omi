<?php

//Replaces all spaces with + (for search)
$title = preg_replace("/ /", '+', $_POST['search_field']);

//HTTP request to OMDb API with JSON answer
$json = file_get_contents("http://www.omdbapi.com/?s=$title&r=json&type=movie");

//JSON decode of answer
$data = json_decode($json, true);
