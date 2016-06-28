<br/>
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#createCollectionModal">
    <span class="fa fa-plus fa-green"></span> New Collection
</button>

<!-- Modal -->
<div class="modal fade" id="createCollectionModal" tabindex="-1" role="dialog" aria-labelledby="createCollectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="createCollectionModalLabel">Create Movie Collection</h4>
            </div>
			<form method="post" role="form" id="createCollectionForm" action="<?php echo $path ?>collection/createCollection.php">
				<div class="modal-body">
                    <!-- Name -->
                    <div class="form-group">
                        <label class="form-label-header" for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter name of collection">   
                    </div>
                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label-header" for="description">Description</label>
                        <input type="textarea" name="description" id="description" class="form-control" placeholder="Enter collection description(Optional)">
                    </div>

                    <!-- Privacy settings -->
                    <div class="form-group">
                        <label class="form-label-header" for="privacy-settings">Privacy Settings</label>
                        <br>
                        <label class="radio-inline">
                            <input type="radio" name="privacy-setting" value="1" checked>Private
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="privacy-setting" value="0">Public
                        </label>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="savebtn" name="savebtn" class="btn btn-default disabled"><span class="fa fa-check fa-omi-blue"></span> Save</button>
					<button type="reset" class="btn btn-default" data-dismiss="modal" aria-label="Close"><span class="fa fa-times fa-darkred"></span> Close</button>
				</div>
			</form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
    function validateCollectionName() {
        if ($("#name").val() === null || $("#name").val() === "") {
            var div = $("#name").closest("div");
            div.removeClass("has-success");
            $("#glyphName").remove();
            $("#infoName").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphName" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoName" class="alert alert-info" role="alert">Please enter a collection name!</div>');
			$("#savebtn").addClass("disabled");
            return false;
        } else if ($("#name").val().length < 2 || $("#name").val().length > 40) {
            var div = $("#name").closest("div");
            div.removeClass("has-success");
            $("#glyphName").remove();
            $("#infoName").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphName" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoName" class="alert alert-info" role="alert">Collection name must be between 2-40 characters.</div>');
			$("#savebtn").addClass("disabled");
            return false;
        } else {
            var div = $("#name").closest("div");
            div.removeClass("has-error");
            $("#infoName").remove();
            div.addClass("has-success has-feedback");
            $("#glyphName").remove();
            div.append('<span id="glyphName" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
			$("#savebtn").removeClass("disabled");
            return true;
        }
    }

    $(document).ready(function () {
        $("#name").focusout(function () {
            validateCollectionName();
        });
    }

    );
</script>







