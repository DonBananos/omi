<?php

class Genre
{

	private $id;
	private $name;

	public function __construct()
	{
		
	}

	public function setValuesAccordingToId($id)
	{
		global $dbCon;
		$sql = "SELECT genre_id, genre_name FROM genre WHERE genre_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $name); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setName($name);
	}

	public function genreAlreadyInDb($name)
	{
		global $dbCon;
		$sql = "SELECT genre_id FROM genre WHERE genre_name = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if ($id > 0)
		{
			$this->setId($id);
			$this->setValuesAccordingToId($id);
			return true;
		}
		return false;
	}

	public function createGenre($name)
	{
		if (!$this->genreAlreadyInDb($name))
		{
			$this->setName($name);
			return $this->saveGenreToDb();
		}
		return true;
	}

	private function saveGenreToDb()
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO genre (genre_name) VALUES (?);";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('s', $this->name);

		//Execute
		$stmt->execute();

		//Get ID of user just saved
		$id = $stmt->insert_id;

		$stmt->close();
		if ($id > 0)
		{
			$this->setValuesAccordingToId($id);
			return true;
		}
		return $dbCon->error;
	}

	public function saveGenreToMovie($movieId)
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO genre_movie (genre_movie_genre_id, genre_movie_movie_id) VALUES (?, ?);";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('ii', $this->getId(), $movieId);

		//Execute
		$stmt->execute();

		//Get ID of user just saved
		$id = $stmt->insert_id;

		$stmt->close();
		if ($id > 0)
		{
			return true;
		}
		return $dbCon->error;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setName($name)
	{
		$this->name = $name;
	}

}
