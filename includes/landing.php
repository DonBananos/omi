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
			</div>
		</section>
		<section id="login">
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
							<div class="text-center">
								<div class="input-group">
									<button type="submit" class="btn btn-omi btn-lg">
										<span class="fa fa-sign-in fa-fw"></span> Sign in
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
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