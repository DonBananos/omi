<!--
User story ID: OMI_015
Author: Heini L. Ovason
-->

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo $path ?>"><img src="http://privat.mjsolutions.dk/includes/img/omi-alpha.png" style="max-height:180%; margin-top: -7px;"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <form class="navbar-form navbar-right" method="post" action="/onlineMovieIndex/user/login.php">
                <div class="form-group">
                    <input type="text" placeholder="Username" name="username" class="form-control" required autofocus>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Sign in</button>
            </form>
        </div><!--/.navbar-collapse -->
    </div>
</nav>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

