<!--
Author: Heini L. Ovason, Mike Jense
-->

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
			<?php
			$collection = new Collection();
			$ch = new CollectionHandler();
			$cids = $ch->getAllCollectionIdsFromUser($active_user->getId());
			foreach ($cids as $cid)
			{
				$collection->setValuesAccordingToId($cid);
				$numberOfMoviesInCollection = count($collection->getAllMoviesInCollection());
				?>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-top: 20px;">
					<a href="<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/" title="<?php echo $collection->getDescription(); ?>">
						<div class="collection" style="position: relative; height: 200px; background-position: center !important; background-size: cover !important; background: url(http://cdn.home-designing.com/wp-content/uploads/2011/11/home-movie-collection.jpg);">
							<h3 style="width: 100%; padding-right: 10px; position: absolute; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); padding-left: 10px; padding-bottom: 5px; margin-bottom: 0; margin-left: 0;">
								<?php echo $collection->getName() ?><br/>
								<small style="color: white;">
									Created on: <b><?php echo formatTextDate($collection->getCreatedDatetime()) ?> - <?php echo $numberOfMoviesInCollection ?> Movies</b>
								</small>
							</h3>
						</div>
					</a>
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
