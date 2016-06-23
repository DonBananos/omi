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

if (isset($_SESSION['signed_in']))
{
	$active_user = new User($_SESSION['user_id']);
	$movie->save_movie_viewed_by_user($active_user->getId());
}


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
	if (isset($_POST['checkedSubs']))
	{
		$subsSelected = $_POST['checkedSubs'];
		if (count($subsSelected) > 0)
		{
			foreach ($subsSelected as $subId)
			{
				$movie->saveSubtitleForMovieInCollection($subId, $collectionId);
			}
		}
	}
	else
	{
		$subsSelected[] = 0;
	}
	$movie->removeSubtitlesNotSelected($subsSelected, $collectionId);
}

if (isset($_POST['saveInCollection']))
{
	$collectionId = $_POST['collectionChooser'];
	$movie->saveMovieToCollection($collectionId);
}

if (isset($_POST['pfsub']))
{
	$poster_file_name = upload_image(300, "poster-file");
	$movie->update_poster_file($poster_file_name);
}

if (isset($_POST['ifsub']))
{
	$image_file_name = upload_image(1500, "image-file");
	$movie->save_uploaded_image_file($image_file_name, $active_user->getId());
}

if (isset($_POST['favmovsub']))
{
	$movie->mark_movie_as_favorite($active_user->getId());
}

if (isset($_POST['unfavmovsub']))
{
	$movie->unfavorise_movie($active_user->getId());
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
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<?php
								$favorite = $movie->check_if_movie_is_favorite($active_user->getId());
								if ($movie->get_if_there_is_movie_images())
								{
									?>
									<div class="header-image" style="background: url(<?php echo $movie->get_movie_image_for_header(); ?>)">
										<a href="<?php echo BASE_URL ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/">
											<div class="movie-title-area">
												<h1 class="movie-title">
													<?php
													if ($favorite)
													{
														?>
														<span class="fa fa-heart fa-darkred"></span>
														<?php
													}
													?>
													<?php echo $title ?> (<?php echo $movie->getYear() ?>)
												</h1>
											</div>
										</a>
									</div>
									<?php
								}
								else
								{
									?>
									<div class="page-header movie-page">
										<h1>
											<?php
											if ($favorite)
											{
												?>
												<span class="fa fa-heart fa-darkred"></span>
												<?php
											}
											?>
											<?php echo $title ?> (<?php echo $movie->getYear() ?>)
										</h1>
									</div>
									<?php
								}
								?>
								<div class="movie-options">
									<ul>
										<li class="dropdown">
											<a href="" class="dropdown-toggle" data-toggle="dropdown">
												<span class="fa fa-plus fa-green"></span><span class="hidden-xs"> Add</span> <span class="fa fa-caret-down"></span>
											</a>
											<ul class="dropdown-menu" role="menu" id="add-movie-dropdown-menu">
												<?php
												$collectionIdsWithoutTheMovie = $movie->getAllCollectionIdsForUserInWhichTheMovieIsNot($active_user->getId());
												$numberOfCollectionsWithoutTheMovie = count($collectionIdsWithoutTheMovie);
												?>
												<li>
													<?php
													if ($numberOfCollectionsWithoutTheMovie > 0)
													{
														$newCollection = new Collection();
														?>
														<a href="#" data-toggle="modal" data-target="#addToCollectionModal"><span class="fa fa-plus fa-green"></span> Add to Collection</a>
														<?php
													}
													else
													{
														?>
														<a class="disabled"><span class="fa fa-plus fa-grey"></span> Add to Collection</a>
														<?php
													}
													?>
												</li>
												<li><a href="#"><span class="fa fa-plus fa-green"></span> Add to List</a></li>
											</ul>
										</li>
										<li>
											<?php
											if ($movie->check_if_movie_is_favorite($active_user->getId()))
											{
												?>
												<form class="form-inline" action="" method="POST" style="display: inline;">
													<button type="submit" class="btn-as-li" name="unfavmovsub"><span class="fa fa-heart-o fa-red"></span><span class="hidden-xs"> Unfavorise</span></button>
												</form>
												<?php
											}
											else
											{
												?>
												<form class="form-inline" action="" method="POST" style="display: inline;">
													<button type="submit" class="btn-as-li" name="favmovsub"><span class="fa fa-heart fa-red"></span><span class="hidden-xs"> Favorite</span></button>
												</form>
												<?php
											}
											?>
										</li>
										<li id="tag-toggler"><span class="fa fa-tag fa-omi-blue"></span><span class="hidden-xs"> Tags</span></li>
										<li><a href="<?php echo $path ?>movie/<?php echo $movie->getId() ?>/<?php echo $movie->getSlug() ?>/discussion/"><span class="fa fa-comments fa-orange"></span><span class="hidden-xs"> Discussion</span></a></li>
									</ul>
									<ul class="pull-right">
										<li><a href="#" data-toggle="modal" data-target="#edit_movie_details_modal"><span class="fa fa-edit fa-orange"></span><span class="hidden-xs"> Edit</span></a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 mpa">
										<div class="movie-poster">
											<img src="<?php echo $movie->getPosterUrl() ?>" alt="<?php echo $movie->getTitle() ?> poster">
											<div class="edit-poster-area">
												<a href="#">
													<span class="fa fa-edit fa-2x" data-toggle="modal" data-target="#upload-poster-modal"></span>
												</a>
											</div>
										</div>
									</div>
									<div class="col-lg-7 col-md-7 col-sm-6 col-xs-6">
										<?php
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
										<label><span class="label-title">IMDb Rating: </span><?php echo $movie->get_latest_imdb_rating() ?></label><br>
										<a href="<?php echo $movie->getImdbLink() ?>" target="_blank">
											<img src="http://ia.media-imdb.com/images/G/01/imdb/images/plugins/imdb_46x22-2264473254._CB379390954_.png">
										</a>
										<div class="movie-tags">
											<?php
											$tags = $movie->get_all_movies_tags($active_user->getId());
											foreach ($tags as $tag_id => $tag_name)
											{
												?>
												<div class="movie-tag" id="mt-<?php echo $tag_id ?>"><?php echo $tag_name ?> <span class="fa fa-times tag-remover" onclick="removeTag(<?php echo $tag_id ?>)"></span></div>
												<?php
											}
											?>
										</div>
										<div class="row" id="tag-input-section" style="display: none; margin-top: 10px">
											<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
												<?php
												$others_tags = $movie->get_others_movie_tag($active_user->getId());
												?>
												<input type="text" class="form-control" id="tag-input" placeholder="Tag...">
												<div class="tag-list" style="display: none">
													<ul id="tag-search-ul">

													</ul>
													<?php
													if (count($others_tags) > 0)
													{
														?>
														<span class="others-tags-delimiter">Others' tags</span>
														<ul id="tag-proposed-ul">
															<?php
															foreach ($others_tags as $tag_id => $tag_name)
															{
																?>
																<li tag-id='<?php echo $tag_id ?>'><?php echo $tag_name ?></li>
																<?php
															}
															?>
														</ul>
														<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
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
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="col-lg-12 col-md-9 col-sm-12 col-xs-12 row">
												<div class="details-section-header">
													In your <?php echo $collectionSingularis ?>
												</div>
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
																				if (!is_int($key))
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
																				if (!is_int($key))
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
									</div>
									<?php
								}
								?>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="details-section-header">
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															Movie Images 
															<span class="pull-right">
																<button class="btn btn-omi btn-sm" data-toggle="modal" data-target="#upload-image-modal">
																	<span class="fa fa-plus"></span> Upload
																</button>
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 movie-images">
													<?php
													$images = $movie->get_all_header_images();
													foreach ($images as $image)
													{
														?>
														<img src="<?php echo get_image_path() . $image ?>" class="miniature-hi" alt="<?php echo $movie->getTitle() ?>">
														<?php
													}
													?>
												</div>
											</div>
										</div>
										<div class="details-section-header">
											Short Plot
										</div>
										<p>
											<?php echo $movie->getPlot() ?>
										</p>
										<div class="details-section-header" id="full-plot-toggle" style="cursor: pointer">
											Full Plot (Can contain spoilers)
										</div>
										<p id="full-plot" style="display: none">
											<?php echo $data['Plot']; ?><br><br>
											<i><label class="label-title" id="hide-full-plot" style="color: lightgray; cursor: pointer; text-decoration: underline">Hide Full plot</label></i>	
										</p>
									</div>
								</div>
							</div>
							<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
								<div class="details-section-header">
									Cast
								</div>
								<div class="cast-list">
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
												<span class="label-title" title="<?php echo $role ?>"><?php echo $role ?></span><br>
											</div>
											<div class="clearfix"></div>
										</div>
										<?php
									}
									?>
								</div>
								<div class="details-section-header">
									Top Discussions
								</div>
								<div class="discussion-overview">
									<div style="background-color: #ccc; border: 1px solid; border-color: #0079b7; padding: 5px 10px; margin: 5px 0;">
										<div style="color: #0079b7;">
											<h4 style="margin: 0 auto; color: #0079b7;">Discussion Title</h4>
										</div>
										<div style="color: #2c4762;">
											<time>June 3rd, 16:21</time> <i>by</i> <a href="#">Username</a>
										</div>
										<div class="color: #000;">
											Lorem ipsum dolor sit...
										</div>
									</div>
									<div style="background-color: #ccc; border: 1px solid; border-color: #0079b7; padding: 5px 10px; margin: 5px 0;">
										<div style="color: #0079b7;">
											<h4 style="margin: 0 auto; color: #0079b7;">Discussion Title</h4>
										</div>
										<div style="color: #2c4762;">
											<time>June 3rd, 16:21</time> <i>by</i> <a href="#">Username</a>
										</div>
										<div class="color: #000;">
											Lorem ipsum dolor sit...
										</div>
									</div>
									<div style="background-color: #ccc; border: 1px solid; border-color: #0079b7; padding: 5px 10px; margin: 5px 0;">
										<div style="color: #0079b7;">
											<h4 style="margin: 0 auto; color: #0079b7;">Discussion Title</h4>
										</div>
										<div style="color: #2c4762;">
											<time>June 3rd, 16:21</time> <i>by</i> <a href="#">Username</a>
										</div>
										<div class="color: #000;">
											Lorem ipsum dolor sit...
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="upload-poster-modal" aria-labelledby="upload-poster-modal-label" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="upload-poster-modal-label">Upload new Poster</h4>
					</div>
					<form action="" method="post" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
									<input type="file" class="form-control" name="poster-file">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="input" class="btn btn-success" name="pfsub"><span class="fa fa-check"></span> Upload</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="upload-image-modal" aria-labelledby="upload-image-modal-label" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="upload-image-modal-label">Upload new Image</h4>
					</div>
					<form action="" method="post" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
									<input type="file" class="form-control" name="image-file">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="input" class="btn btn-success" name="ifsub"><span class="fa fa-check"></span> Upload</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="addToCollectionModal" aria-labelledby="addToCollectionModalLabel" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="addToCollectionModalLabel">Add <?php echo $origTitle ?> to Collection</h4>
					</div>
					<form action="" method="post">
						<div class="modal-body">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
								<h4 class="label-title pull-right">Add to:</h4>
							</div>
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
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-default" name="saveInCollection"><span class="fa fa-check fa-green"></span> Add to Collection</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-red"></span> Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="edit_movie_details_modal" aria-labelledby="edit_movie_details_modal_label" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close fa-omi-blue" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="edit_movie_details_modal_label">Edit Details</h4>
					</div>
					<form action="" method="post" name="emd_form">
						<div class="modal-body">

						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-default" name="edmosbm"><span class="fa fa-check fa-green"></span> Add to Collection</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-red"></span> Close</button>
						</div>
					</form>
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
			$("#tag-toggler").click(function () {
				$("#tag-input-section").slideToggle();
			});
			$("#tag-input").keyup(function (e) {
				if (e.keyCode == 13)
				{
					var Tag = $(this).val();
					$.getJSON('<?php echo $path ?>tag/saveTagForMovie.php', {mi: <?php echo $movie->getId() ?>, ui: <?php echo $active_user->getId() ?>, tn: $(this).val()}, function (response) {
						if (response['status'] == 1)
						{
							var TagId = response['tag_id'];
							$(".movie-tags").append("<div class='movie-tag' id='mt-" + TagId + "'>" + Tag + " <span class='fa fa-times tag-remover' onclick='removeTag(" + TagId + ")'></span></div>");
							$(".tag-list").slideUp();
							$("#tag-search-ul").html("");
							$("#tag-input").val("");
						}
						else
						{
							alert(response['error']);
						}
					});
				}
				else
				{
					var Text = $(this).val();
					if (Text.length == 0)
					{
						$(".tag-list").slideUp();
					}
					else
					{
						$("#tag-search-ul").html("");
						$.getJSON('<?php echo $path ?>tag/getTagSearchResults.php', {tn: Text}, function (response) {
							$.each(response, function (id, name) {
								$("#tag-search-ul").append("<li tag-id='" + id + "'>" + name + "</li>");
							});
						});
						$("#tag-search-ul").append("<li class='current-tag-typing'>" + $("#tag-input").val() + "</li>");
						$(".tag-list").slideDown();
					}
				}
			});
			$("#tag-input").focusout(function ()
			{
				$(".tag-list").slideUp()();
			});

			$(".mpa").mouseenter(function () {
				$(".edit-poster-area").fadeIn();
			});

			$(".mpa").mouseleave(function () {
				$(".edit-poster-area").fadeOut();
			});

			function removeTag(tagId)
			{
				$.getJSON('<?php echo $path ?>tag/removeTagFromMovie.php', {mi: <?php echo $movie->getId() ?>, ui: <?php echo $active_user->getId() ?>, ti: tagId}, function (response) {
					if (response['status'] == 1)
					{
						var theTagId = response['tag_id'];
						$("#mt-" + theTagId).fadeOut(250);
						setTimeout(function () {
							$("#mt-" + theTagId).remove();
						}, 1500);
					}
					else
					{
						alert(response['error']);
					}
				});
			}
		</script>
	</body>
</html>