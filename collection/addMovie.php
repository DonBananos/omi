<?php
session_start();
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../user/user.php';
require './collection.php';
require './collectionHandler.php';
require '../movie/movie.php';

$title = $_POST['title'];
$imdbId = $_POST['imdbId'];
$plot = $_POST['plot'];
$release = $_POST['release'];
$poster = $_POST['poster'];
$runtime = $_POST['runtime'];
$language = $_POST['language'];
$collectionId = $_GET['cid'];

$collection = new Collection($collectionId);

if ($_SESSION['signed_in'])
{
	$active_user = new User($_SESSION['user_id']);
	if ($active_user->getId() == $collection->getUserId())
	{
		$proceed = true;
	}
	else
	{
		$proceed = false;
	}
}
else
{
	$proceed = false;
}

if (!$proceed)
{
	?>
	<script>
		alert("You are not allowed to alter this collection!");
	</script>
	<?php
}
$movie = new Movie();
$answer = $movie->createMovie($title, $plot, $release, $runtime, $imdbId, $poster, $language);
if ($answer === true)
{
	$answer = $movie->saveMovieToCollection($collection->getId());
	if ($answer !== true)
	{
		?>
		<script>
			alert("ERROR: <?php echo $answer ?>");
		</script>
		<?php
		die();
	}
	?>
	<script>
		window.location = '<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/';
	</script>
	<?php
}
else
{
	?>
	<script>
		alert("Error: <?php echo $answer ?>");
	</script>
	<?php
	die();
}
