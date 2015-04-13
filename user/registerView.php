<!--
Task ID: OMI_014
Author: Heini L. Ovason
-->

<!-- 
### Overview of Javascript validation functionality ###
When keys are released in the different fields, a related validation-function 
is triggered, which the updates the content of its related HTML errorMsg element.
-->

<div class="container">

    <div class="row">
        <div class="col-md-4">
            <form role="form" action="./includes/config/register.php" method="POST" >

                <h2>Register</h2>

                <hr>

                <!-- Username -->
                <div class="form-group">
                    <input type="text" onkeyup="usernameLength()" name="display_name" id="display_name" class="form-control input-lg" placeholder="Username" required>
                </div>
                <div id="display_name_errorMsg"></div>

                <!-- Email -->
                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email Address" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input type="password" onkeyup="passwordLength()" name="password" id="password" class="form-control input-lg" placeholder="Password" required>
                </div>
                <div id="password_errorMsg"></div>

                <!-- Retype Password -->
                <div class="form-group">
                    <input type="password" onkeyup="comparePasswords()" name="password_confirmation" id="password_confirmation" class="form-control input-lg" placeholder="Confirm Password" required>
                </div>
                <div id="password_confirmation_errorMsg"></div>

                <hr>

                <!-- Checkbox & Link to Terms of Condition-->
                <div class="form-group">
                    <!-- Modal view of Terms & Conditions? -->
                    <input type="checkbox" value="remember-me" required>
                    <small>I accept <a href="#">Terms & Conditions</a></small>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <button type="submit" class="btn btn-primary">Sign up</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    /*
     * Checking #display_name length to be at least 1 char, and at most 20 chars. 
     * */
    function usernameLength() {
        var username = document.getElementById("display_name").value;
        var errMsg = "";
        if (!(username.length > 1)) {
            errMsg = "Username min. 2 characters!";
        } else if (!(username.length < 20)) {
            errMsg = "Username max. 20 characters!";
        } else {
            // Do nothing
        }
        document.getElementById("display_name_errorMsg").innerHTML = "<p>" + errMsg + "</p>";
    }

    /*
     * Checking #password length to be at least 6 char, and at most 30 chars. 
     * */
    function passwordLength() {
        var password = document.getElementById("password").value;
        var errMsg = "";
        if (!(password.length > 5)) {
            errMsg = "Password min. 6 characters!";
        } else if (password.length >= 30) {
            errMsg = "Password max. 30 characters!";
        } else {
            // Do nothing
        }
        document.getElementById("password_errorMsg").innerHTML = "<p>" + errMsg + "</p>";
    }

    /*
     * If #password_confirmation.length is equal to, or larger than
     * #password.length, then we start comparing the two element values 
     * */
    function comparePasswords() {
        var pwd = document.getElementById("password").value;
        var pwdConf = document.getElementById("password_confirmation").value;
        var errMsg = "";
        if (pwdConf === pwd) {
            // Do nothing
        } else {
            errMsg = "Passwords dont match!";
        }
        document.getElementById("password_confirmation_errorMsg").innerHTML = "<p>" + errMsg + "</p>";
    }



</script>




<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

