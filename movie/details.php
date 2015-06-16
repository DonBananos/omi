<?php
$movie_id = $_GET['mid'];

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require './movie.php';
require '../user/user.php';
require '../person/person.php';
require '../genre/genre.php';
require '../collection/collection.php';
require './movieHandler.php';

$movie = new Movie($movie_id);
$imdbId = $movie->getImdbId();

$mh = new MovieHandler();

$json = file_get_contents("http://www.omdbapi.com/?i=$imdbId&plot=full&r=json");

//JSON decode of answer
$data = json_decode($json, true);

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

if (isset($_POST['qualityChange']))
{
	$selectedQuality = $_POST['qualitySelect'];
	$collectionId = $_POST['cid'];
	$movieId = $movie->getId();
	$movie->updateQualityInCollection($selectedQuality, $collectionId);
	$subsSelected = $_POST['checkedSubs'];
	foreach ($subsSelected as $subId)
	{
		$movie->saveSubtitleForMovieInCollection($subId, $collectionId);
	}
}

if (isset($_POST['saveInCollection']))
{
	$collectionId = $_POST['collectionChooser'];
	$movie->saveMovieToCollection($collectionId);
}
?>
<html lang="en">
	<head>
		<title><?php echo $title ?> | Online movie Index</title>
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
							<h1><?php echo $title ?></h1>
						</div>
						<div class="row">
							<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12 row">
								<div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
									<img src="<?php echo $data['Poster'] ?>" class="thumbnail img-responsive pull-left">
								</div>
								<div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
									<?php
									if ($movie->getOrigTitle() == null)
									{
										$origTitle = $movie->getTitle();
									}
									else
									{
										$origTitle = $movie->getOrigTitle();
									}
									?>
									<label><span class="label-title">Title: </span><?php echo $title; ?></label><br>
									<?php
									if ($origTitle !== false)
									{
										?>
										<label><span class="label-title">Original: </span><?php echo $origTitle; ?></label><br>
										<?php
									}
									?>
									<label><span class="label-title">Released: </span><?php echo $movie->getYear() ?></label><br>
									<label><span class="label-title">Runtime: </span><?php echo $movie->getRuntime() ?> minutes</label><br>
									<label><span class="label-title">Language: </span><?php echo $movie->getLanguage(); ?></label><br>
									<label><span class="label-title">Genres: </span>
										<?php
										$genre = new Genre();
										$movieGenres = $movie->getAllGenresForMovie();
										$numberOfGenres = count($movieGenres);
										$counter = 0;
										foreach ($movieGenres as $movieGenre)
										{
											$genre->setValuesAccordingToId($movieGenre);
											echo $genre->getName();
											$counter++;
											if ($counter < $numberOfGenres)
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
										if ($numberOfDirectors == 0)
										{
											echo '<i>Not on record</i>';
										}
										foreach ($directors as $movieDirector)
										{
											$director->setValuesAccordingToId($movieDirector);
											?>
											<a href="<?php echo $path ?>person/<?php echo $director->getId() ?>/<?php echo $director->getSlug() ?>/"><?php echo $director->getName(); ?></a>
											<?php
											$counter++;
											if ($counter < $numberOfDirectors)
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
								<?php
								$collectionIdsWithoutTheMovie = $movie->getAllCollectionIdsForUserInWhichTheMovieIsNot($active_user->getId());
								$numberOfCollectionsWithoutTheMovie = count($collectionIdsWithoutTheMovie);
								?>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<hr class="visible-xs">
									<?php
									if ($numberOfCollectionsWithoutTheMovie > 0)
									{
										$newCollection = new Collection();
										?>
										<button class="btn btn-success" data-toggle="modal" data-target="#addToCollectionModal"><span class="fa fa-plus"></span> Add to Collection</button>
										<!-- Modal -->
										<div class="modal fade" id="addToCollectionModal" aria-labelledby="addToCollectionModalLabel" >
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
														<h4 class="modal-title" id="addToCollectionModalLabel">Add <?php echo $origTitle ?> to Collection</h4>
													</div>
													<div class="modal-body">
														<form action="" method="post">
															<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
																<select name="collectionChooser" class="form-control">
																	<?php
																	foreach ($collectionIdsWithoutTheMovie as $collectionId)
																	{
																		$newCollection->setValuesAccordingToId($collectionId);
																		?>
																		<option value="<?php echo $newCollection->getId() ?>"><?php echo $newCollection->getName() ?></option>
																		<?php
																	}
																	?>
																</select>
															</div>
															<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
																<input type="submit" class="btn btn-success" value="Add to Collection" name="saveInCollection">
															</div>
														</form>
														<div class="clearfix"></div>
													</div>
													<div class="clearfix"></div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
													</div>
												</div>
											</div>
										</div>
										<?php
									}
									else
									{
										?>
										<button class="btn btn-success disabled"><span class="fa fa-plus"></span> Add to Collection</button>
										<?php
									}
									?>
									<button class="btn btn-warning disabled"><span class="fa fa-heart"></span> Favorite</button>
								</div>
								<?php
								$collectionIds = $movie->getAllCollectionIdsForUserInWhichTheMovieIs($active_user->getId());
								$numberOfCollections = count($collectionIds);
								$collectionSingularis = 'collection';
								if ($numberOfCollections > 1)
								{
									$collectionSingularis .= 's';
								}
								if ($numberOfCollections > 0)
								{
									?>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<hr>
										<div class="col-lg-12 col-md-9 col-sm-12 col-xs-12 row">
											<label><span class="label-title">In your <?php echo $collectionSingularis ?>: </span></label><br>
											<?php
											$collection = new Collection();
											foreach ($collectionIds as $collectionId => $quality)
											{
												$collection->setValuesAccordingToId($collectionId);
												?>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
													<p>
														<span class="fa fa-pencil" id="edit-mov-col" data-toggle="modal" data-target="#<?php echo $collectionId ?>edit" style="cursor: pointer"></span>
														<a class="label-title" href="<?php echo $path ?>collection/<?php echo $collectionId ?>/<?php echo $collection->getSlug() ?>/"><?php echo $collection->getName() ?></a>
													</p>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
													<p>
														<i>Quality: </i><?php echo $quality ?>
													</p>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
													<?php
													$subs = $movie->getAllSubsForMovieInCollection($collectionId);
													$numberOfSubs = count($subs);
													?>
													<p>
														<i>Subs: </i>

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
													</p>
												</div>
												<!-- Modal -->
												<div class="modal fade" id="<?php echo $collectionId ?>edit" aria-labelledby="<?php echo $collectionId ?>ModalLabel" >
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																<h4 class="modal-title" id="<?php echo $collectionId ?>ModalLabel">Change Movie / Collections Details</h4>
															</div>
															<div class="modal-body">
																<form action="" method="post">
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
																		<label class="label-title pull-right" style="padding: 5px;">Quality: </label>
																	</div>
																	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
																		<input type="hidden" name="cid" value="<?php echo $collectionId ?>">
																		<input type="hidden" name="mid" value="<?php echo $movie->getId() ?>">
																		<select class="form-control" name="qualitySelect">
																			<?php
																			foreach ($qualities as $quali10 => $explanation)
																			{
																				if ($quality == $quali10)
																				{
																					?><option value="<?php echo $quali10 ?>" selected><?php echo $quali10 ?> (<?php echo $explanation ?>)</option><?php
																				}
																				else
																				{
																					?><option value="<?php echo $quali10 ?>"><?php echo $quali10 ?> (<?php echo $explanation ?>)</option><?php
																				}
																			}
																			?>
																		</select>
																	</div>
																	<div class="clearfix"></div>
																	<br>
																	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
																		<label class="label-title pull-right" style="padding: 5px;">Subtitles: </label>
																	</div>
																	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8" style="overflow-y: auto; max-height: 20vh;">
																		<small style="color: grey">Used by you:</small>
																		<?php
																		$usedSubs = $mh->getAllUsedSubs($active_user->getId());
																		$allSubs = $mh->getAllPossibleSubs();
																		$ownedSubs = $movie->getAllSubsForMovieInCollection($collectionId);
																		$printedSubIds = array();
																		foreach ($usedSubs as $usedSub)
																		{
																			foreach ($ownedSubs as $code)
																			{
																				$key = array_search($usedSub['id'], $printedSubIds);
																				if ($usedSub['code'] == $code AND ! is_int($key))
																				{
																					?>
																					<div class="checkbox">
																						<label>
																							<input type="checkbox" name="checkedSubs[]" value="<?php echo $usedSub['id'] ?>" checked> <?php echo $usedSub['code'] ?> (<?php echo $usedSub['name'] ?>)
																						</label>
																					</div>
																					<?php
																					array_push($printedSubIds, $usedSub['id']);
																				}
																			}
																			$key = array_search($usedSub['id'], $printedSubIds);
																			if (! is_int($key))
																			{
																				?>
																				<div class="checkbox">
																					<label>
																						<input type="checkbox" name="checkedSubs[]" value="<?php echo $usedSub['id'] ?>"> <?php echo $usedSub['code'] ?> (<?php echo $usedSub['name'] ?>)
																					</label>
																				</div>
																				<?php
																			}
																		}
																		echo '<hr>';
																		foreach ($allSubs as $sub)
																		{
																			$key = array_search($sub['id'], $printedSubIds);
																			if (! is_int($key))
																			{
																				?>
																				<div class="checkbox">
																					<label>
																						<input type="checkbox" name="checkedSubs[]" value="<?php echo $sub['id'] ?>"> <?php echo $sub['code'] ?> (<?php echo $sub['name'] ?>)
																					</label>
																				</div>
																				<?php
																			}
																		}
																		/*
																		  foreach ($usedSubs as $possibleSubtitle)
																		  {
																		  if (count($ownedSubs) == 0)
																		  {
																		  ?>
																		  <div class="checkbox">
																		  <label>
																		  <input type="checkbox" name="checkedSubs[]" value="<?php echo $possibleSubtitle['id'] ?>"> <?php echo $possibleSubtitle['code'] ?> (<?php echo $possibleSubtitle['name'] ?>)
																		  </label>
																		  </div>
																		  <?php
																		  }
																		  else
																		  {
																		  foreach ($ownedSubs as $ownedSubCode)
																		  {
																		  if (array_search($possibleSubtitle['id'], $printedSubIds) == false)
																		  {
																		  if ($ownedSubCode == $possibleSubtitle['code'])
																		  {
																		  ?>
																		  <div class="checkbox">
																		  <label>
																		  <input type="checkbox" name="checkedSubs[]" value="<?php echo $possibleSubtitle['id'] ?>" checked> <?php echo $possibleSubtitle['code'] ?> (<?php echo $possibleSubtitle['name'] ?>)
																		  </label>
																		  </div>
																		  <?php
																		  }
																		  else
																		  {
																		  ?>
																		  <div class="checkbox">
																		  <label>
																		  <input type="checkbox" name="checkedSubs[]" value="<?php echo $possibleSubtitle['id'] ?>"> <?php echo $possibleSubtitle['code'] ?> (<?php echo $possibleSubtitle['name'] ?>)
																		  </label>
																		  </div>
																		  <?php
																		  }
																		  }
																		  }
																		  }
																		  array_push($printedSubIds, $possibleSubtitle['id']);
																		  }
																		  echo '<hr>';
																		  echo '<small style="color: grey">All other subtitles:</small>';
																		  foreach ($allSubs as $possibleSubtitle)
																		  {
																		  if (array_search($possibleSubtitle['id'], $printedSubIds) == FALSE)
																		  {
																		  ?>
																		  <div class="checkbox">
																		  <label>
																		  <input type="checkbox" name="checkedSubs[]" value="<?php echo $possibleSubtitle['id'] ?>"> <?php echo $possibleSubtitle['code'] ?> (<?php echo $possibleSubtitle['name'] ?>)
																		  </label>
																		  </div>
																		  <?php
																		  }
																		  }
																		 */
																		?>
																	</div>
																	<div class="clearfix"></div>
															</div>
															<div class="clearfix"></div>
															<div class="modal-footer">
																<input type="submit" value="Save" name="qualityChange" class="btn btn-success">
																<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
															</div>
															</form>
														</div>
													</div>
												</div>
												<?php
											}
											?>
										</div>
									</div>
									<?php
								}
								?>
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
							<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 cast-list">
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
											<a href="<?php echo $path ?>person/<?php echo $actor->getId() ?>/<?php echo $actor->getSlug() ?>/">
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
												<span class="label-title"><?php echo $actor->getName() ?></span>
											</a>
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