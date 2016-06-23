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

if(isset($_POST['pesubmit']))
{
	$bio = $_POST['pebio'];
	$born = $_POST['pebd'];
	$person->updatePersonWithFullData($bio, date("Y-m-d", strtotime($born)), NULL);
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
						<h1><?php echo $person->getName() ?></h1>
					</div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<span class="pull-right">
								<div class="person-options">
									<a href="http://www.facebook.com" target="_blank">
										<button class="btn btn-facebook">
											<span class="fa fa-facebook"></span> <?php echo $person->getName() ?>
										</button>
									</a>
									<a href="http://www.twitter.com" target="_blank">
										<button class="btn btn-twitter">
											<span class="fa fa-twitter"></span> @actorname
										</button>
									</a>
									<a href="http://www.instagram.com" target="_blank">
										<button class="btn btn-instagram">
											<span class="fa fa-instagram"></span> <?php echo $person->getName() ?>
										</button>
									</a>
									<a href="http://www.domain.com" target="_blank">
										<button class="btn btn-default">
											<span class="fa fa-at"></span> Website
										</button>
									</a>
									<a href="#">
										<button class="btn btn-warning" data-toggle="modal" data-target="#edit-person-modal">
											<span class="fa fa-edit"></span> Edit
										</button>
									</a>
									<a href="http://www.imdb.com/name/nm<?php echo $person->getImdbId() ?>" target="_blank">
										<button class="btn btn-default btn-sm">
											<img src="<?php echo $path ?>includes/img/imdblogo.png" alt="IMDb logo">
										</button>
									</a>
								</div>
							</span>
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
								<p>
									<?php echo $person->getBio() ?>
								</p>
								<?php
							}
							?>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label><span class="label-title">Filmography</span></label> <i style="color: white">(Only movies saved in OMI)</i><label><span class="label-title">:</span></label><br>
							<?php
							$allMovieIds = $person->getAllMovies();
							$movie = new Movie();
							foreach ($allMovieIds as $movieIdAndRole)
							{
								$movie->setValuesWithId($movieIdAndRole['movie_id']);
								if ($movie->getLocalTitleIfExists() != false)
								{
									$title = $movie->getLocalTitleIfExists();
								}
								else
								{
									$title = $movie->getTitle();
								}
								?>
								<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
									<div class="owned">
										<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
											<div class="mbb" style="background: url(<?php echo $movie->getPosterUrl() ?>)" id="movie-<?php echo $movie->getImdbId() ?>">
												<div class="mbbt">
													<h4>
														<?php echo $title ?>
													</h4>
													<h5>
														<span style="color: white"><i>as</i></span> <?php echo $movieIdAndRole['role'] ?>
													</h5>
												</div>
											</div>
										</a>
									</div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		require '../includes/footer.php';
		?>
		<!-- Modal -->
		<div class="modal fade" id="edit-person-modal" aria-labelledby="edit-person-modal-label">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="edit-person-modal-label">Edit <i><?php echo $person->getName() ?></i></h4>
					</div>
					<form action="" method="post">
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row form-row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>
												Gender
											</label>
										</div>
										<div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
											<select class="form-control">
												<option value="0">Unset</option>
												<option value="1">Male</option>
												<option value="2">Female</option>
											</select>
										</div>
									</div>
									<div class="row form-row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>
												Born
											</label>
										</div>
										<div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
											<div class="input-group">
												<span class="input-group-addon" id="bd-in-ad"><span class="fa fa-calendar fa-fw"></span></span>
												<input type="date" class="form-control" describedby="bd-in-ad" placeholder="birthday" name="pebd" value="<?php echo date("Y-m-d", strtotime($person->getBorn())) ?>">
											</div>
											<div class="input-group">
												<span class="input-group-addon" id="bp-in-ad"><span class="fa fa-map-pin fa-fw"></span></span>
												<input type="text" class="form-control" describedby="bp-in-ad" placeholder="Born Area">
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="row form-row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>
												Social
											</label>
										</div>
										<div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
											<div class="input-group">
												<span class="input-group-addon" id="fb-in-ad"><span class="fa fa-facebook fa-fw"></span></span>
												<input type="url" class="form-control" placeholder="Facebook link" describedby="fb-in-ad">
											</div>
											<div class="input-group">
												<span class="input-group-addon" id="tw-in-ad"><span class="fa fa-twitter fa-fw"></span></span>
												<input type="text" class="form-control" placeholder="Twitter username" describedby="tw-in-ad">
											</div>
											<div class="input-group">
												<span class="input-group-addon" id="in-in-ad"><span class="fa fa-instagram fa-fw"></span></span>
												<input type="text" class="form-control" placeholder="Instagram Username" describedby="in-in-ad">
											</div>
											<div class="input-group">
												<span class="input-group-addon" id="we-in-ad"><span class="fa fa-at fa-fw"></span></span>
												<input type="url" class="form-control" placeholder="Website Link" describedby="we-in-ad">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row form-row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>
												Biography
											</label>
										</div>
										<div class="col-lg-12 col-sm-12 col-sm-12 col-xs-12">
											<textarea class="form-control" rows="10" name="pebio"><?php echo $person->getBioForEditor() ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success" name="pesubmit"><span class="fa fa-check"></span> Save</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<script>
			$(".mbb").mouseenter(function () {
				$(this).find(".mbbt").slideToggle();
			});
			$(".mbb").mouseleave(function () {
				$(this).find(".mbbt").slideToggle();
			});
		</script>
	</body>
</html>