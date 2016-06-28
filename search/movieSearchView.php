<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<div class="col-lg-6 center-block">
	<button type="button" class="btn btn-default" data-toggle="modal" data-target="#addMovieModal">
		<span class="fa fa-plus fa-omi-blue"></span><span class="hidden-xs"> Add Movie</span>
	</button>

	<!-- Modal -->
	<div class="modal fade modal-wide" id="addMovieModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Add Movie</h4>
				</div>
				<div class="modal-body modal-search">
					<div class="col-lg-11 col-md-10 col-sm-9 col-xs-7">
						<input type="text" class="form-control" id="movieSearchInput" placeholder="Movie Title">
					</div>
					<div class="col-lg-1 col-md-2 col-sm-3 col-xs-5">
						<button id="searchForMovieSubmit" class="btn btn-primary" style="margin: -1px;">Search <span class="fa fa-search"></span></button>
					</div>
					<div class="clearfix"></div>
					<hr>
					<p id="beforeLocalSearchText">Please enter search terms</p>
					<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" id="movieSearchResultArea" class="search-modal-box">
						<?php
						require '../search/searchResultListView.php';
						?>
					</div>
					<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12" id="movieListingArea" class="search-modal-box">
						<div id="MovieWaiting" style="display: none;">
							<p><span class="fa fa-spinner fa-pulse fa-4x" style="padding:35% 45%;"></span></p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>