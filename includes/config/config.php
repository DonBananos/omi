<?php

$path = "/onlineMovieIndex/";
$url = "localhost/onlineMovieIndex/";

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
//  {8,40}              length at least 8 characters and maximum of 40	
//  )			End of group
$regexPassword = "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[@#$%_-]*.{8,40}$/";

//Email: onlu 'a-z', '0-9', a single '@' and a single '.' is allowed.
//$regexEmail = "^(?=^.{6,}$)(?=.*[a-z])(?=.*@)(?=.*\.)[0-9a-z@\.]*$";
//This regex goes a layer deeper and listens for something in the domain/subdomian section of the email address
$regexEmail = "^[a-zA-Z0-9_.+-]+@[a-z0-9A-Z]+\.[a-z0-9A-Z]*\.?[a-zA-Z]{2,}$";

$supportMail = "omiadmin@heibisoft.com";
/*
 * Date and Time Formats
 */

class Config
{

	public $shortDateFormat = 'd/m-y'; // 31/01-12
	public $fullDateFormat = 'd/m-Y'; // 31/01-2012
	public $textDateFormat = 'F jS, Y'; // January 31st 2012
	public $shortTimeFormat = 'H:i'; // 13:21
	public $fullTimeFormat = 'H:i:s'; // 13:21:53

	function __construct()
	{
		
	}

	function formatShortDate($date)
	{
		return date($this->shortDateFormat, strtotime($date));
	}

	function formatFullDate($date)
	{
		return date($this->fullDateFormat, strtotime($date));
	}
	
	function formatTextDate($date)
	{
		return date($this->textDateFormat, strtotime($date));
	}

	function formatShortTime($date)
	{
		return date($this->shortTimeFormat, strtotime($date));
	}
	
	function formatFullTime($date)
	{
		return date($this->$fullTimeFormat, strtotime($date));
	}
	
	function formatShortDateTime($date)
	{
		return $this->formatShortDate($date).' '.$this->formatShortTime($date);
	}
}
