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
if(isset($_SESSION['signed_in']))
{
        //echo 'UserID: '. $_SESSION['user_id'];
	//Save userId
	$userId = $_SESSION['user_id'];
	//Create the collection
	$answer = $collection->createCollection($name, $desc, $priv, $userId);
	if($answer)
	{
		if($answer === true)
		{
			
		}
		else
		{
			?>
			<script>
				alert('<?php echo $answer; ?>');
			</script>
			<?php
		}
	}
	else
	{
		?>
		<script>
			alert('There was an error saving the Collection. Please try again.');
		</script>
		<?php
	}
}
?>
	<script>
	window.location = '<?php echo $path ?>';
	</script>


<div class="col-lg-3 col-md-4">
        <a href="#">
             <img src="http://ia.media-imdb.com/images/M/MV5BMjAzOTM4MzEwNl5BMl5BanBnXkFtZTgwMzU1OTc1MDE@._V1_SX300.jpg" class="thumbnail img-responsive">
        </a>
        </div>
        <div class="col-lg-3 col-md-4">
        <a href="#">
             <img src="http://ia.media-imdb.com/images/M/MV5BMTc1Njk1NTE3NF5BMl5BanBnXkFtZTgwNjAyMzcxMTE@._V1_SX300.jpg" class="thumbnail img-responsive">
        </a>
        </div>
        <div class="col-lg-3 col-md-4">
        <a href="#">
             <img src="http://ia.media-imdb.com/images/M/MV5BMjAzOTM4MzEwNl5BMl5BanBnXkFtZTgwMzU1OTc1MDE@._V1_SX300.jpg" class="thumbnail img-responsive">
        </a>
        </div>
        <div class="col-lg-3 col-md-4">
        <a href="#">
             <img src="http://ia.media-imdb.com/images/M/MV5BMTc1Njk1NTE3NF5BMl5BanBnXkFtZTgwNjAyMzcxMTE@._V1_SX300.jpg" class="thumbnail img-responsive">
        </a>
        </div>
        <div class="col-lg-3 col-md-4">
        <a href="#">
             <img src="http://ia.media-imdb.com/images/M/MV5BMjAzOTM4MzEwNl5BMl5BanBnXkFtZTgwMzU1OTc1MDE@._V1_SX300.jpg" class="thumbnail img-responsive">
        </a>
        </div>



