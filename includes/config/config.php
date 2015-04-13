<?php

$path = "/onlineMovieIndex/";


/*
 * Regular Expressions for Register and Login
 */

//Username: Only 'A-Z', 'a-z', '0-9' and '-_'. 
//Only 'A-Z', 'a-z' or '0-9' as first character
//Between 6 and 40 characters of length
$regexUsername = "^[a-zA-Z0-9][a-zA-Z0-9_-]{5,39}$"; 

//Password: Only 'A-Z', 'a-z', '0-9' and '-_!#£$%&?'
//At least 1 upper case character
//At least 1 lower case character
//At least 1 digit
//Only 'A-Z', 'a-z' or '0-9' as first character
//Between 8 and 40 characters of length
//Accepted special characters: !@#$%&
$regexPassword = "^(?=^.{7,39}$)(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)[0-9a-zA-Z!@#$%&]*$";

//Email: onlu 'a-z', '0-9', a single '@' and a single '.' is allowed.
$regexEmail = "^(?=^.{6,}$)(?=.*[a-z])(?=.*@)(?=.*\.)[0-9a-z@\.]*$";

error_reporting(E_ALL ^ E_NOTICE); 

        if($_POST['register'])
        {
            // CONNECTING TO DB.
            require("../core/database.php");

            $username = mysqli_real_escape_string($dbCon, $_POST['username']);
            $email = mysqli_real_escape_string($dbCon, $_POST['email']);
            $password = mysqli_real_escape_string($dbCon, $_POST['password']);
            $retypedPassword = mysqli_real_escape_string($dbCon, $_POST['retypepassword']);

            // VALIDATING REGISTRATION INPUT FIELDS.
            require '../core/validation/regValidate.php';
            $regValidate = new regValidate($username, $email, $password, $retypedPassword);
            if( $regValidate->validate() == FALSE )
            {
                // UPS! SOMETHIGNG IS NOT RIGHT.
                echo "<br><div class=\"alert alert-danger\">";
                $regValidate->printErrMsg();
                echo "</div>";

                // CLOSING CONNECTION TO DB.
                $dbCon->close();
            }
            else
            {
                // ALL IS GOOD, SO WE CONTINUE.

                // CHECKING FOR CURRENT USERNAME IN DB.
                $query = $dbCon->query("SELECT * FROM users WHERE username='$username'");
                $numrows = mysqli_num_rows($query);
                if($rowCount == 0)
                {
                    // CHECKING FOR CURRENT EMAIL IN DB.
                    $query = $dbCon->query("SELECT * FROM users WHERE email='$email'");
                    $numrows = mysqli_num_rows($query);
                    if($numrows == 0)
                    {
                        // SO EVERYTHING LOOKS JOLLY SO FAR.
                        // LET US ENCRYPT THAT PASSWORD(COLLABORATION WITH CHRISTIAN BOECK DURING THE SEMESTER).
                        // [algorithm][cost]$[22 digits from "./0-9A-Za-z" as salt].
                        $algo = '$2y$14$';
                        $salt1 = crypt($password.$username);
                        $salt = substr($salt1, 0, 22);
                        $crypt_password = crypt($password, $algo.$salt);

                        $date = date("F d, Y");
                        $activationcode = crypt($password.$username);

                        $sanitizedStmt = mysqli_real_escape_string($dbCon, $_POST['username']);
                        $dbCon->query("INSERT INTO users VALUES
					('', '$username', '$crypt_password', '$email', '0', '$activationcode', '$date')
								");

                        require '../core/activationSendRegister.php';
                        $as = new activationSend($username, $activationcode, $email, $date);
                        $as->send();


                        echo "<h3> User registered!</h3>";
                        echo "<h3> A mail have been sent to your email address with an activation link!</h3>";
                        //$redirect_page = './core/mailSent.php';
                      //  header('location: ' . $redirect_page);

                    }
                    else
                    {
                        echo "<b>\"$email\"</b> is already registered to an account. Please chooose a different email adress.<br>";
                    }
                }
                else
                {
                    echo "There is already a user registered as <b>\"$username\"</b>.<br>";
                }
                // CLOSING CONNECTION TO DB.
                $dbCon->close();

            } /* END else -> if( $regValidation->validate() == FALSE ) */
        } /* END if($_POST['registerbtn']) */




?>
