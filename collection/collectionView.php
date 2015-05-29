<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';

require '../movie/movie.php';

require '../genre/genre.php';

//Instantiate new Collection object
$collection = new Collection($_GET['id']);

$owner = new User($collection->getUserId());

$signed_in = false;
$own_collection = false;
$collection_private = true;

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
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="pull-right">
									<?php
									if (!$collection_private OR $own_collection)
									{
										if (!$collection_private)
										{
											if (!$own_collection)
											{
												?>
												<button class="btn btn-warning"><span class="fa fa-heart"></span> Favorite</button>
												<?php
											}
											?>
											<button class="btn btn-facebook"><span class="fa fa-facebook"></span> Share</button>
											<button class="btn btn-twitter"><span class="fa fa-twitter"></span> Tweet</button>
											<button class="btn btn-primary"><span class="fa fa-envelope"></span> Share</button>
											<?php
										}
										if ($own_collection)
										{
											?>
											<button class="btn btn-danger"><span class="fa fa-trash"></span> Delete Collection</button>
											<?php
										}
										?>
									</div>
								</div>
							</div>
							<br>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<p><?php echo $collection->getDescription() ?></p>
							</div>
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
										?>
										<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
											<div class="owned">
												<div class="movie-box">
													<div class="image-box" id="<?php echo $movie->getImdbId() ?>">
														<img src="<?php echo $movie->getPosterUrl() ?>" class="thumbnail img-responsive">
														<div class="edit-bar" id="<?php echo $movie->getImdbId(); ?>-edit-bar" style="display: none;">
															<span class="fa fa-remove fa-4x pull-right" data-toggle="modal" data-target="#<?php echo $movie->getImdbId() ?>DeleteModal"></span>
															<?php
															if ($own_collection)
															{
																?>
																<span class="fa fa-search fa-4x pull-left" data-toggle="modal" data-target="#<?php echo $movie->getImdbId() ?>DetailsModal"></span>
																<?php
															}
															?>
														</div>
													</div>
													<h4><?php echo $movie->getTitle() ?> (<?php echo substr($movie->getRelease(), 7) ?>)</h4>
												</div>
											</div>
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
																	<a href="<?php echo $movie->getImdbLink() ?>" target="_blank"><img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png"></a><br>

																	<b>Release</b>: <?php echo $movie->getRelease() ?><br>
																	<b>Runtime</b>: <?php echo $movie->getRuntime() ?><br>
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
																</p>
															</div>
															<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																<p><b>Plot</b>: <?php echo $movie->getPlot() ?><br></p>
															</div>
														</div>
														<div class="clearfix"></div>
														<div class="modal-footer">
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
				</div>
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
				require '../includes/footer.php';
			}
			?>
