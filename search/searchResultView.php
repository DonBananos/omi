<?php
if (isset($data))
{
	foreach ($data as $result)
	{
		if (is_array($result))
		{
			foreach ($result as $movie)
			{
				$title = $movie['Title'];
				$year = $movie['Year'];
				$imdbId = $movie['imdbID'];
				$type = $movie['Type'];
				?>
				<div class="col-lg-3">
					<h4><?php echo $title ?></h4>
					<p>Year: <?php echo $year ?> - <?php echo $type ?></p>
					<a href="http://www.imdb.com/title/<?php echo $imdbId ?>/">IMDb</a>
					<div class="clearfix"></div>
				</div>
				<?php
			}
		}
	}
}