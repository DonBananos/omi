<!--
Author: Heini L. Ovason
-->

<div class="container">
    <div class="row"> 
		<div class="page-header">
			<h1>Welcome Back <?php echo $active_user->getUsername() ?></h1>
		</div>
		<?php
		require './collection/collectionHandler.php';
		require './collection/collection.php';
		require './collection/collectionsView.php';
		?>