<?php

class Person
{
	private $id;
	private $name;
	private $slug;
	private $imdbId;
	private $bio;
	private $born;
	private $bornPlace;
	private $photo;
	
	function __construct($id = null)
	{
		if(!empty($id))
		{
			$this->setValuesAccordingToId($id);
		}
	}
	
	public function setValuesAccordingToId($id)
	{
		if (empty($id))
		{
			$id = $this->id;
			if ($id == null)
			{
				return false;
			}
		}
		global $dbCon;
		$sql = "SELECT person_id, person_name, person_slug, person_imdb_id, person_bio, person_born, person_born_place, person_photo FROM person WHERE person_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $name, $slug, $imdbId, $bio, $born, $bornPlace, $photo); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setName($name);
		$this->setSlug($slug);
		$this->setImdbId($imdbId);
		$this->setBio($bio);
		$this->setBorn($born);
		$this->setBornPlace($bornPlace);
		$this->setPhoto($photo);

		return $id;
	}
	
	public function checkIfPersonIsInDb($imdbId)
	{
		global $dbCon;
		$sql = "SELECT person_id FROM person WHERE person_imdb_id = ? LIMIT 1;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('s', $imdbId); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if ($id > 0)
		{
			return $id;
		}
		return false;
	}
	
	public function createPerson($name, $imdbId, $bio, $born, $bornPlace, $photo)
	{
		$this->setName($name);
		$this->setSlug($this->createSlug($name));
		$this->setImdbId($imdbId);
		$this->setBio($bio);
		$this->setBorn($born);
		$this->setBornPlace($bornPlace);
		$this->setPhoto($photo);
		
		return $this->savePersonToDb();
	}
	
	private function savePersonToDb()
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO person (person_name, person_slug, person_imdb_id, person_bio, person_born, person_born_place, person_photo) VALUES (?, ?, ?, ?, ?, ?, ?);";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('sssssss', $this->name, $this->slug, $this->imdbId, $this->bio, $this->born, $this->bornPlace, $this->photo);

		//Execute
		$stmt->execute();

		//Get ID of user just saved
		$id = $stmt->insert_id;

		$stmt->close();
		if ($id > 0)
		{
			$this->setValuesAccordingToId($id);
			return $id;
		}
		return $dbCon->error;
	}
	
	public function savePersonToMovie($id, $movie, $role, $team)
	{
		if($this->checkIfPersonIsAlreadyInMovieWithRole($movie, $role, $id, $team))
		{
			return;
		}
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO person_movie (person_movie_person_id, person_movie_movie_id, person_movie_role, person_movie_team) VALUES (?, ?, ?, ?)";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('iiss', $id, $movie, $role, $team);

		//Execute
		$stmt->execute();
		
		$stmt->close();
		if ($dbCon->error != NULL)
		{
			return $dbCon->error;
		}
		return true;
	}
	
	private function checkIfPersonIsAlreadyInMovieWithRole($movie, $role, $id, $team)
	{
		global $dbCon;
		$sql = "SELECT person_movie_id FROM person_movie WHERE person_movie_person_id = ? AND person_movie_movie_id = ? AND person_movie_role = ? AND person_movie_team = ?;";
		
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		//Bind parameters.
		$stmt->bind_param('iiss', $id, $movie, $role, $team);

		//Execute
		$stmt->execute();
		
		$stmt->bind_result($moviePersonId);
		$stmt->fetch();
		$stmt->close();
		if($moviePersonId > 0)
		{
			return true;
		}
		return false;
	}
	
	private function createSlug($name)
	{
		$slug = trim($name);
		$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
		$slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
		$slug = strtolower(trim($slug, '-'));
		$slug = preg_replace("/[\/_|+ -]+/", '-', $slug);

		return $slug;
	}
	
	public function getAllMovies()
	{
		$movieIds = array();
		$theMovie = array();
		global $dbCon;
		$sql = "SELECT person_movie_id, person_movie_movie_id, person_movie_role FROM person_movie WHERE person_movie_person_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->id);
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $movieId, $role); //Get ResultSet
		while ($stmt->fetch())
		{
			$theMovie['id'] = $id;
			$theMovie['movie_id'] = $movieId;
			$theMovie['role'] = $role;
			array_push($movieIds, $theMovie);
		}
		$stmt->close();
		return $movieIds;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function getImdbId()
	{
		return $this->imdbId;
	}

	public function getBio()
	{
		return $this->bio;
	}

	public function getBorn()
	{
		return $this->born;
	}

	public function getBornPlace()
	{
		return $this->bornPlace;
	}
	
	public function getPhoto()
	{
		return $this->photo;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setName($name)
	{
		$this->name = $name;
	}

	private function setSlug($slug)
	{
		$this->slug = $slug;
	}

	private function setImdbId($imdbId)
	{
		$this->imdbId = $imdbId;
	}

	private function setBio($bio)
	{
		$this->bio = $bio;
	}

	private function setBorn($born)
	{
		$this->born = $born;
	}

	private function setBornPlace($bornPlace)
	{
		$this->born_place = $bornPlace;
	}

	private function setPhoto($photo)
	{
		$this->photo = $photo;
	}
}
