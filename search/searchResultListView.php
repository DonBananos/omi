<?php
/*
 * This file receives a search term from the search view, along with a 
 * collection id. Then it calls the intern search api, prints the results and
 * then calls the extern omdb api to search for movies not in the database.
 * The result from OMDBapi is then received, with duplications from the intern
 * search removed.
 * Each selection on the list envokes a search in the imdbphp api, which returns
 * an extensive result for the selected movie.
 */
?>
<div id="localResultArea">
	<p id="waitingLocalSearchText" style="display: none"><span class="fa fa-spinner fa-pulse fa-2x"></span> Loading results...</p>
</div>
<div id="externResultArea">
	<p id="waitingExternalSearchText" style="display: none"><span class="fa fa-spinner fa-pulse fa-2x"></span> Loading external results...</p>
</div>


<script async>
	//In order to make the search function work (with adding movies to collections,the $.get path should be changed to: search/searchResultView.php
	$("#searchForMovieSubmit").click(function () {
		$('#beforeLocalSearchText').hide();
		$('.movie-title-search-link').hide();
		$('#waitingLocalSearchText').show();
		var SearchString = $("#movieSearchInput").val();
		$.get('<?php echo $path ?>search/internDbSearchResultView.php', {s: SearchString, cid: <?php echo $collection->getId(); ?>}, function (respons) {
			$('#waitingLocalSearchText').hide();
			$('#localSearchIntro').show();
			$('#waitingExternalSearchText').show();
			$('#movieSearchResultArea').addClass('search-modal-box-list');
			$('#localResultArea').html(respons);
		});
		$.get('<?php echo $path ?>search/searchResultView.php', {s: SearchString, cid: <?php echo $collection->getId(); ?>}, function (respons) {
			$('#externSearchIntro').show();
			$('#waitingExternalSearchText').hide();
			$('#externResultArea').html(respons);
		});
	});
</script>