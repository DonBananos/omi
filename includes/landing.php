<body style="background-color: #000 !important; background-image: none;">
	<div id="full-front">
		<section id="front-first" style="background-image: url(<?php echo SERVER . BASE ?>includes/img/background/mt.jpg);">
			<div class="overlay">
				<div class="container">
					<div id="front-headline" class="text-center">
						<img src="includes/img/omi-alpha.png" style="height: 90px;">
						<h1>Welcome to OMI</h1>
						<p class="lead front-lead">Your Online Movie Index</p>
					</div>
					<hr class="front-hr">
					<form method="POST">
						<div class="row">
							<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
								<div class="intro-text text-center">
									<h3>Get started right away!</h3>
									<p class="front-lead">Type the name of your first movie collection</p>
								</div>
								<div class="input-group input-group-lg">
									<input type="text" name="omiftcolnm" class="form-control lg-omi-input omi-front-input" required="required" autocomplete="off" placeholder="Name of Collection">
									<span class="input-group-btn">
										<button type="submit" name="omiftcolsm" class="btn btn-omi omi-front-btn">&Gt;</button>
									</span>
								</div>
							</div>
						</div>
					</form>
					<div class="text-center" id="front-existing-user-area">
						<a href="#login">I already have a user</a>
					</div>
				</div>
			</div>
		</section>
		<section id="front-about" style="height: 550px;">
			<div class="container">
				<h2>What is OMI?</h2>
				<hr class="front-hr">
				<br/>
				<br/>
				<br/>
				<div class="row text-center">
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
						<div class="fa fa-list fa-4x"></div><br/>
						<small class="lead">Keep track of all your movies</small>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
						<div class="fa fa-search fa-4x"></div><br/>
						<small class="lead">Find just the movie you're looking for</small>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
						<div class="fa fa-users fa-4x"></div><br/>
						<small class="lead">Use OMI with your friends</small>
					</div>
					<div class="col-lg-3 col-md-3 hidden-sm col-xs-6">
						<div class="fa fa-share fa-4x"></div><br/>
						<small class="lead">Share public collections and lists</small>
					</div>
				</div>
			</div>
		</section>
		<section id="login">
			<div class="overlay">
				<div class="container">
					<form method="POST" action="<?php echo $path ?>user/login.php">
						<div class="row">
							<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
								<div class="intro-text text-center">
									<h3>Login to OMI</h3>
									<p class="front-lead">And control your movie colletions anytime, anywhere!</p>
								</div>
								<div class="input-group">
									<span class="input-group-addon" id="usrnm-login"><span class="fa fa-user fa-fw"></span></span>
									<input type="text" name="username" class="form-control" placeholder="Username" describedby="usrnm-login">
								</div>
								<div class="input-group">
									<span class="input-group-addon" id="pwd-login"><span class="fa fa-key fa-fw"></span></span>
									<input type="password" name="password" class="form-control" placeholder="Password" describedby="pwd-login">
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<button type="submit" class="btn btn-omi form-control">
										<span class="fa fa-sign-in fa-fw fa-white"></span> Sign in
									</button>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<a href="#register">
										<button type="button" class="btn btn-omi form-control">
											<span class="fa fa-user-plus fa-fw fa-white"></span> Register
										</button>
									</a>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
		<section id="news">
			<div class="container">
				<div class="row">
					<h2>OMI News</h2>
					<hr class="front-hr">
					<div class="col-lg-8 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-9 col-xs-12">
						<article class="news-story">
							<h3 class="news-title">Lorem Ipsum Dolor...</h3>
							<time class="news-time">2016-04-11</time>
							<p class="news-content">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
							</p>
						</article>
						<article class="news-story">
							<h3 class="news-title">Lorem Ipsum Dolor...</h3>
							<time class="news-time">2016-04-11</time>
							<p class="news-content">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
							</p>
						</article>
						<article class="news-story">
							<h3 class="news-title">Lorem Ipsum Dolor...</h3>
							<time class="news-time">2016-04-11</time>
							<p class="news-content">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
							</p>
						</article>
					</div>
				</div>
			</div>
		</section>
		<section id="img-quotes">
			<div class="overlay">
				<div class="container">
					<div class="text-center text-capitalize">
						<h3>
							"It's funny how the colors of the real world only seem really real when you watch them on a screen."
						</h3>
						<p class="lead"> - Anthony Burgess, A Clockwork Orange</p>
					</div>
				</div>
			</div>
		</section>
		<?php
		require_once './includes/footer.php';
		?>
	</div>
	<script>
		$(document).ready(function () {
			$("#full-front").fadeIn(1700);
		});
	</script>
</body>