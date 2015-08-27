<?php
$person_id = $_GET['pid'];

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require './person.php';
require '../user/user.php';
require '../movie/movie.php';
require '../genre/genre.php';
require '../collection/collection.php';

require '../includes/api/imdbphp/imdb_person.class.php';

$person = new Person($person_id);
$imdbPerson = new imdb_person(substr($person->getImdbId(), -7));
if (empty($person->getBio()))
{
	$person_bio = "";
	foreach ($imdbPerson->bio() as $bio)
	{
		$person_bio .= $bio['desc'];
	}
	if (isset($imdbPerson->born()['year']))
	{
		$born = $imdbPerson->born()['year'] . '-' . $imdbPerson->born()['mon'] . '-' . $imdbPerson->born()['day'];
	}
	else
	{
		$born = null;
	}
	$person->updatePersonWithFullData($person_bio, $born, $imdbPerson->photo(FALSE));
	$person->setValuesAccordingToId($person_id);
}
?>
<html lang="en">
	<head>
		<title><?php echo $person->getName() ?> | Online movie Index</title>
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
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="page-header">
						<h1><?php echo $person->getName() ?>
						</h1>
					</div>
					<div class="row">
						<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
							<img src="<?php echo $imdbPerson->photo(FALSE) ?>" class="thumbnail img-responsive pull-left" style="margin-right: 25px; margin-bottom: 10px;">
							<?php
							if ($person->getBorn() != null)
							{
								?>
								<label><span class="label-title">Born: </span><?php echo formatTextDate($person->getBorn()); ?></label><br>
								<?php
							}
							if ($person->getBio() != null)
							{
								?>
								<label><span class="label-title">Bio: </span></label><br>
								<p>
									<?php echo $person->getBio() ?>
								</p>
								<?php
							}
							?>
						</div>
						<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
							<label><span class="label-title">Filmography</span></label> <i style="color: white">(Only movies saved in OMI)</i><label><span class="label-title">:</span></label><br>
							<p>
								<?php
								$allMovieIds = $person->getAllMovies();
								$movie = new Movie();
								foreach ($allMovieIds as $movieIdAndRole)
								{
									$movie->setValuesWithId($movieIdAndRole['movie_id']);
									if ($movie->getOrigTitle() == null)
									{
										$origTitle = $movie->getTitle();
									}
									else
									{
										$origTitle = $movie->getOrigTitle();
									}
									?>
								<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/"><?php echo $origTitle ?> (<?php echo $movie->getYear() ?>)</a> <i style="color: lightgrey">as</i> <b><?php echo $movieIdAndRole['role'] ?></b><br>
									<?php
								}
								?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	require '../includes/footer.php';
	?>
</body>
</html>