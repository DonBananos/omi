<?php

class MovieHandler
{
	function __construct()
	{
	
	}
	
	public function getAllMovieIds()
	{
		global $dbCon;
		$allMovies = array();
		$sql = "SELECT movie_id FROM movie;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->execute(); //Execute
		$stmt->bind_result($movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($allMovies, $movie_id);
		}
		$stmt->close();
		return $allMovies;
	}
}

