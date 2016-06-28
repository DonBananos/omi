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

	public function getAllUsedSubs($userId)
	{
		$usedSubIds = array();
		$usedSubs = array();
		global $dbCon;
		$sql = "SELECT DISTINCT(collection_movie_subtitle_id) FROM collection_movie_sub INNER JOIN collection ON collection_movie_collection_id = collection_id WHERE collection_user_id = ?;";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $userId);
		$stmt->execute();
		$stmt->bind_result($subId);
		while ($stmt->fetch())
		{
			array_push($usedSubIds, $subId);
		}
		$stmt->close();
		foreach ($usedSubIds as $subId)
		{
			$subtitle = $this->turnSubIdToSub($subId);
			array_push($usedSubs, $subtitle);
		}
		return $usedSubs;
	}

	private function turnSubIdToSub($subId)
	{
		$subtitle = array();
		global $dbCon;
		$sql = "SELECT subtitles_language_id, subtitles_language_name, subtitles_language_code_2 FROM subtitles_language WHERE subtitles_language_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $subId);
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $name, $code); //Get ResultSet
		$stmt->fetch();
		$subtitle['id'] = $id;
		$subtitle['name'] = $name;
		$subtitle['code'] = $code;
		$stmt->close();
		return $subtitle;
	}

	public function getAllPossibleSubs()
	{
		global $dbCon;
		$allSubs = array();
		$subtitle = array();
		$sql = "SELECT subtitles_language_id, subtitles_language_name, subtitles_language_code_2 FROM subtitles_language;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $name, $code); //Get ResultSet
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

	public function get_latest_added_movies($limit = 4)
	{
		$movie_ids = array();
		
		global $dbCon;
		
		$sql = "SELECT movie_id FROM movie ORDER BY movie_id DESC LIMIT ?;";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $limit);
		$stmt->execute();
		$stmt->bind_result($movie_id);
		while ($stmt->fetch())
		{
			$movie_ids[] = $movie_id;
		}
		$stmt->close();
		return $movie_ids;
	}
	
	public function get_most_popular_movies($limit = 6)
	{
		$movie_ids = array();
		global $dbCon;

		$sql = "SELECT COUNT(id), movie_id FROM movie_user_view GROUP BY movie_id ORDER BY COUNT(id) DESC LIMIT ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $limit); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($count, $movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			$movie_ids[] = $movie_id;
		}
		$stmt->close();
		return $movie_ids;
	}
	
	public function get_newest_added_movies($limit = 6)
	{
		$movie_ids = array();
		global $dbCon;

		$sql = "SELECT movie_id FROM movie ORDER BY movie_added DESC LIMIT ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $limit); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			$movie_ids[] = $movie_id;
		}
		$stmt->close();
		return $movie_ids;
	}
	
	public function get_movies_from_search_string($string, $start = 0, $limit = 10)
	{
		$string = "%".$string."%";
		$movie_ids = array();
		global $dbCon;
		
		$sql = "SELECT DISTINCT(movie_id) FROM movie INNER JOIN movie_aka_title ON movie_id = movie_aka_title_movie_id WHERE movie_title LIKE ? OR movie_orig_title LIKE ? OR movie_aka_title_title LIKE ? LIMIT ?,?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('sssii', $string, $string, $string, $start, $limit); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			$movie_ids[] = $movie_id;
		}
		$stmt->close();
		return $movie_ids;
	}
	
	public function get_highest_imdb_rated_movies($limit = 6)
	{
		$movie_ids = array();
		global $dbCon;

		$sql = "SELECT movie_id FROM movie_imdb_rating ORDER BY imdb_rating DESC LIMIT ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $limit); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			$movie_ids[] = $movie_id;
		}
		$stmt->close();
		return $movie_ids;
	}
}
