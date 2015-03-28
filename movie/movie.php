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
	public $id;
	public $title;
	public $slug; //The url prepared name for the movie
	public $runtime; //In minutes, as received from OMDb API
	public $year;
	public $release;
	public $language; //Array of languages
	public $plot;
	public $poster_url;
	public $imdb_id;
	public $imdb_rating;
	public $imdb_link;
	
	function __construct($id = null)
	{
		if(!empty($id))
		{
			$this->id = $id;
			$this->set_values_with_id();
		}
	}
	
	private function set_values_with_id()
	{
		/*
		 * Function that receives the movie ID and sets the entire object from it.
		 * Used when receiving an array of movie to display, or by other functions.
		 */
		
		$sql = "SELECT * FROM movie WHERE movie_id = '$this->id';";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$this->title = $row['movie_title'];
		$this->slug = $row['movie_slug'];
		$this->runtime = $row['movie_runtime'];
		$this->year = $row['movie_year'];
		$this->release = $row['movie_release'];
		$this->language = $row['movie_language'];
		$this->plot = $row['movie_plot'];
		$this->poster_url = $row['movie_poster_url'];
		$this->imdb_id = $row['movie_imdb_id'];
		$this->imdb_link = $row['movie_imdb_link'];
		$this->imdb_rating = $row['movie_imdb_rating'];
	}
	
	public function set_values_with_slug($unesc_slug)
	{
		/*
		 * Function that receives an URL prepared title and sets the entire object from it.
		 * Used when visiting a movie detail page
		 * http://www.domain.com/movie/the-movie-title/
		 */
		
		$slug = mysql_real_escape_string($unesc_slug);
		$sql = "SELECT movie_id FROM movie WHERE movie_slug = '$slug';";
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		$this->id = $row['movie_id'];
		$this->set_values_with_id();
	}
}