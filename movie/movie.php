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
	private $slug; //The url prepared name for the movie
	private $runtime; //In minutes, as received from OMDb API
	private $release; //Date
	private $language; //Array of languages
	private $plot;
	private $posterUrl;
	private $imdbId;
	private $imdbLink; //Set by using the imdb_id and a configured url from the config file
	
	function __construct($id = null)
	{
		if(!empty($id))
		{
			$this->id = $id;
			$this->setValuesWithId();
		}
	}
	
	public function setValuesWithId($id = null)
	{
		/*
		 * Function that receives the movie ID and sets the entire object from it.
		 * Used when receiving an array of movie to display, or by other functions.
		 */
		if(empty($id))
		{
			$id = $this->id;
			if($id == null)
			{
				return false;
			}
		}
		global $dbCon;
		$sql = "SELECT movie_title, movie_slug, movie_plot, movie_release, movie_runtime, movie_imdb_id, movie_poster, movie_language FROM movie WHERE movie_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($title, $slug, $plot, $release, $runtime, $imdbId, $posterUrl, $language); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->setId($id);
		$this->setTitle($title);
		$this->setSlug($slug);
		$this->setPlot($plot);
		$this->setRelease($release);
		$this->setRuntime($runtime);
		$this->setImdbId($imdbId);
		$this->setImdbLink($imdbId);
		$this->setPosterUrl($posterUrl);
		$this->setLanguage($language);
		
		return true;
	}
	
	public function createMovie($title, $plot, $release, $runtime, $imdbId, $posterUrl, $language)
	{
		$this->setTitle($title);
		$this->setSlug($this->createSlug($this->title));
		$this->setPlot($plot);
		$this->setRuntime($runtime);
		$this->setRelease($release);
		$this->setImdbId($imdbId);
		$this->setImdbLink($imdbId);
		$this->setPosterUrl($posterUrl);
		$this->setLanguage($language);
		
		return $this->saveMovieInDb();
	}
	
	private function saveMovieInDb()
	{
		global $dbCon;
		//Create SQL Query
		$sql = "INSERT INTO movie (movie_title, movie_slug, movie_plot, movie_release, movie_runtime, movie_imdb_id, movie_poster, movie_language) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
		
		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}

		//Bind parameters.
		$stmt->bind_param('ssssssss', $this->title, $this->slug, $this->plot, $this->release, $this->runtime, $this->imdbId, $this->posterUrl, $this->language);

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
	
	public function getId()
	{
		return $this->id;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getSlug()
	{
		return $this->slug;
	}

	public function getRuntime()
	{
		return $this->runtime;
	}

	public function getRelease()
	{
		return $this->release;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function getPlot()
	{
		return $this->plot;
	}

	public function getPosterUrl()
	{
		return $this->posterUrl;
	}

	public function getImdbId()
	{
		return $this->imdbId;
	}

	public function getImdbLink()
	{
		return $this->imdbLink;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setTitle($title)
	{
		$this->title = $title;
	}

	private function setSlug($slug)
	{
		$this->slug = $slug;
	}

	private function setRuntime($runtime)
	{
		$this->runtime = $runtime;
	}

	private function setRelease($release)
	{
		$this->release = $release;
	}

	private function setLanguage($language)
	{
		$this->language = $language;
	}

	private function setPlot($plot)
	{
		$this->plot = $plot;
	}

	private function setPosterUrl($posterUrl)
	{
		$this->posterUrl = $posterUrl;
	}

	private function setImdbId($imdbId)
	{
		$this->imdbId = $imdbId;
	}

	private function setImdbLink($imdbId)
	{
		$this->imdbLink = 'http://www.imdb.com/title/'.$imdbId.'/';
	}


}