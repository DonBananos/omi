<!--
Author: Heini L. Ovason, Mike Jense
-->

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="page-header">
                <h1>Your Collections</h1>
            </div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?php require 'createCollectionView.php'; ?>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php
				$collection = new Collection();
				$ch = new CollectionHandler();
				$cids = $ch->getAllCollectionIdsFromUser($active_user->getId());
				foreach ($cids as $cid)
				{
					$collection->setValuesAccordingToId($cid);
					?>
					<div class="col-lg-4" style="height: 200px;">
						<a href="<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/">
							<h3>
								<?php echo $collection->getName() ?>
							</h3>
						</a>
						<hr>
						<p>
							<?php echo $collection->getDescription() ?>
						</p>
						<small>
							Created on: <b><?php echo formatTextDate($collection->getCreatedDatetime()) ?></b>
						</small>
					</div>
					<div class="clearfix">
						<?php
					}
					//Require a collection_handler
					//Get that handler to select all collections
					//Display these within another require
					?>
				</div>
			</div>
		</div> <!-- END container -->
