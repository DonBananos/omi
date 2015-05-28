<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<div class="col-lg-6 center-block">
	<?php
	//$searchControllerPath = $path.'search/controller.php';
	//include ($searchControllerPath);
	?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMovieModal">
		Add Movie
	</button>

	<!-- Modal -->
	<div class="modal fade" id="addMovieModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Movie</h4>
				</div>
				<div class="modal-body">
					<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
						<input type="text" class="form-control" id="movieSearchInput">
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
						<button id="searchForMovieSubmit" class="btn btn-primary">Search <span class="fa fa-search"></span></button>
					</div>
					<div class="clearfix"></div>
					<div class="col-lg-12" id="movieSearchResultArea"style="padding: 20px;">
						<p id="movieSearchResultAreaHold">Please search for a movie</p>
						<p id="movieSearchResultAreaWait" style="display: none"><span class="fa fa-spinner fa-spin"></span> Loading results...</p>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script async>
				$("#searchForMovieSubmit").click(function() {
					$('#movieSearchResultAreaWait').show();
					$('#movieSearchResultAreaHold').hide();
					var SearchString = $("#movieSearchInput").val();
					$.get('<?php echo $path ?>search/searchResultView.php', {searchString: SearchString, cid: <?php echo $collection->getId(); ?> }, function (respons) {
						$('#movieSearchResultAreaWait').hide();
						$('#movieSearchResultArea').html(respons);
					});
		});
	</script>

	<?php
	//$searchResultPath = $path.'search/searchResultView.php';
	//include ($searchResultPath);
	?>
</div>