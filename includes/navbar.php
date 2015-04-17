<?php
if(isset($_SESSION['user_id']))
{
	$signed_in = true;
}
else
{
	$signed_in = true;
}
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Online Movie Index</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="/onlineMovieIndex/">Home</a></li>
            </ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $active_user->getUsername(); ?> <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu" id="navbar-dropdown-menu">
						<li><a href="/onlineMovieIndex/user/logout.php">Logout</a></li>
					</ul>
				</li>
			</ul>
        </div><!--/.navbar-collapse -->
    </div>
</nav>