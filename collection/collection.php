<?php

/*
 * In order to get a collection object, simply create an object with the ID as
 * parameter. Alternatively, an object can be created without a parameter, and
 * the object can be fully created with the setValuesAccordingToId with the 
 * parameter ID.
 */

class Collection
{
	private $id;
	private $name;
	private $description;
	private $userId;
	private $privacy;
	private $createdDatetime;
	private $slug;
	
	function __construct($id = null)
	{
		if(!empty($id))
		{
			$this->id = $id;
			$this->setValuesAccordingToId();
		}
	}
	
	public function setValuesAccordingToId($id = null)
	{
		if(empty($id))
		{
			$id = $this->id;
			if($id == null)
			{
				return false;
			}
		}
		global $dbCon;
		$sql = "SELECT collection_name, collection_description, collection_user_id, collection_private, collection_created, collection_slug FROM collection WHERE collection_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($name, $description, $userId, $private, $created, $slug); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setName($name);
		$this->setDescription($description);
		$this->setUserId($userId);
		$this->setPrivacy($private);
		$this->setCreatedDatetime($created);
		$this->setSlug($slug);
		return true;
	}
	
	public function createCollection($name, $description, $private, $userId)
	{
		$notInUse = $this->NameNotUsedByUser($name, $userId);
		if($notInUse == false)
		{
			return 'You already have a collection with that name';
		}
		$this->name = $name;
		$this->description = $description;
		$this->privacy = $private;
		$this->userId = $userId;
		$this->slug = $this->createSlug($name);
		$answer = $this->saveCollectionInDB();
		if($answer === true)
		{
			return $answer;
		}
	}
	
	private function saveCollectionInDB()
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO collection (collection_name, collection_description, collection_user_id, collection_private, collection_created, collection_slug) VALUES (?, ?, ?, ?, NOW(), ?)";
		
		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('ssiis', $this->name, $this->description, $this->userId, $this->privacy, $this->slug);

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
	
	private function NameNotUsedByUser($name, $userId)
	{
		global $dbCon;
		$sql = "SELECT collection_id FROM collection WHERE collection_name = ? AND collection_user_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('si', $name, $userId); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if ($id != null)
		{
			return false;
		}return true;
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
	
	public function getAllMoviesInCollection()
	{
		global $dbCon;
		$moviesIds = array();
		$sql = "SELECT collection_movie_movie_id FROM collection_movie WHERE collection_movie_collection_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($movieId); //Get ResultSet
		while($stmt->fetch())
		{
			array_push($moviesIds, $movieId);
		}
		$stmt->close();
		return $moviesIds;
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getPrivacy()
	{
		return $this->privacy;
	}

	public function getCreatedDatetime()
	{
		return $this->createdDatetime;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setName($name)
	{
		$this->name = $name;
	}

	private function setDescription($description)
	{
		$this->description = $description;
	}

	private function setUserId($userId)
	{
		$this->userId = $userId;
	}

	private function setPrivacy($privacy)
	{
		$this->privacy = $privacy;
	}

	private function setCreatedDatetime($createdDatetime)
	{
		$this->createdDatetime = $createdDatetime;
	}

	private function setSlug($slug)
	{
		$this->slug = $slug;
	}


}

