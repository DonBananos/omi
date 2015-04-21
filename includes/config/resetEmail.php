<?php

/*
 * This file should be used when a user has forgotten his email
 */

require 'database.php';
require 'config.php';
require '../../user/user.php';

$from = 'omiadmin@heibosoft.com'; //flyt til config

function resetPassword() {

    if ($_POST['action'] === "resetPassword") {
        $email = mysqli_real_escape_string($database, $_POST['email']); //get email 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validate email address use validate from config
            $message = "Invalid email address, please enter a valid email address!";
        } else {
            $query = "SELECT recoverpassword FROM user where email='" . $email . "'";
            $result = mysqli_query($connection, $query);
            $username = mysqli_fetch_array($result); //get username from email.
        }
    }

    function makeRandomString() {
        if (strlen($username) > 5) {
            $encrypt1 = md5($username);
            $encrypt2 = md5(jazz50hands09210);
            $encrypt = $encrypt1 . $encrypt2;
        }
    }

    function makeEmail() {
        if() {
            $message = "A password reset link has been send to your e-mail account.";
            $subject = "Online Movie Index - Reset Password.";
            $from = 'omiadmin@heibosoft.com';
            $body = 'Hi, </b> ' . $username . '</b><br/> <br/>We have recieved a request to reset your OMI password<br><br>Click here to reset your password http://heibosoft.com/omi/reset.php?encrypt=' . $encrypt . '&action=resetPassword<br/> <br/>--<br>br.';
            $headers = "From: " . strip_tags($from) . "\r\n";
            $headers .= "Reply-To: " . strip_tags($from) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            mail($email, $subject, $body, $headers);
        } else {
            $message = "Email was not linked to an accountF";
        }
    }
    