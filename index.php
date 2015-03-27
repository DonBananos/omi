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
			<?php
			echo '<small>Today is: <b>'.date('l jS F Y').'</b></small>'; //l : day of week, j = daynumber in month no 0 in front, S is ordinal suffix for date, F is month name, Y is year
			?>
		</div>
    </body>
</html>
