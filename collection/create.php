<?php

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
if(isset($_SESSION['signed_in']))
{
	//Save userId
	$userId = $_SESSION['user_id'];
	//Create the collection
	$answer = $collection->createCollection($name, $description, $private, $userId);
}
else
{
	?>
<script>
window.location = '<?php echo $path ?>';
</script>
	<?php
	die();
}

?>
<script>
	alert('<?php echo $answer; ?>');
window.location = '<?php echo $path ?>';
</script>
	<?php