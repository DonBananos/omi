<?php

class Movie
{
	public $id;
	public $title;
	public $slug;
	public $runtime;
	public $year;
	public $release;
	public $language;
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
}