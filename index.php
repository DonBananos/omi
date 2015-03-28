<?php
require './includes/config/config.php';

if(isset($_POST['search']))
{
	$title = preg_replace("/ /", '+', $_POST['search_field']);
	
	$json = file_get_contents("http://www.omdbapi.com/?t=$title&y=&plot=short&r=json&type=movie");
	
	$data = json_decode($json, true);
}
?>

<!DOCTYPE html>
<html>
    <head>
		<title>Online Movie Index</title>
		<?php
		require './includes/header.html';
		?>
    </head>
    <body>
		<?php
		require './includes/navbar.php';
		?>
		<div class="container page-wrap">
			<div id="col-lg-12">
            <div class="page-header">
                <h1>Welcome to Online Movie Index</h1>
            </div>
        </div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
				<div class="box">
					<h3>Filters</h3>
					<hr class="header-ender">
					<br>
					<br>
				</div>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-8">
				<div class="col-lg-12">
					<!-- This should be given an javascript function associated, so when a letter is typed, this should invoke a search which toggles images below for results -->
					<form action=" " method="post">
						<input type="text" class="form-control" placeholder="Enter keywords..." name="search_field">
						<input type="submit" name="search" style="display: none">
					</form>
				</div>
				<div class="clearfix"></div>
				<br>
				<div class="col-lg-12">
					<div class="box">
						<?php
						if(is_array($data))
						{
							foreach($data as $key=>$value)
							{
								if($key == 'Poster')
								{
									?><img src="<?php echo $value ?>"><?php
								}
								else
								{
									echo $key.': '.$value.'<br>';
								}
							}
						}


						// close cURL resource, and free up system resources
						?>
					</div>
				</div>
				<br>
				<div class="clearfix"></div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BMjAzOTM4MzEwNl5BMl5BanBnXkFtZTgwMzU1OTc1MDE@._V1_SX300.jpg">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BMTc1Njk1NTE3NF5BMl5BanBnXkFtZTgwNjAyMzcxMTE@._V1_SX300.jpg">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BMTY2OTE5MzQ3MV5BMl5BanBnXkFtZTgwMTY2NTYxMTE@._V1_SX300.jpg">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BMTMyMTM5OTMxNF5BMl5BanBnXkFtZTYwODcyNDY5._V1_SX300.jpg">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BMTQ2MzYwMzk5Ml5BMl5BanBnXkFtZTcwOTI4NzUyMw@@._V1_SX300.jpg">
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
					<img class="poster" src="http://ia.media-imdb.com/images/M/MV5BODU4MjU4NjIwNl5BMl5BanBnXkFtZTgwMDU2MjEyMDE@._V1_SX300.jpg">
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
			require './includes/footer.html';
			?>
		</div>
    </body>
</html>
