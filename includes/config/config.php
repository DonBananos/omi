<?php

$path = "/onlineMovieIndex/";


/*
 * Regular Expressions for Register and Login
 */

//Username: Only 'A-Z', 'a-z', '0-9' and '-_'. 
//Only 'A-Z', 'a-z' or '0-9' as first character
//Between 6 and 40 characters of length
$regexUsername = "^[a-zA-Z0-9][a-zA-Z0-9_-]{5,39}$"; 

//  KILDE: http://www.mkyong.com/regular-expressions/how-to-validate-password-with-regular-expression/
//  (                   Start of group      
//  (?=.*\d)		must contains one digit from 0-9
//  (?=.*[a-z])		must contains one lowercase characters
//  (?=.*[A-Z])		must contains one uppercase characters
//  (?=.*[@#$%])	must contains one special symbols in the list "@#$%_-"
//  .                   match anything with previous condition checking
//  {6,20}              length at least 6 characters and maximum of 20	
//  )			End of group
$regexPassword = "((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%_-]).{6,20})";

//Email: onlu 'a-z', '0-9', a single '@' and a single '.' is allowed.
$regexEmail = "^(?=^.{6,}$)(?=.*[a-z])(?=.*@)(?=.*\.)[0-9a-z@\.]*$";

