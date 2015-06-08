<?php
$searchString = $_GET['searchString'];
//$collectionId = $_GET['cid'];

/*
 * Check if the cache folder exists in the imdbphp api.
 * if it doesn't - then create it!
 */
if (!file_exists('../includes/api/imdbphp/cache'))
{
	mkdir('../includes/api/imdbphp/cache', 0777, true);
}

require '../includes/api/imdbphp/imdb.class.php';
require '../includes/api/imdbphp/imdbsearch.class.php';
require '../includes/api/imdbphp/imdb_person.class.php';
$search = new imdbsearch();
$results = $search->search($searchString, [imdbsearch::MOVIE], 1);
?>
<div style="max-height: 68vh; overflow: auto" class="movieSearchResultAreaHold">
	<?php
	foreach ($results as $movie)
	{
		?>
		<div class="col-lg-6 movie-box"style="margin-bottom: 40px;">
			<img class="thumbnail img-responsive" src="<?php echo $movie->photo(true) ?>">
			<h4>
				<?php
				echo $movie->title();
				?> 
				(
				<?php
				echo $movie->year();
				?>
				)<p>
					<?php
					/*
					 * imdb Object ( 
					 * [akas:protected] => Array ( ) 
					 * [awards:protected] => Array ( ) 
					 * [countries:protected] => Array ( ) 
					 * [castlist:protected] => Array ( ) 
					 * [crazy_credits:protected] => Array ( ) 
					 * [credits_cast:protected] => Array ( ) 
					 * [credits_composer:protected] => Array ( ) 
					 * [credits_director:protected] => Array ( ) 
					 * [credits_producer:protected] => Array ( )
					 * [credits_writing:protected] => Array ( ) 
					 * [extreviews:protected] => Array ( ) 
					 * [goofs:protected] => Array ( ) 
					 * [langs:protected] => Array ( ) 
					 * [langs_full:protected] => Array ( ) 
					 * [aspectratio:protected] => 
					 * [main_comment:protected] => 
					 * [main_genre:protected] => 
					 * [main_keywords:protected] => Array ( ) 
					 * [all_keywords:protected] => Array ( ) 
					 * [main_language:protected] => 
					 * [main_photo:protected] => http://ia.media-imdb.com/images/M/MV5BMTQ2MzYwMzk5Ml5BMl5BanBnXkFtZTcwOTI4NzUyMw@@._V1 
					 * [main_thumb:protected] => http://ia.media-imdb.com/images/M/MV5BMTQ2MzYwMzk5Ml5BMl5BanBnXkFtZTcwOTI4NzUyMw@@._V1_SX214_AL_.jpg 
					 * [main_pictures:protected] => Array ( ) 
					 * [main_plotoutline:protected] => 
					 * [main_rating:protected] => -1 
					 * [main_runtime:protected] => 
					 * [main_movietype:protected] => Movie 
					 * [main_title:protected] => Anchorman 
					 * [original_title:protected] => 
					 * [main_votes:protected] => -1 
					 * [main_year:protected] => 2004 
					 * [main_endyear:protected] => -1 
					 * [main_yearspan:protected] => Array ( ) 
					 * [main_creator:protected] => Array ( ) 
					 * [main_tagline:protected] => 
					 * [main_storyline:protected] => 
					 * [main_prodnotes:protected] => Array ( ) 
					 * [main_movietypes:protected] => Array ( ) 
					 * [main_top250:protected] => -1 
					 * [moviecolors:protected] => Array ( ) 
					 * [movieconnections:protected] => Array ( ) 
					 * [moviegenres:protected] => Array ( ) 
					 * [moviequotes:protected] => Array ( ) 
					 * [movierecommendations:protected] => Array ( ) 
					 * [movieruntimes:protected] => Array ( ) 
					 * [mpaas:protected] => Array ( ) 
					 * [mpaas_hist:protected] => Array ( ) 
					 * [mpaa_justification:protected] => 
					 * [plot_plot:protected] => Array ( ) 
					 * [synopsis_wiki:protected] => 
					 * [release_info:protected] => Array ( ) 
					 * [seasoncount:protected] => -1 
					 * [season_episodes:protected] => Array ( ) 
					 * [sound:protected] => Array ( ) 
					 * [soundtracks:protected] => Array ( ) 
					 * [split_comment:protected] => Array ( ) 
					 * [split_plot:protected] => Array ( ) 
					 * [taglines:protected] => Array ( ) 
					 * [trailers:protected] => Array ( ) 
					 * [video_sites:protected] => Array ( ) 
					 * [soundclip_sites:protected] => Array ( ) 
					 * [photo_sites:protected] => Array ( ) 
					 * [misc_sites:protected] => Array ( ) 
					 * [trivia:protected] => Array ( ) 
					 * [compcred_prod:protected] => Array ( ) 
					 * [compcred_dist:protected] => Array ( ) 
					 * [compcred_special:protected] => Array ( ) 
					 * [compcred_other:protected] => Array ( ) 
					 * [parental_guide:protected] => Array ( ) 
					 * [official_sites:protected] => Array ( ) 
					 * [locations:protected] => Array ( ) 
					 * [version] => 2.6.1 
					 * [lastServerResponse] => 
					 * [months:protected] => Array ( [January] => 01 [February] => 02 [March] => 03 [April] => 04 [May] => 05 [June] => 06 [July] => 07 [August] => 08 [September] => 09 [October] => 10 [November] => 11 [December] => 12 ) 
					 * [cache:protected] => imdb_cache Object ( [config:protected] => imdb Object *RECURSION* [logger:protected] => imdb_logger Object ( [enabled:protected] => ) ) 
					 * [logger:protected] => imdb_logger Object ( [enabled:protected] => ) 
					 * ["TitleFoot"]=> string(0) "" 
					 * ["Credits"]=> string(0) "" 
					 * ["CrazyCredits"]=> string(0) "" 
					 * ["Amazon"]=> string(0) "" 
					 * ["Goofs"]=> string(0) "" 
					 * ["Trivia"]=> string(0) "" 
					 * ["Plot"]=> string(0) "" 
					 * ["Synopsis"]=> string(0) "" 
					 * ["Comments"]=> string(0) "" 
					 * ["Quotes"]=> string(0) "" 
					 * ["Taglines"]=> string(0) "" 
					 * ["Plotoutline"]=> string(0) "" 
					 * ["Directed"]=> string(0) "" 
					 * ["Episodes"]=> string(0) "" 
					 * ["Trailers"]=> string(0) "" 
					 * ["MovieConnections"]=> string(0) "" 
					 * ["ExtReviews"]=> string(0) "" 
					 * ["ReleaseInfo"]=> string(84653) "
					 */
					//print_r($movie['akas']);
					echo '<br>';
					/* foreach ($movie->alsoknow() as $aka)
					  {
					  if (!empty($aka['country']))
					  {
					  echo 'Country: '.$aka['country'] . '<br>';
					  echo 'Title: '.$aka['title'] . '<br>';
					  echo 'Lang: '.$aka['lang'] . '<br>';
					  echo 'Comment: '.$aka['comment'] . '<br>';
					  echo 'Year: '.$aka['year'] . '<br>';
					  foreach($aka['comments'] as $comment)
					  {
					  var_dump($comment);
					  echo ' | ';
					  }
					  echo '<br>';
					  }
					  //var_dump($aka);
					  echo '<br>*************************<br>';
					  } */
					foreach ($movie->country() as $country)
					{
						echo $country . '<br>----<br>';
					}
					foreach ($movie->genres() as $genre)
					{
						echo $genre . ', ';
					}echo '<br><hr>';
					foreach ($movie->director() as $director)
					{
						$person = new imdb_person($director['imdb']);
						echo $person->name() . '<br>';
						/* foreach($personSearch->bio() as $bio)
						  {
						  foreach($bio as $data)
						  {
						  if(!is_array($data))
						  {
						  echo '<p>'.$data.'</p>';
						  }
						  }
						  } */
						$count = 0;
						foreach ($person->nickname() as $nick)
						{
							$count++;
							echo $count . ' ' . $nick;
						}
						echo $person->born()['day'] . '/' . $person->born()['mon'] . '-' . $person->born()['year'] . ' in ' . $person->born()['place'] . '<br>';
						echo '<a href="http://www.imdb.com/name/nm' . $person->imdbid() . '/">Link</a>';
						//echo '<img src="'.$person->photo().'">';
					}
					echo $movie->plotoutline() . '<br><br>';
					echo $movie->storyline();
					?></p>
				<a href="http://www.imdb.com/title/<?php echo 'tt' . $movie->imdbid() ?>/" target="_blank">
					<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
				</a>
				<p>
					<b>Genres:</b><br>
					<?php
					foreach ($movie->genres() AS $genre)
					{
						echo $genre . '<br>';
					}
					?>
					<b>Cast:</b><br>
					<?php
					foreach ($movie->cast() as $star)
					{
						if ($star['thumb'] != null)
						{
							?>
							<img src="<?php echo $star['thumb'] ?>">
							<?php
							echo $star['name'] . ' <i>playing</i> ' . $star['role'] . '<br>';
							$person->setid($star['imdb']);
							echo $person->born()['day'] . '/' . $person->born()['mon'] . '-' . $person->born()['year'] . ' in ' . $person->born()['place'] . '<br>';
						}
					}
					?>
				</p>
			</h4>
			<button class="btn btn-success">Add Movie</button>
		</div>
		<?php
	}
	if (count($results) == 0)
	{
		echo '<p>Unfortunately, your search did not give a result. Please try again.</p>';
	}
	?>
</div>
<div class="clearfix"></div>