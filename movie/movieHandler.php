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
		$sql = "SELECT movie_id FROM movie ORDER BY movie_title ASC;";
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
	
	public function getAllPossibleSubs()
	{
		global $dbCon;
		$allSubs = array();
		$subtitle = array();
		$sql = "SELECT subtitles_language_id, subtitles_language_name, subtitles_language_code_2, COUNT(subtitles_language_id) AS uses FROM subtitles_language LEFT JOIN collection_movie_sub ON subtitles_language_id = collection_movie_subtitle_id GROUP BY subtitles_language_id ORDER BY uses DESC;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $name, $code, $uses); //Get ResultSet
		while ($stmt->fetch())
		{
			$subtitle['id'] = $id;
			$subtitle['name'] = $name;
			$subtitle['code'] = $code;
			array_push($allSubs, $subtitle);
		}
		$stmt->close();
		return $allSubs;
	}
}

