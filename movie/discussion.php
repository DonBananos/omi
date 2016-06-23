<?php
$movie_id = $_GET['id'];

session_start();

require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';
require '../movie/movie.php';

$movie = new Movie($movie_id);
$dont_show = FALSE;
if (isset($_SESSION['signed_in']))
{
	$active_user = new User($_SESSION['user_id']);
}
else
{
	$dont_show = TRUE;
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
<html lang="en">
	<head>
		<title><?php echo $movie->getTitle() ?> Discussion | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<?php
			if (!$dont_show)
			{
				require '../includes/navbar.php';
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
													<?php echo $title ?> | Discussion Board
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
											<?php echo $title ?> | Discussion Board
										</h1>
									</div>
									<?php
								}
								if ($dont_show)
								{
									?>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<h2>You need to be signed in, in order to view the movies discussions.</h2>
										</div>
									</div>
									<?php
									require_once '../includes/footer.php';
									die();
								}
								?>
								<div class="movie-options">
									<ul>
										<li><a href="<?php echo BASE_URL ?>movie/<?php echo $movie_id ?>/<?php echo $movie->getSlug() ?>/"><span class="fa fa-angle-double-left"></span> <?php echo $title ?></a></li>
									</ul>
									<ul class="pull-right">
										<li data-toggle="modal" data-target="#new-discussion-modal"><span class="fa fa-plus fa-green"></span> New Discussion</li>
									</ul>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="row">
											<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
												<div class="details-section-header">
													Latest Discussions
												</div>
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
												<div class="details-section-header">
													Top Discussions
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		require '../includes/footer.php';
		?>
		<!-- Modal -->
		<div class="modal fade" id="new-discussion-modal" aria-labelledby="new-discussion-modal-label">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="new-discussion-modal-label">New Discussion</h4>
					</div>
					<form action="" method="post">
						<div class="modal-body">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<input type="text" name="discussion-headline" class="form-control" placeholder="Discussion Headline">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<select class="form-control">
										<option>Public Discussion</option>
										<option>Private Discussion</option>
									</select>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<textarea class="form-control" rows="5" placeholder="Start the discussion.." style="margin-top: 10px;"></textarea>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" name="pesubmit"><span class="fa fa-check fa-green"></span> Save</button>
							<button type="reset" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-red"></span> Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>