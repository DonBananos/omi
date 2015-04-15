<?php

// CONNECTING TO DB.
require("database.php");

$username = mysqli_real_escape_string($dbCon, $_POST['username']);
$email = mysqli_real_escape_string($dbCon, $_POST['email']);
$password = mysqli_real_escape_string($dbCon, $_POST['password']);
$retypedPassword = mysqli_real_escape_string($dbCon, $_POST['retypePassword']);

// VALIDATING REGISTRATION INPUT FIELDS.
require 'regValidate.php';
$regValidate = new regValidate($username, $email, $password, $retypedPassword);
if ($regValidate->validate() == FALSE) {
    // UPS! SOMETHIGNG IS NOT RIGHT.
    echo "<br><div class='container-fluid' style='max-width:350px;'><div class='alert alert-danger'>";
    $regValidate->printErrMsg();
    echo "</div></div>";
    unset($regValidate);
    exit();
} else {
    unset($regValidate);
    // ALL IS GOOD, SO WE CONTINUE.
    
    // CHECKING FOR CURRENT USERNAME IN DB.
    $query = $dbCon->query("SELECT * FROM user WHERE username='$username'");
    $numrows = mysqli_num_rows($query);
    if ($numrows == 0) {
        // CHECKING FOR CURRENT EMAIL IN DB.
        $query = $dbCon->query("SELECT * FROM user WHERE email='$email'");
        $numrows = mysqli_num_rows($query);
        if ($numrows == 0) {
            // SO EVERYTHING LOOKS JOLLY SO FAR.
            // LET US ENCRYPT THAT PASSWORD(COLLABORATION WITH CHRISTIAN BOECK DURING THE SEMESTER).
            // [algorithm][cost]$[22 digits from "./0-9A-Za-z" as salt].
            $algo = '$2y$14$';
            $rand = crypt($password . $username); // mere end 22 tegn.
            $salt = substr($rand, 0, 22); // vi vil ikke have mere end 22 tegn
            $crypt_password = crypt($password, $algo . $salt);

            $date = date("F d, Y");
            $activationcode = crypt($password . $username);
            echo $activationcode;
            $sanitizedStmt = mysqli_real_escape_string($dbCon, $_POST['username']);
            $dbCon->query("INSERT INTO user VALUES
					('', '$username', '$email', '$crypt_password', '$date', '0', '0')
								");

            /*
              require 'activationSend.php';
              $as = new activationSend($username, $activationcode, $email, $date);
              $as->send();

              $redirect_page = '../core/login.php';
              header('location: ' . $redirect_page); */
        } else {
            echo "<b>\"$email\"</b> is already registered to an account. <br>Please chooose a different email adress.<br>";
        }
    } else {
        echo "There is already a user registered as <b>\"$username\"</b>.<br>";
    }
    // CLOSING CONNECTION TO DB.
    $dbCon->close();
} /* END else -> if( $regValidation->validate() == FALSE ) */


