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
		<div class="container">
			<?php
			require './includes/navbar.php';
			echo '<h1>Online Movie Index (OMI)</h1>';
			echo 'Today is: <b>'.date('l jS F Y').'</b>'; //l : day of week, j = daynumber in month no 0 in front, S is ordinal suffix for date, F is month name, Y is year
			?>
		</div>
    </body>
</html>
