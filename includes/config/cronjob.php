<?php

class Cronjob
{
	private $dbCon;
	function __construct()
	{
		$dbCon = new mysqli('localhost', 'root', 'mik89jen', 'omi');
		if ($dbCon->connect_errno)
		{
			printf("Connect failed: %s\n", $dbCon->connect_error);
			exit();
		}
		$dbCon->set_charset("utf8");
	}

	public function updateMovies()
	{
		$moviesNeedingData = $this->getIdOfAllMoviesNeedingData();
	}

	private function getIdOfAllMoviesNeedingData()
	{
		$moviesNeedingData = array();
		$sql = "SELECT movie_id FROM movie WHERE movie_runtime IS NULL OR movie_plot IS NULL OR movie_language IS NULL;";
		$stmt = $this->dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->execute(); //Execute
		$stmt->bind_result($movie_id); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($moviesNeedingData, $movie_id);
		}
		$stmt->close();
		return $moviesNeedingData;
	}
}
