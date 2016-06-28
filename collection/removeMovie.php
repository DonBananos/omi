<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

require '../movie/movie.php';

$collectionId = $_POST['collectionId'];
$collection = new Collection($collectionId);
$movieId = $_POST['removeMovieId'];
$ownCollection = $_POST['ownCollection'];

if (isset($_POST['remove']))
{
	if ($ownCollection)
	{
		$removeMovie = new Movie($movieId);
		$removeMovie->removeMovieFromCollection($collection->getId());
		?>
		<script>
			window.location = "<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/";
				</script>
		<?php
	}
	else
	{
		?>
		<script>
			alert("You are not allowed to remove movies from this collection");
		</script>
		<?php
	}
}