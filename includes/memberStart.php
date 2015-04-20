<!--
Author: Heini L. Ovason
-->

<div class="container">
    <div class="row">

        <h1>Welcome Back <?php echo $active_user->getUsername() ?></h1>

        <?php require './collection/createCollectionView.php'; ?>
        

    </div>
</div>