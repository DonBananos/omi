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
	require '../includes/navbar.php';
}
else
{
	$dont_show = TRUE;
}
?>
<html lang="en">
	<head>
		<title><?php echo $movie->getTitle() ?> Discussion | Online movie Index</title>
		<?php require '../includes/header.php'; ?>
	</head>
	<body>
		<div class="main-container">
			<div class="container">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="page-header">
						<h1><?php echo $movie->getTitle() ?> Discussion Board</h1>
					</div>
					<?php
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
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<span class="pull-right">
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#new-discussion-modal">
											<span class="fa fa-plus"></span> New Discussion
										</button>
									</span>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
									<h3>Top Discussions</h3>
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
							<button type="button" class="btn btn-success" name="pesubmit"><span class="fa fa-check"></span> Save</button>
							<button type="reset" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span class="fa fa-times"></span> Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>