<!--
Author: Heini L. Ovason
-->

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Create Movie Collection
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Movie Collection</h4>
            </div>
            <div class="modal-body">
                
                <form method="post" role="form" id="createCollectionForm" action="<?php echo $path ?>collection/createCollection.php">

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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
                <button type="button" id="savebtn" name="savebtn" class="btn btn-primary">Save</button>
            </div>
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
            return false;
        } else if ($("#name").val().length < 2) {
            var div = $("#name").closest("div");
            div.removeClass("has-success");
            $("#glyphName").remove();
            $("#infoName").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphName" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoName" class="alert alert-info" role="alert">Colection name must be between 2-40 characters.</div>');
            return false;
        } else {
            var div = $("#name").closest("div");
            div.removeClass("has-error");
            $("#infoName").remove();
            div.addClass("has-success has-feedback");
            $("#glyphName").remove();
            div.append('<span id="glyphName" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
            return true;
        }
    }

    $(document).ready(function () {

        /*
         * Listening to fieldd based on related CSS selector ID's.
         * If focus is removed then the focusout() triggers a function
         * which calls the correct validation-function.
         */
        $("#name").focusout(function () {
            validateCollectionName();
        });

        /*
         * Listening to form button based on related CSS selector ID.
         * Again we verify that input is correct before we are able
         * to perform form action.
         */
        $("#savebtn").click(function () {
            if (validateCollectionName())
            {
                // ### TEST ### Console print form input values.
                console.log("Name: " + $("#name").val() + ", " + "Description: " + $("#description").val() + ", " + "Privacy settings: " + jQuery('input[name=privacy-setting]:checked').val());

                $("form#createCollectionForm").submit();
            }
        });
    }

    );
</script>







