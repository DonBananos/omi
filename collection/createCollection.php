<?php
session_start();
require './collection.php';
require '../includes/config/config.php';
require '../includes/config/database.php';

//Get the values from the form
$name = $_POST['name'];
$desc = $_POST['description'];
$priv = $_POST['privacy-setting'];

//Instantiate new Collection object
$collection = new Collection();

//Check if user is signed in
if (isset($_SESSION['signed_in']))
{
	//echo 'UserID: '. $_SESSION['user_id'];
	//Save userId
	$userId = $_SESSION['user_id'];
	//Create the collection
	$answer = $collection->createCollection($name, $desc, $priv, $userId);
	if ($answer)
	{
		if ($answer === true)
		{
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
				alert('<?php echo $answer; ?>');
				window.location = '<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/';
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>
			alert('There was an error saving the Collection. Please try again.');
			window.location = '<?php echo $path ?>collection';
		</script>
		<?php
	}
}
die();
