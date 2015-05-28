<!--
Author: Heini L. Ovason
-->

<div class="container">
    <div class="row"> 
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="page-header">
        <h1>Welcome Back <?php echo $active_user->getUsername() ?></h1>

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
        <?php //require './app/index.html'; ?>
        <?php 
		require './collection/collectionHandler.php';
		require './collection/collection.php';
		require './collection/collectionsView.php'; ?>
    </div>
</div>