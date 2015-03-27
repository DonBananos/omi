<?php
require './includes/config/config.php';
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
					<div class="box">
						<form action=" " method="post">
							<input type="text" class="form-control" placeholder="Enter keywords...">
						</form>
					</div>
				</div>
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
