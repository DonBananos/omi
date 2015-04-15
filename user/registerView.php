<!--
User story ID: OMI_014
Author: Heini L. Ovason
-->

<form method="post" id="registerForm" role="form" action="./includes/config/register.php">

    <h2>Register</h2>

    <!-- Username -->
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username">   
    </div>

    <!-- Email -->
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
    </div>

    <!-- Password -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
    </div>

    <!-- Retype Password -->
    <div class="form-group">
        <label for="retypePassword">Retype Password</label>
        <input type="password" name="retypePassword" id="retypePassword" class="form-control" placeholder="Confirm Password">
    </div>

    <!-- Checkbox & Link to Terms of Condition-->
    <div class="form-group">
        <!-- Modal view of Terms & Conditions? -->
        <input type="checkbox" value="remember-me">
        <small>I accept <a href="#">Terms & Conditions</a></small>
    </div>

    <button type="button" id="registerBtn" class="btn btn-primary">Sign up</button>

</form>

<!-- Access the regex standards variables defined in config.php -->
<?php 
    require './includes/config/config.php'; 
?> 

<!-- 
jQuery (necessary for Bootstrap's JavaScript plugins)
To verify that backend registration validation is working simply commendable
commend out the script.
-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<!-- Javascript -->
<script type="text/javascript">
    /*
     * Inspirationskilde https://www.youtube.com/watch?v=t2oXpi61E4A
     * All 4 validation functions are inspired from above video, and its sequel.
     */

    function validateUsername() {
        if ($("#username").val() === null || $("#username").val() === "") {
            var div = $("#username").closest("div");
            div.removeClass("has-success");
            $("#glyphUsername").remove();
            $("#infoUsername").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphUsername" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoUsername" class="alert alert-info" role="alert">Please enter a username!</div>');
            return false;
        } else {
            if ($("#username").val().match(regexUsr)) {
                var div = $("#username").closest("div");
                div.removeClass("has-error");
                $("#infoUsername").remove();
                div.addClass("has-success has-feedback");
                $("#glyphUsername").remove();
                div.append('<span id="glyphUsername" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                return true;
            } else {
                var div = $("#username").closest("div");
                div.removeClass("has-success");
                $("#glyphUsername").remove();
                $("#infoUsername").remove();
                div.addClass("has-error has-feedback");
                div.append('<span id="glyphUsername" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                div.append('<div id="infoUsername" class="alert alert-info" role="alert">Enter a valid username between 6-40 characters. Only \'A-Z\', \'a-z\', \'0-9\' and \'-_\' are allowed.</div>');
                return false;
            }
        }
    }

    function validateEmail() {
        if ($("#email").val() === null || $("#email").val() === "") {
            var div = $("#email").closest("div");
            div.removeClass("has-success");
            $("#glyphEmail").remove();
            $("#infoEmail").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphEmail" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoEmail" class="alert alert-info" role="alert">Please enter your Email!</div>');
            return false;
        } else {
            if ($("#email").val().match(regexEm)) {
                var div = $("#email").closest("div");
                div.removeClass("has-error");
                $("#infoEmail").remove();
                div.addClass("has-success has-feedback");
                $("#glyphEmail").remove();
                div.append('<span id="glyphEmail" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                return true;
            } else {
                var div = $("#email").closest("div");
                div.removeClass("has-success");
                $("#glyphEmail").remove();
                $("#infoEmail").remove();
                div.addClass("has-error has-feedback");
                div.append('<span id="glyphEmail" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                div.append('<div id="infoEmail" class="alert alert-info" role="alert">Please enter a valid email address. Ex: John@doe.com</div>');
                return false;
            }
        }
    }
    function validatePassword() {
        if ($("#password").val() === null || $("#password").val() === "") {
            var div = $("#password").closest("div");
            div.removeClass("has-success");
            $("#glyphPassword").remove();
            $("#infoPassword").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphPassword" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoPassword" class="alert alert-info" role="alert">Please enter a Password!</div>');
            return false;
        } else {
            if ($("#password").val().match(regexPwd)) {
                var div = $("#password").closest("div");
                div.removeClass("has-error");
                $("#infoPassword").remove();
                div.addClass("has-success has-feedback");
                $("#glyphPassword").remove();
                div.append('<span id="glyphPassword" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                return true;
            } else {
                var div = $("#password").closest("div");
                div.removeClass("has-success");
                $("#glyphPassword").remove();
                $("#infoPassword").remove();
                div.addClass("has-error has-feedback");
                div.append('<span id="glyphPassword" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                div.append('<div id="infoPassword" class="alert alert-info" role="alert">Please enter a valid password.<br>At least 1 upper case character.<br>At least 1 lower case character.<br>At least 1 digit Only.<br>\'A-Z\', \'a-z\' or \'0-9\' as first character.<br>Between 8 and 40 characters of length.<br>Accepted special characters: !@#$%&</div>');
                return false;
            }
        }
    }

    function validateRetypedPassword() {
        if ($("#password").val() === null || $("#retypePassword").val() === "") {
            var div = $("#retypePassword").closest("div");
            div.removeClass("has-success");
            $("#glyphRetypePassword").remove();
            $("#infoRetypePassword").remove();
            div.addClass("has-error has-feedback");
            div.append('<span id="glyphRetypePassword" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
            div.append('<div id="infoRetypePassword" class="alert alert-info" role="alert">Please verify your password!</div>');
            return false;
        } else {
            if ($("#password").val() === $("#retypePassword").val()) {
                var div = $("#retypePassword").closest("div");
                div.removeClass("has-error");
                $("#infoRetypePassword").remove();
                div.addClass("has-success has-feedback");
                $("#glyphRetypePassword").remove();
                div.append('<span id="glyphRetypePassword" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                return true; 
            } else {
                var div = $("#retypePassword").closest("div");
                div.removeClass("has-success");
                $("#glyphRetypePassword").remove();
                $("#infoRetypePassword").remove();
                div.addClass("has-error has-feedback");
                div.append('<span id="glyphRetypePassword" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                div.append('<div id="infoRetypePassword" class="alert alert-info" role="alert">Password and retyped paswword do not match!</div>');
                return false;
            }
        }
    }

    $(document).ready(function () {
        regexUsr = "<?php echo $regexUsername ?>";
        regexEm = "<?php echo $regexEmail ?>";
        regexPwd = "<?php echo $regexPassword ?>";
        /*
         * Listening to fields based on related CSS selector ID's.
         * If focus is removed then the focusout() triggers a function
         * which calls the correct validation-function.
         */
        $("#username").focusout(function () {
            validateUsername();
        });

        $("#email").focusout(function () {
            validateEmail();
        });

        $("#password").focusout(function () {
            validatePassword();
        });

        $("#retypePassword").focusout(function () {
            validateRetypedPassword();
        });

        /*
         * Listening to form button based on related CSS selector ID.
         * Again we verify that input is correct before we are able
         * to perform form action.
         */
        $("#registerBtn").click(function () {
            var nrOfTestsPassed = 0;

            if (validateUsername()) {
                nrOfTestsPassed++;
            }
            if (validateEmail()) {
                nrOfTestsPassed++;
            }
            if (validatePassword()) {
                nrOfTestsPassed++;
            }
            if (validateRetypedPassword()) {
                nrOfTestsPassed++;
            }
            //console.log("nrOfTestsPassed:" + nrOfTestsPassed)
            if (nrOfTestsPassed === 4)
            {
                $("form#registerForm").submit();
            }
        });
    }

    );

</script>






