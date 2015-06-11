<?php
$movie_id = $_GET['mid'];

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require './movie.php';
require '../user/user.php';
require '../person/person.php';
require '../genre/genre.php';

$movie = new Movie($movie_id);
$imdbId = $movie->getImdbId();

$json = file_get_contents("http://www.omdbapi.com/?i=$imdbId&plot=full&r=json");

//JSON decode of answer
$data = json_decode($json, true);

if($movie->getOrigTitle() == null)
{
	$origTitle = $movie->getTitle();
}
else
{
	$origTitle = $movie->getOrigTitle();
}
?>
<html lang="en">
	<head>
		<title><?php echo $origTitle ?> | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php
			if (isset($_SESSION['signed_in']))
			{
				$active_user = new User($_SESSION['user_id']);
				require '../includes/navbar.php';
			}
			else
			{
				require '../includes/loginBar.php';
			}
			?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="page-header">
							<h1><?php echo $origTitle ?>
							</h1>
						</div>
						<div class="row">
							<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 row">
								<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
									<img src="<?php echo $data['Poster'] ?>" class="thumbnail img-responsive pull-left">
								</div>
								<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
									<?php
									if($movie->getOrigTitle() == null)
									{
										$origTitle = $movie->getTitle();
									}
									else
									{
										$origTitle = $movie->getOrigTitle();
									}
									?>
									<label><span class="label-title">Title: </span><?php echo $movie->getTitle(); ?></label><br>
									<label><span class="label-title">Original: </span><?php echo $origTitle; ?></label><br>
									<label><span class="label-title">Released: </span><?php echo $movie->getYear() ?></label><br>
									<label><span class="label-title">Runtime: </span><?php echo $movie->getRuntime() ?> minutes</label><br>
									<label><span class="label-title">Language: </span><?php echo $movie->getLanguage(); ?></label><br>
									<label><span class="label-title">Genres: </span>
									<?php
									$genre = new Genre();
									$movieGenres = $movie->getAllGenresForMovie();
									$numberOfGenres = count($movieGenres);
									$counter = 0;
									foreach($movieGenres as $movieGenre)
									{
										$genre->setValuesAccordingToId($movieGenre);
										echo $genre->getName();
										$counter++;
										if($counter < $numberOfGenres)
										{
											echo ', ';
										}
									}
									?>
									</label><br>
									<label><span class="label-title">Director: </span>
									<?php
									$director = new Person();
									$directors = $movie->getDirectors();
									$numberOfDirectors = count($directors);
									$counter = 0;
									if($numberOfDirectors == 0)
									{
										echo '<i>Not on record</i>';
									}
									foreach($directors as $movieDirector)
									{
										$director->setValuesAccordingToId($movieDirector);
										echo $director->getName();
										$counter++;
										if($counter < $numberOfDirectors)
										{
											echo ', ';
										}
									}
									?>
									</label><br>
									<label><span class="label-title">IMDb Rating: </span><?php echo $data['imdbRating'] ?></label><br>
									<a href="<?php echo $movie->getImdbLink() ?>" target="_blank">
										<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
									</a>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<hr class="visible-xs">
									<button class="btn btn-success"><span class="fa fa-plus"></span> Add to Collection</button>
									<button class="btn btn-warning disabled"><span class="fa fa-heart"></span> Favorite</button>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<hr>
									<label><span class="label-title">Short Plot: </span></label><br>
									<p>
										<?php echo $movie->getPlot() ?>
									</p>
									<label><span class="label-title" id="full-plot-toggle" style="cursor: pointer">Full Plot: (Can contain spoilers)</span></label><br>
									<p id="full-plot" style="display: none">
										<?php echo $data['Plot']; ?><br><br>
										<i><label class="label-title" id="hide-full-plot" style="color: lightgray; cursor: pointer; text-decoration: underline">Hide Full plot</label></i>	
									</p>
									<hr class="hidden-lg">
								</div>
							</div>
							<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
								<label><span class="label-title">Cast: </span></label><br>
								<?php
								$castList = $movie->getFullCast();
								$actor = new Person();
								foreach ($castList as $cast => $role)
								{
									$actor->setValuesAccordingToId($cast);
									?>
									<div class="cast-list-entry">
										<div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 row">
											<?php
											if ($actor->getPhoto() == null)
											{
												?>
											<img src="http://ia.media-imdb.com/images/G/01/imdb/images/nopicture/32x44/name-2138558783._CB379389446_.png" class="cast-thumb">

												<?php
											}
											else
											{
												?>
											<img src = "<?php echo $actor->getPhoto() ?>" class="cast-thumb">
												<?php
											}
											?>
											<span class="label-title"><?php echo $actor->getName() ?></span><br>
										</div>
										<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2 cast-role">
											<i style="color: lightgrey" class="pull-right">as</i>
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 cast-role">
											<span class="label-title"><?php echo $role ?></span><br>
										</div>
										<div class="clearfix"></div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		require '../includes/footer.php';
		?>
		<script>
			$("#full-plot-toggle").click(function () {
				$("#full-plot").toggle(250);
			});
			$("#hide-full-plot").click(function () {
				$("#full-plot").hide(250);
			});
		</script>
	</body>
</html>