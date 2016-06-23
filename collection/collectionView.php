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

if (isset($_POST['EditDescriptionSubmit']))
{
	$text = trim($_POST['descriptionText']);
	$collection->updateDescription($text);
}

if (isset($_POST['EditCollectionSubmit']))
{
	$image_file_name = NULL;
	if (isset($_FILES['image-file']))
	{
		$image_file_name = upload_image(1500, "image-file");
	}
	$collection->update_collection($_POST['edcolnm'], $image_file_name, $_POST['edcolpp']);
}

/* DETERMINE IF USER HAS ACCESS */
$access_granted = true;
if ($collection_private && !$own_collection)
{
	$access_granted = false;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
		if (!$access_granted)
		{
			?>
			<title>Private Collection | Online movie Index</title>
			<?php
		}
		else
		{
			?>
			<title><?php echo $collection->getName() ?> | Online movie Index</title>
			<?php
		}
		?>
		<title>Your Collections | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php require '../includes/navbar.php'; ?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="collection-header" style="background: url(<?php echo $collection->get_image_name() ?>) !important; background-position: center !important; background-size: cover !important; height: 300px;">
							<div class="collection-header-title-area">
								<div class="collection-header-title">
									<?php
									if (!$access_granted)
									{
										?>
										<h1>
											<span class="fa fa-lock fa-omi-blue"></span> Private Collection
										</h1>
										<small class="lead collection-lead">
											Access denied for viewing collection.
										</small>
										<?php
									}
									else
									{
										?>
										<h1>
											<?php echo $collection->getName() ?>
										</h1>
										<small class="collection-lead">
											<?php
											if ($collection->getPrivacy())
											{
												?>
											<span class="fa fa-lock fa-omi-blue" title="Private Collection"></span>
												<?php
											}
											else
											{
												?>
												<span class="fa fa-unlock fa-omi-blue" title="Public Collection"></span>
												<?php
											}
											?>
											Created on: <?php echo formatShortDateTime($collection->getCreatedDatetime()) ?> by <a href="<?php echo BASE_URL ?>user/<?php echo $owner->getId() ?>/"><?php echo $owner->getUsername() ?></a>
										</small>
										<?php
									}
									?>
								</div>
							</div>
						</div>
						<?php
						if (!$access_granted)
						{
							?>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-3 col-md-4 col-sm-6 col-xs-12">
											<form action="" method="POST" name="reqacccolform">
												<label class="form-label-header">
													Request owner for access
												</label>
												<textarea class="form-control input-lg unreziseable" name="reqaccmes" placeholder="Message to the owner" required="required" rows="5"></textarea>
												<div class="text-center top-mar-20">
													<button type="submit" class="btn btn-default btn-lg" name="reqacccolsbm"><span class="fa fa-send fa-omi-blue"></span> Send Request</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
							<?php
							//Just to end the page, if user doesn't have access.
							echo '</div></div></div></div>';
							require '../includes/footer.php';
							echo '</body></html>';
							die();
						}
						?>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php
								if ($own_collection)
								{
									?>
									<div class="pull-left">
										<?php require '../search/movieSearchView.php'; ?>
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
											<button class="btn btn-default" data-toggle="modal" data-target="#editCollectionModal"><span class="fa fa-edit fa-omi-blue"></span><span class="hidden-xs"> Edit</span></button>
											<button class="btn btn-default disabled btn-delete"><span class="fa fa-trash fa-darkred"></span><span class="hidden-xs"> Delete</span></button>
											<?php
										}
									}
									?>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
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
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-inline">
								<div class="pull-right">
									<div class="input-group">
										<span class="input-group-addon"><span class="fa fa-search fa-omi-blue"></span></span>
										<input type="text" class="form-control" placeholder="Search in collection..." id="titleSearchField">
									</div>
									<button class="btn btn-default form-control" onclick="toggleFilters()"><span class="fa fa-filter fa-omi-blue"></span> Filters</button>
								</div>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="collection-filters">
								filters
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="movie-tags collection-tags">
								<?php
								$collection_tags = $collection->get_all_movie_tags_in_collection();
								foreach ($collection_tags as $collection_tag_id => $collection_tag_name)
								{
									?>
									<div class="movie-tag" id="mt-<?php echo $collection_tag_id ?>" tag_id="<?php echo $collection_tag_id ?>"><?php echo $collection_tag_name ?></div>
									<?php
								}
								?>
							</div>
						</div>
						<div class="clearfix"></div>
						<hr>
						<div class="row movie-holder">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php
									$collection->save_collection_viewed_by_user($active_user->getId());
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
											$movies_tags = $movie->get_all_movies_tags($active_user->getId());
											$movies_tags_array = array();
											foreach ($movies_tags as $tag_id => $tag_name)
											{
												$movies_tags_array[] = $tag_id;
											}
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
											<div class="movie-listing collection-list-movie" movie-tags="<?php echo implode(",", $movies_tags_array) ?>">
												<div class="col-lg-1 col-md-2 col-sm-3 col-xs-4">
													<a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
														<img src="<?php echo $movie->getPosterUrl() ?>" class="img-responsive">
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
												<!-- Modal -->
												<div class="modal fade" id="<?php echo $movie->getImdbId() ?>DetailsModal" aria-labelledby="<?php echo $movie->getImdbId() ?>ModalLabel" >
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close modal-header-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<?php
																$favorite = $movie->check_if_movie_is_favorite($active_user->getId());
																if ($movie->get_if_there_is_movie_images())
																{
																	?>
																	<div class="header-image" style="background: url(<?php echo $movie->get_movie_image_for_header(); ?>)">
																		<div class="movie-title">
																			<h3 id="<?php echo $movie->getImdbId() ?>ModalLabel">
																				<?php
																				if ($favorite)
																				{
																					?>
																					<span class="fa fa-heart fa-darkred"></span>
																					<?php
																				}
																				?>
																				<?php echo $title ?> (<?php echo $movie->getYear() ?>)
																			</h3>
																		</div>
																	</div>
																	<?php
																}
																else
																{
																	?>
																	<h4 class="modal-title" id="<?php echo $movie->getImdbId() ?>ModalLabel">
																		<?php
																		if ($favorite)
																		{
																			?>
																			<span class="fa fa-heart fa-darkred"></span>
																			<?php
																		}
																		?>
																		<?php echo $movie->getTitle() ?>
																	</h4>
																	<?php
																}
																?>
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
					</div>
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
										if ($collection->getDescription() == "No description")
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
										<textarea class="form-control dark-hidden-input unreziseable" rows="6" name="descriptionText" placeholder="<?php echo $placeHolder ?>"><?php echo $value ?></textarea>
									</div>
									<div class="clearfix"></div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-default" name="EditDescriptionSubmit"><span class="fa fa-check fa-omi-blue"></span> Save</button>
										<button type="reset" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-orange"></span> Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal fade" id="editCollectionModal" aria-labelledby="editCollectionModalLabel" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="editCollectionModalLabel">Edit Collection</h4>
								</div>
								<form action="" method="POST" name="editDescriptionForm" enctype="multipart/form-data">
									<div class="modal-body">
										<div class="row form-row">
											<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
												<div class="label-title">
													Collection Name
												</div>
												<input type="text" class="form-control dark-hidden-input input-lg" value="<?php echo $collection->getName() ?>" name="edcolnm">
											</div>
										</div>
										<div class="row form-row">
											<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
												<div class="label-title">
													Header Image
												</div>
												<input type="file" class="form-control dark-hidden-input" name="image-file">
											</div>
										</div>
										<div class="row form-row">
											<?php
											if ($collection->getPrivacy() == 1)
											{
												$rad_private = " checked='checked'";
												$rad_public = "";
											}
											else
											{
												$rad_private = "";
												$rad_public = " checked='checked'";
											}
											?>
											<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
												<div class="label-title">
													Privacy Setting
												</div>
												<div class="radio-group">
													<label class="radio-inline"><input type="radio" name="edcolpp" value="1"<?php echo $rad_private ?>>Private Collection</label>
													<label class="radio-inline"><input type="radio" name="edcolpp" value="0"<?php echo $rad_public ?>>Public Collection</label>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="modal-footer">
										<button type="submit" class="btn btn-default" name="EditCollectionSubmit"><span class="fa fa-check fa-omi-blue"></span> Save</button>
										<button type="reset" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-orange"></span> Cancel</button>
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

					$("#titleSearchField").keyup(function () {
						var SearchString = $(this).val();
						if (SearchString.length > 0)
						{
							$(".movie-listing").each(function () {
								if ($(this).find("div div .movie-title a").text().search(new RegExp(SearchString, "i")) < 0)
								{
									// Show the list item if the phrase matches and increase the count by 1
									$(this).fadeOut();
								}
								else
								{
									$(this).fadeIn();
								}
							});
						}
					});

					$(".movie-tag").click(function () {
						var TagId = $(this).attr("tag_id");
						if ($(this).hasClass("active-tag-link"))
						{
							$(".movie-tag").each(function () {
								$(this).removeClass("active-tag-link");
								$(this).removeClass("inactive-tag-link");
							});
							$(".movie-listing").each(function () {
								$(this).slideDown();
							});
						}
						else
						{
							$(".movie-tag").each(function () {
								$(this).removeClass("active-tag-link");
								$(this).addClass("inactive-tag-link");
							});
							$(this).addClass("active-tag-link");
							$(".movie-listing").each(function () {
								var movieTagArray = $(this).attr("movie-tags").split(",");
								if (isInArray(TagId, movieTagArray))
								{
									$(this).slideDown();
								}
								else
								{
									$(this).slideUp();
								}
							});
						}
					});
					function isInArray(value, array) {
						return array.indexOf(value) > -1;
					}

					function toggleFilters()
					{
						$("#collection-filters").slideToggle();
					}

					$(".btn-delete").mouseenter(function () {
						$(this).removeClass("btn-default");
						$(this).addClass("btn-danger");
						$(this).children(".fa").removeClass("fa-darkred");
					});
					$(".btn-delete").mouseleave(function () {
						$(this).removeClass("btn-danger");
						$(this).addClass("btn-default");
						$(this).children(".fa").addClass("fa-darkred");
					});
				</script>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php
		require '../includes/footer.php';
		?>
	</body>
</html>