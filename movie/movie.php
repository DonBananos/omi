<?php

/*
 * Movie object for the OMI (Online Movie Index) Project.
 * Project: http://www.github.com/DonBananos/omi
 * 
 * Author: Mike Jensen <mj@mjsolutions.dk>
 * 
 * If developing in this file, please make describing comments.
 * If creating a function, please create a comment area in top describing the function
 */

class Movie
{

	private $id;
	private $title;
	private $origTitle; //The original title
	private $slug; //The url prepared name for the movie
	private $plot;
	private $runtime; //In minutes, as received from OMDb API
	private $posterUrl;
	private $posterUrlThumb;
	private $language; //Array of languages
	private $year;
	private $imdbId;
	private $imdbLink; //Set by using the imdb_id and a configured url from the config file

	function __construct($id = null)
	{
		if (!empty($id))
		{
			$this->id = $id;
			$this->setValuesWithId($id);
		}
	}

	public function setValuesWithId($id = null)
	{
		/*
		 * Function that receives the movie ID and sets the entire object from it.
		 * Used when receiving an array of movie to display, or by other functions.
		 */
		if (empty($id))
		{
			$id = $this->id;
			if ($id == null)
			{
				return false;
			}
		}
		global $dbCon;
		$sql = "SELECT movie_title, movie_orig_title, movie_slug, movie_plot, movie_runtime, movie_imdb_id, movie_poster, movie_poster_thumb, movie_language, movie_year FROM movie WHERE movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($title, $origTitle, $slug, $plot, $runtime, $imdbId, $posterUrl, $posterUrlThumb, $language, $year); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setTitle($title);
		$this->setOrigTitle($origTitle);
		$this->setSlug($slug);
		$this->setPlot($plot);
		$this->setRuntime($runtime);
		$this->setImdbId($imdbId);
		$this->setPosterUrl($posterUrl);
		$this->setPosterUrlThumb($posterUrlThumb);
		$this->setLanguage($language);
		$this->setImdbLink($imdbId);
		$this->setYear($year);

		return true;
	}

	public function createMovie($title, $imdbId, $posterUrl, $year)
	{
		$this->setTitle($title);
		$this->setSlug($this->createSlug($this->title));
		$this->setImdbId($imdbId);
		$this->setPosterUrl($posterUrl);
		$this->setYear($year);

		return $this->saveMovieInDb();
	}

	private function saveMovieInDb()
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO movie (movie_title, movie_slug, movie_imdb_id, movie_poster, movie_year) VALUES (?, ?, ?, ?, ?);";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('sssss', $this->title, $this->slug, $this->imdbId, $this->posterUrl, $this->year);

		//Execute
		$stmt->execute();

		//Get ID of user just saved
		$id = $stmt->insert_id;

		$stmt->close();
		if ($id > 0)
		{
			$this->setValuesWithId($id);
			return true;
		}
		return $dbCon->error;
	}

	public function saveAlternativeTitleToDb($title, $languageCode)
	{
		if ($this->checkIfAlternativeTitleIsInDb($title, $languageCode))
		{
			return true;
		}
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO movie_aka_title (movie_aka_title_language_code, movie_aka_title_movie_id, movie_aka_title_title) VALUES (?, ?, ?);";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('sis', $languageCode, $this->id, $title);

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

	private function checkIfAlternativeTitleIsInDb($title, $languageCode)
	{
		global $dbCon;
		//Create SQL Query
		$sql = "SELECT movie_aka_title_id FROM movie_aka_title WHERE movie_aka_title_language_code = ? AND movie_aka_title_movie_id = ? AND movie_aka_title_title = ?;";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('sis', $languageCode, $this->id, $title);

		//Execute
		$stmt->execute();

		//Get ID of user just saved
		$stmt->bind_result($matid);
		$stmt->fetch();

		$stmt->close();
		if ($matid > 0)
		{
			return true;
		}
		return false;
	}

	public function updateMovieWithFullData($plot, $runtime, $posterUrlThumb, $language)
	{
		$this->setPlot($plot);
		$this->setRuntime($runtime);
		$this->setPosterUrlThumb($posterUrlThumb);
		$this->setLanguage($language);

		$this->insertUpdateInDatabase();
	}

	private function insertUpdateInDatabase()
	{
		global $dbCon;
		$sql = "UPDATE movie SET movie_plot = ?, movie_runtime = ?, movie_poster_thumb = ?, movie_language = ? WHERE movie_id = ?;";
		
		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('sissi', $this->plot, $this->runtime, $this->posterUrlThumb, $this->language, $this->id);

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

	private function createSlug($title)
	{
		$slug = trim($title);
		$slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
		$slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
		$slug = strtolower(trim($slug, '-'));
		$slug = preg_replace("/[\/_|+ -]+/", '-', $slug);

		return $slug;
	}

	public function saveMovieToCollection($collectionId)
	{
		if ($this->checkIfMovieIsInCollectionAlready($collectionId))
		{
			return true;
		}
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO collection_movie (collection_movie_collection_id, collection_movie_movie_id, collection_movie_added) VALUES (?, ?, NOW())";

		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('ii', $collectionId, $this->id);

		//Execute
		$stmt->execute();

		$stmt->close();
		if ($dbCon->error != NULL)
		{
			return $dbCon->error;
		}
		return true;
	}

	public function removeMovieFromCollection($collectionId)
	{
		if ($this->checkIfMovieIsInCollectionAlready($collectionId))
		{
			global $dbCon;
			//Create SQL Query
			$sql = "DELETE FROM collection_movie WHERE collection_movie_collection_id = ? AND collection_movie_movie_id = ?";

			//Prepare Statement
			$stmt = $dbCon->prepare($sql);
			if ($stmt === false)
			{
				trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
			}

			//Bind parameters.
			$stmt->bind_param('ii', $collectionId, $this->id);

			//Execute
			$stmt->execute();

			$stmt->close();
			if ($dbCon->error != NULL)
			{
				return $dbCon->error;
			}
			return true;
		}
	}

	public function getFullCast()
	{
		global $dbCon;
		$castList = array();
		$sql = "SELECT person_movie_person_id, person_movie_role FROM person_movie WHERE person_movie_movie_id = ? AND person_movie_team = 'Cast';";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->getId()); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($person_id, $role); //Get ResultSet
		while ($stmt->fetch())
		{
			$castList[$person_id] = $role;
		}
		$stmt->close();
		return $castList;
	}

	public function getDirectors()
	{
		global $dbCon;
		$directorList = array();
		$sql = "SELECT person_movie_person_id FROM person_movie WHERE person_movie_movie_id = ? AND person_movie_role = 'Director';";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->getId()); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($person_id); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($directorList, $person_id);
		}
		$stmt->close();
		return $directorList;
	}

	public function checkIfMovieIsInCollectionAlready($collectionId)
	{
		global $dbCon;
		$sql = "SELECT collection_movie_movie_id FROM collection_movie WHERE collection_movie_collection_id = ? AND collection_movie_movie_id = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $collectionId, $this->getId()); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if ($id > 0)
		{
			return true;
		}return false;
	}

	public function getAllGenresForMovie()
	{
		global $dbCon;
		$genreIds = array();
		$sql = "SELECT genre_movie_genre_id FROM genre_movie WHERE genre_movie_movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->getId()); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($genreId); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($genreIds, $genreId);
		}
		$stmt->close();
		return $genreIds;
	}

	public function getAllCollectionIdsForUserInWhichTheMovieIs($user_id)
	{
		global $dbCon;
		$collectionIds = array();
		$sql = "SELECT collection_movie_collection_id, collection_movie_quality FROM collection_movie INNER JOIN collection ON collection_movie_collection_id = collection_id WHERE collection_user_id = ? AND collection_movie_movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $user_id, $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($collectionId, $quality); //Get ResultSet
		while ($stmt->fetch())
		{
			$collectionIds[$collectionId] = $quality;
		}
		$stmt->close();
		return $collectionIds;
	}

	public function getAllCollectionIdsForUserInWhichTheMovieIsNot($user_id)
	{
		global $dbCon;
		$collectionIds = array();
		$sql = "SELECT collection_id FROM collection WHERE collection_user_id = ? AND collection_id NOT IN (SELECT collection_movie_collection_id FROM collection_movie WHERE collection_movie_movie_id = ?)";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $user_id, $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($collectionId); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($collectionIds, $collectionId);
		}
		$stmt->close();
		return $collectionIds;
	}

	public function getMovieInCollectionQuality($collection)
	{
		global $dbCon;
		$sql = "SELECT collection_movie_quality FROM collection_movie WHERE collection_movie_collection_id = ? AND collection_movie_movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $collection, $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($quality); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		return $quality;
	}

	public function updateQualityInCollection($quality, $collectionId)
	{
		global $dbCon;
		$collectionIds = array();
		$sql = "UPDATE collection_movie SET collection_movie_quality = ? WHERE collection_movie_collection_id = ? AND collection_movie_movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('sii', $quality, $collectionId, $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->close();
	}

	public function getAllSubsForMovieInCollection($collectionId)
	{
		global $dbCon;
		$subs = array();
		$sql = "SELECT subtitles_language_code_2 FROM collection_movie_sub INNER JOIN subtitles_language ON collection_movie_subtitle_id = subtitles_language_id WHERE collection_movie_collection_id = ? AND collection_movie_movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('ii', $collectionId, $this->id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($subCode); //Get ResultSet
		while ($stmt->fetch())
		{
			array_push($subs, $subCode);
		}
		$stmt->close();
		return $subs;
	}

	public function saveSubtitleForMovieInCollection($subtitleId, $collectionId)
	{
		global $dbCon;
		$sql = "INSERT INTO collection_movie_sub (collection_movie_collection_id, collection_movie_movie_id, collection_movie_subtitle_id) VALUES (?, ?, ?)";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('iii', $collectionId, $this->id, $subtitleId); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->close();
	}

	public function checkIfMovieAlreadyExists($imdbId)
	{
		global $dbCon;
		$sql = "SELECT movie_id FROM movie WHERE movie_imdb_id = ? LIMIT 1;";
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
		}return false;
	}

	public function getLocalTitleIfExists()
	{
		//First, we get the browser prefered languages
		$lang_str = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '<br><br>';
		//Then we only select the first available, since titles should only be
		//in the users native language, it not the original.
		$prefered_lang = substr($lang_str, 0, 5);
		//Get all alternative titles for movie
		$alternativeTitles = $this->getArrayOfAllAlternativeTitles();
		if (isset($alternativeTitles[$prefered_lang]))
		{
			return $alternativeTitles[$prefered_lang];
		}
		elseif (isset($alternativeTitles[substr($prefered_lang, 0, 2)]))
		{
			return $alternativeTitles[substr($prefered_lang, 0, 2)];
		}
		return false;
	}

	private function getArrayOfAllAlternativeTitles()
	{
		$alternativeTitles = array();
		global $dbCon;
		$sql = "SELECT movie_aka_title_language_code, movie_aka_title_title FROM movie_aka_title WHERE movie_aka_title_movie_id = ?;";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $this->id);
		$stmt->execute();
		$stmt->bind_result($languageCode, $title);
		while ($stmt->fetch())
		{
			$alternativeTitles[$languageCode] = $title;
		}
		$stmt->close();
		if (count($alternativeTitles) > 0)
		{
			return $alternativeTitles;
		}
		return false;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getOrigTitle()
	{
		return $this->origTitle;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function getPlot()
	{
		return $this->plot;
	}

	public function getRuntime()
	{
		return $this->runtime;
	}

	public function getPosterUrl()
	{
		return $this->posterUrl;
	}

	public function getPosterUrlThumb()
	{
		return $this->posterUrlThumb;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function getYear()
	{
		return $this->year;
	}

	public function getImdbId()
	{
		return $this->imdbId;
	}

	public function getImdbLink()
	{
		return $this->imdbLink;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function setOrigTitle($origTitle)
	{
		$this->origTitle = $origTitle;
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	public function setPlot($plot)
	{
		$this->plot = $plot;
	}

	public function setRuntime($runtime)
	{
		$this->runtime = $runtime;
	}

	public function setPosterUrl($posterUrl)
	{
		$this->posterUrl = $posterUrl;
	}

	public function setPosterUrlThumb($posterUrlThumb)
	{
		$this->posterUrlThumb = $posterUrlThumb;
	}

	public function setLanguage($language)
	{
		$this->language = $language;
	}

	public function setYear($year)
	{
		$this->year = $year;
	}

	public function setImdbId($imdbId)
	{
		$this->imdbId = $imdbId;
	}

	private function setImdbLink($imdbId)
	{
		$this->imdbLink = 'http://www.imdb.com/title/' . $imdbId . '/';
	}

}
