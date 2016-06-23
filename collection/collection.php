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
	private $image_name;

	function __construct($id = null)
	{
		if (!empty($id))
		{
			$this->id = $id;
			$this->setValuesAccordingToId();
		}
	}

	public function setValuesAccordingToId($id = null)
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
		$sql = "SELECT collection_name, collection_description, collection_user_id, collection_private, collection_created, collection_slug, collection_image_name FROM collection WHERE collection_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($name, $description, $userId, $private, $created, $slug, $image_name); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setName($name);
		$this->setDescription($description);
		$this->setUserId($userId);
		$this->setPrivacy($private);
		$this->setCreatedDatetime($created);
		$this->setSlug($slug);
		$this->set_image_name($image_name);
		return true;
	}

	public function createCollection($name, $description, $private, $userId)
	{
		$notInUse = $this->NameNotUsedByUser($name, $userId);
		if ($notInUse == false)
		{
			return 'You already have a collection with that name';
		}
		$this->name = $name;
		$this->description = $description;
		$this->privacy = $private;
		$this->userId = $userId;
		$this->slug = $this->createSlug($name);
		$answer = $this->saveCollectionInDB();
		if ($answer === true)
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
		$sql = "SELECT collection_movie_movie_id FROM collection_movie INNER JOIN movie ON collection_movie_movie_id = movie_id WHERE collection_movie_collection_id = ? ORDER BY movie_title ASC";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($movieId); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($moviesIds, $movieId);
		}
		$stmt->close();
		return $moviesIds;
	}

	public function updateDescription($text)
	{
		global $dbCon;

		$sql = "UPDATE collection SET collection_description = ? WHERE collection_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('si', $text, $this->id); //Bind parameters.
		$status = $stmt->execute(); //Execute
		if ($status)
		{
			$this->setDescription($text);
		}
		return $status;
	}

	public function get_all_movie_tags_in_collection()
	{
		$tags = array();

		global $dbCon;

		$sql = "SELECT tag.tag_id, tag_name FROM tag INNER JOIN movie_tag ON tag.tag_id = movie_tag.tag_id WHERE movie_id IN (SELECT collection_movie_movie_id FROM collection_movie INNER JOIN movie ON collection_movie_movie_id = movie_id WHERE collection_movie_collection_id = ?) AND user_id = ?;";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $this->id, $this->userId);
		$stmt->execute();
		$stmt->bind_result($tag_id, $tag_name);
		while ($stmt->fetch())
		{
			$tags[$tag_id] = $tag_name;
		}
		$stmt->close();
		return $tags;
	}

	public function save_collection_viewed_by_user($user_id)
	{
		global $dbCon;

		$sql = "INSERT INTO collection_user_view (collection_id, user_id) VALUES (?, ?);";
		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $this->id, $user_id);
		$stmt->execute();
		$id = $stmt->insert_id;
		$stmt->close();
		if ($id > 0)
		{
			return true;
		}
		return false;
	}

	private function get_header_images_from_movies_in_collection()
	{
		$header_images = array();
		$movie_ids = $this->getAllMoviesInCollection();
		if (count($movie_ids) < 1)
		{
			return false;
		}

		global $dbCon;
		$sql = "SELECT image_name, movie_id FROM movie_image WHERE movie_id IN (SELECT collection_movie_movie_id FROM collection_movie WHERE collection_movie_collection_id = ?);";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$stmt->bind_result($image_name, $movie_id);
		while ($stmt->fetch())
		{
			$movie_image = array();
			$movie_image['movie_id'] = $movie_id;
			$movie_image['image_name'] = $image_name;
			$header_images[] = $movie_image;
		}
		$stmt->close();
		return $header_images;
	}

	public function update_collection($name, $image, $privacy)
	{
		if ($name != $this->name)
		{
			if(!$this->update_collection_name($name))
			{
				return "Error updating collection name";
			}
		}
		if ($image != NULL)
		{
			if(!$this->save_collection_image($image))
			{
				return "Error updating collection image";
			}
		}
		if ($privacy != $this->privacy)
		{
			if(!$this->update_collection_privacy($privacy))
			{
				return "Error updating collection privacy";
			}
		}
		return TRUE;
	}

	private function update_collection_name($name)
	{
		if ($this->NameNotUsedByUser($name, $this->userId))
		{
			$slug = $this->createSlug($name);

			global $dbCon;

			$sql = "UPDATE collection SET collection_name = ?, collection_slug = ? WHERE collection_id = ?;";
			$stmt = $dbCon->prepare($sql); //Prepare Statement
			if ($stmt === false)
			{
				trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
			}
			$stmt->bind_param('ssi', $name, $slug, $this->id); //Bind parameters.
			$status = $stmt->execute(); //Execute
			if ($status)
			{
				$this->setName($name);
				$this->setSlug($slug);
			}
			return $status;
		}
	}

	private function save_collection_image($image)
	{
		global $dbCon;

		$sql = "UPDATE collection SET collection_image_name = ? WHERE collection_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('si', $image, $this->id); //Bind parameters.
		$status = $stmt->execute(); //Execute
		if ($status)
		{
			$this->set_image_name($image);
		}
		return $status;
	}

	private function update_collection_privacy($privacy)
	{
		global $dbCon;

		$sql = "UPDATE collection SET collection_private = ? WHERE collection_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $privacy, $this->id); //Bind parameters.
		$status = $stmt->execute(); //Execute
		if ($status)
		{
			$this->setPrivacy($privacy);
		}
		return $status;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return htmlspecialchars($this->name);
	}

	public function getDescription()
	{
		if (empty($this->description))
		{
			return "No description";
		}
		return htmlspecialchars($this->description);
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
		return htmlspecialchars($this->slug);
	}

	public function get_image_name()
	{
		if ($this->image_name == NULL)
		{
			$movies_in_collection = $this->getAllMoviesInCollection();
			if (count($movies_in_collection) > 0)
			{
				$image_names = array();
				$header_images = $this->get_header_images_from_movies_in_collection();
				foreach ($header_images as $header_image)
				{
					$image_name = $header_image['image_name'];
					$image_names[] = $image_name;
				}
				shuffle($image_names);
				if(count($image_names) > 0)
				{
					return get_image_path() . $image_names[rand(0, count($image_names) - 1)];
				}
			}
			return  get_image_path() . DEFAULT_COLLECTION_IMAGE;
		}
		return get_image_path() . $this->image_name;
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

	private function set_image_name($image_name)
	{
		$this->image_name = $image_name;
	}

}
