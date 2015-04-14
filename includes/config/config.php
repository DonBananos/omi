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

/*
 * Date and Time Formats
 */

$shortDateFormat = 'd/m-y'; // 31/01-12
$fullDateFormat = 'd/m-Y'; // 31/01-2012
$textDateFormat = 'F jS, Y'; // January 31st 2012
$shortTimeFormat = 'H:i'; // 13:21
$fullTimeFormat = 'H:i:s'; // 13:21:53
$shortDateTimeFormat = $shortDateFormat.' '.$shortTimeFormat;
