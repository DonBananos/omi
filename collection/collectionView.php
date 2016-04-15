<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';

require '../movie/movie.php';

require '../genre/genre.php';
require '../person/person.php';
require '../movie/movieHandler.php';

//Instantiate new Collection object
$collection = new Collection($_GET['id']);

$owner = new User($collection->getUserId());

$signed_in = false;
$own_collection = false;
$collection_private = true;

$mh = new MovieHandler();

//Check if user is signed in
if (isset($_SESSION['signed_in']))
{
	$userId = $_SESSION['user_id'];
	if ($userId > 0)
	{
		$active_user = new User($userId);
		$signed_in = true;
		if ($active_user->getId() == $owner->getId())
		{
			$own_collection = true;
		}
	}
}
if ($collection->getPrivacy() != 1)
{
	$collection_private = false;
}

if(isset($_POST['EditDescriptionSubmit']))
{
	$text = trim($_POST['descriptionText']);
	$collection->updateDescription($text);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Your Collections | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php require '../includes/navbar.php'; ?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="page-header">
							<h1><?php echo $collection->getName() ?><br>
								<small>
									Created on: <?php echo formatShortDateTime($collection->getCreatedDatetime()) ?> by <?php echo $owner->getUsername() ?>
								</small>
							</h1>
						</div>
						<div class="row">
							<?php
							if ($own_collection)
							{
								?>
								<div class="pull-left">
									<?php require '../search/movieSearchView.php'; ?>
								</div>
								<?php
							}
							if ($collection_private AND ! $own_collection)
							{
								?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<p>This Collection is private, and you are not allowed to see it</p>
								</div>
								<?php
							}
							?>
							<div class="pull-right">
								<?php
								if (!$collection_private OR $own_collection)
								{
									if (!$collection_private)
									{
										if (!$own_collection)
										{
											?>
											<button class="btn btn-warning disabled"><span class="fa fa-heart"></span><span class="hidden-xs"> Favorite</span></button>
											<?php
										}
										?>
										<button class="btn btn-facebook disabled"><span class="fa fa-facebook"></span><span class="hidden-xs"> Share</span></button>
										<button class="btn btn-twitter disabled"><span class="fa fa-twitter"></span><span class="hidden-xs"> Tweet</span></button>
										<button class="btn btn-primary disabled"><span class="fa fa-envelope"></span><span class="hidden-xs"> Share</span></button>
										<?php
									}
									if ($own_collection)
									{
										?>
										<button class="btn btn-danger disabled"><span class="fa fa-trash-o"></span><span class="hidden-xs"> Delete Collection</span></button>
										<?php
									}
									?>
								</div>
							</div>
							<br>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<p class="collection-description">
									<?php
									echo nl2br($collection->getDescription());
									if ($own_collection)
									{
										?>
										<a href="#" class="edit-pencil" data-toggle="modal" data-target="#editDescriptionModal">
											<span class="fa fa-pencil"></span>
										</a>
										<?php
									}
									?>
								</p>
							</div>
							<div class="clearfix"></div>
							<hr>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php
								$movieIds = $collection->getAllMoviesInCollection();
								if (count($movieIds) < 1)
								{
									?>
									<p>
										There's no movies in this collection yet.
									</p>
									<?php
								}
								else
								{
									$movie = new Movie();
									$movieCounter = 0;
									$numberOfMoviesInCollection = count($movieIds);
									foreach ($movieIds as $movieId)
									{
										$movie->setValuesWithId($movieId);
										if (strlen($movie->getPlot()) > 300)
										{
											$plot = substr($movie->getPlot(), 0, 297) . ' (...)';
										}
										else
										{
											$plot = $movie->getPlot();
										}
										if ($movie->getLocalTitleIfExists() !== false)
										{
											$title = $movie->getLocalTitleIfExists();
											$origTitle = $movie->getTitle();
										}
										else
										{
											$title = $movie->getTitle();
											$origTitle = false;
										}
										?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-listing">
											<div class="col-lg-1 col-md-2 col-sm-3 col-xs-4">
												<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
													<img src="<?php echo $movie->getPosterUrlThumb() ?>" class="img-responsive">
												</a>
											</div>
											<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6">
												<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
													<h4 class="movie-title"><a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/"><?php echo $title ?></a> <span style="color: white">(<?php echo $movie->getYear() ?>)</span></h4>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-xs">
													<?php
													$quality = $movie->getMovieInCollectionQuality($collection->getId());
													?>
													<label class="movie-title" style="margin: 10px;"><span class="label-title">Quality: </span><?php echo $quality ?></label>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 hidden-xs">
													<?php
													$subs = $movie->getAllSubsForMovieInCollection($collection->getId());
													$numberOfSubs = count($subs);
													?>
													<label class="movie-title" style="margin: 10px;">
														<span class="label-title">Subs: </span>
														<?php
														if ($numberOfSubs == 0)
														{
															echo 'No Subtitles';
														}
														else
														{
															$counter = 0;
															foreach ($subs as $sub)
															{
																echo $sub;
																$counter++;
																if ($counter < $numberOfSubs)
																{
																	echo ', ';
																}
															}
														}
														?>
													</label>
												</div>
												<div class="clearfix"></div>
												<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 hidden-xs">
													<label class="movie-title" style="margin: 10px;">
														<span class="label-title">Genre: </span>
														<?php
														$genre = new Genre();
														$genreIds = $movie->getAllGenresForMovie();
														$numberOfGenres = count($genreIds);
														$numberOfRuns = 0;
														foreach ($genreIds as $genreId)
														{
															$genre->setValuesAccordingToId($genreId);
															echo $genre->getName();
															$numberOfRuns++;
															if ($numberOfRuns < $numberOfGenres)
															{
																echo ', ';
															}
															else
															{
																echo '<br>';
															}
														}
														?>
													</label>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-xs">
													<label class="movie-title" style="margin: 10px;">
														<span class="label-title">Runtime: </span>
														<?php echo $movie->getRuntime() ?> minutes
													</label>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 hidden-xs hidden-sm">
													<label class="movie-title" style="margin: 10px;">
														<span class="label-title">Language: </span>
														<?php echo $movie->getLanguage() ?>
													</label>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="col-lg-1 col-md-1 col-sm-1 col-xs-2 pull-right movie-listing-edit">
												<span class="fa fa-search fa-2x pull-right" data-toggle="modal" data-target="#<?php echo $movie->getImdbId() ?>DetailsModal"></span>
												<div class="clearfix"></div>
												<br>
												<?php
												if ($own_collection)
												{
													?>
													<span class="fa fa-remove fa-2x pull-right" data-toggle="modal" data-target="#<?php echo $movie->getImdbId() ?>DeleteModal"></span>
													<?php
												}
												?>
											</div>
											<div class="clearfix"></div>
											<?php
											$movieCounter++;
											if ($movieCounter < $numberOfMoviesInCollection)
											{
												echo '<hr>';
											}
											?>
											<!-- Modal -->
											<div class="modal fade" id="<?php echo $movie->getImdbId() ?>DetailsModal" aria-labelledby="<?php echo $movie->getImdbId() ?>ModalLabel" >
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															<h4 class="modal-title" id="<?php echo $movie->getImdbId() ?>ModalLabel"><?php echo $movie->getTitle() ?></h4>
														</div>
														<div class="modal-body">
															<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
																<img src="<?php echo $movie->getPosterUrl() ?>" class="thumbnail img-responsive">
															</div>
															<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
																<p>
																	<?php
																	if ($origTitle !== false)
																	{
																		?>
																		<b>Original Title</b>: <?php echo $origTitle ?><br>
																		<?php
																	}
																	?>
																	<b>Release</b>: <?php echo $movie->getYear() ?><br>
																	<b>Runtime</b>: <?php echo $movie->getRuntime() ?> minutes<br>
																	<b>Genre</b>:
																	<?php
																	$genre = new Genre();
																	$genreIds = $movie->getAllGenresForMovie();
																	$numberOfGenres = count($genreIds);
																	$numberOfRuns = 0;
																	foreach ($genreIds as $genreId)
																	{
																		$genre->setValuesAccordingToId($genreId);
																		echo $genre->getName();
																		$numberOfRuns++;
																		if ($numberOfRuns < $numberOfGenres)
																		{
																			echo ', ';
																		}
																		else
																		{
																			echo '<br>';
																		}
																	}
																	?>
																	<b>Language</b>: <?php echo $movie->getLanguage() ?><br>
																	<b>Director</b>: 
																	<?php
																	$director = new Person();
																	$directors = $movie->getDirectors();
																	$numberOfDirectors = count($directors);
																	$counter = 0;
																	if ($numberOfDirectors == 0)
																	{
																		echo '<i>Not on record</i>';
																	}
																	foreach ($directors as $movieDirector)
																	{
																		$director->setValuesAccordingToId($movieDirector);
																		echo $director->getName();
																		$counter++;
																		if ($counter < $numberOfDirectors)
																		{
																			echo ', ';
																		}
																	}
																	?>
																	<br>
																	<b>Quality</b>: <?php echo $quality ?><br>
																	<b>Subtitles</b>: 
																	<?php
																	$subs = $movie->getAllSubsForMovieInCollection($collection->getId());
																	$numberOfSubs = count($subs);
																	if ($numberOfSubs == 0)
																	{
																		echo 'No Subtitles';
																	}
																	else
																	{
																		$counter = 0;
																		foreach ($subs as $sub)
																		{
																			echo $sub;
																			$counter++;
																			if ($counter < $numberOfSubs)
																			{
																				echo ', ';
																			}
																		}
																	}
																	?>
																</p>
																<a href="<?php echo $movie->getImdbLink() ?>" target="_blank"><img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png"></a><br>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<p><b>Plot</b>: <?php echo $movie->getPlot() ?><br></p>
															</div>
														</div>
														<div class="clearfix"></div>
														<div class="modal-footer">
															<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/" class="btn btn-primary">Full Details</a>
															<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
														</div>
													</div>
												</div>
											</div>
											<!-- Modal -->
											<div class="modal fade" id="<?php echo $movie->getImdbId() ?>DeleteModal" aria-labelledby="<?php echo $movie->getImdbId() ?>ModalLabel" >
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
															<h4 class="modal-title" id="<?php echo $movie->getImdbId() ?>ModalLabel">Remove <?php echo $movie->getTitle() ?>?</h4>
														</div>
														<div class="modal-body">
															<h4>Are you sure you wan't to remove <b><?php echo $movie->getTitle() ?></b> from your collection <b>"<?php echo $collection->getName() ?></b>"</h4>
														</div>
														<div class="clearfix"></div>
														<div class="modal-footer">
															<form action="removeMovie/" method="post" style="display: inline">
																<input type="hidden" name="removeMovieId" value="<?php echo $movie->getId() ?>">
																<input type="hidden" name="collectionId" value="<?php echo $collection->getId() ?>">
																<input type="hidden" name="ownCollection" value="<?php echo $own_collection ?>">
																<input type="submit" name="remove" value="Remove" class="btn btn-danger">
															</form>
															<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
								}
								?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				<?php
					require '../includes/footer.php';
				?>
				</div>
				<?php
				if ($own_collection)
				{
					?>
					<div class="modal fade" id="editDescriptionModal" aria-labelledby="editDescriptionModalLabel" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="editDescriptionModalLabel">Add/Edit Collection Description</h4>
								</div>
								<form action=" " method="POST" name="editDescriptionForm">
									<div class="modal-body">
										<?php
										if($collection->getDescription() == "No description")
										{
											$value = "";
											$placeHolder = $collection->getDescription();
										}
										else
										{
											$placeHolder = "";
											$value = $collection->getDescription();
										}
										?>
										<textarea class="form-control" rows="6" name="descriptionText" placeholder="<?php echo $placeHolder ?>"><?php echo $value ?></textarea>
									</div>
									<div class="clearfix"></div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-primary" name="EditDescriptionSubmit"><span class="fa fa-check"></span> Save</button>
										<button type="reset" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times"></span> Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<script>
					$(".image-box").hover(function () {
						var element = $(this).attr('id');
						$("#" + element + "-edit-bar").fadeIn();
					},
							function () {
								var element = $(this).attr('id');
								$("#" + element + "-edit-bar").fadeOut();
							});
				</script>

				<?php
			}
			?>
		</div>
