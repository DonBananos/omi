<!--
Author: Heini L. Ovason, Mike Jense
-->

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
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 collection-box">
			<a href="<?php echo $path ?>collection/<?php echo $collection->getId() ?>/<?php echo $collection->getSlug() ?>/" title="<?php echo $collection->getDescription(); ?>">
				<div class="collection" style="background: url(<?php echo $collection->get_image_name() ?>);">
					<div class="overlay"></div>
					<div class="collection-title">
						<h3>
							<?php echo $collection->getName() ?><br/>
						</h3>
						<small>
							<?php
							if ($collection->getPrivacy())
							{
								?>
								<span class="fa fa-lock fa-omi-blue" title="Private Collection"></span>
								<?php
							}
							else
							{
								?>
								<span class="fa fa-unlock fa-omi-blue" title="Public Collection"></span>
								<?php
							}
							?>
							Created on: <b><?php echo formatTextDate($collection->getCreatedDatetime()) ?> - <?php echo $numberOfMoviesInCollection ?> Movies</b>
						</small>
					</div>
				</div>
			</a>
		</div>
		<?php
	}
	//Require a collection_handler
	//Get that handler to select all collections
	//Display these within another require
	?>
</div>