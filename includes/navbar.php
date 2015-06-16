<!--
Author: R. Mike Jensen
-->
<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php
if (isset($_SESSION['user_id'])) {
    $signed_in = true;
} else {
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
            <a class="navbar-brand" href="<?php echo $path ?>"><img src="<?php echo $path ?>includes/img/omi-alpha.png" style="max-height:180%; margin-top: -7px;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo $path ?>">Home</a></li>
                <li><a href="<?php echo $path ?>movie/">Movies</a></li>
                <li><a href="<?php echo $path ?>collection/">Collections</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown"><?php echo $active_user->getUsername(); ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu" id="navbar-dropdown-menu">
                        <li><a href="<?php echo $path ?>user/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!--/.navbar-collapse -->
    </div>
</nav>