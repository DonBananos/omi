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

$qualities = array("Not Set" => "Unknown", "Scr" => "Worst", "240p" => "Very Bad", "360p" => "Bad", "480p" => "Not Good", "Web" => "Web Rip", "DVD" => "DVD Disc", "BluRay" => "BluRay Disc", "720p" => "HD Ready", "1080p" => "Full HD", "3D" => "Overrated", "4K" => "Ultra HD");

$countryLanguages = array(
	"da" => "Denmark",
	"en-us" => "USA",
	"en-gb" => "UK",
	"es-ar" => "Argentina",
	"bg" => "Bulgaria",
	"pt-br" => "Brazil",
	"es-cl" => "Chile",
	"cs" => "Czech Republic",
	"de" => "Germany",
	"es" => "Spain",
	"fi" => "Finland",
	"fr" => "France",
	"el" => "Greece",
	"hr" => "Croatia",
	"hu" => "Hungary",
	"he" => "Israel",
	"it" => "Italy",
	"lt" => "Lithuania",
	"es-mx" => "Mexico",
	"es-pe" => "Peru",
	"pl" => "Poland",
	"pt" => "Portugal",
	"ro" => "Romania",
	"sr" => "Serbia",
	"tr" => "Turkey",
	"uk" => "Ukraine"
); 

function formatShortDate($date)
{
	// 31/01-12
	return date('d/m-y', strtotime($date));
}

function formatFullDate($date)
{
	// 31/01-2012
	return date('d/m-Y', strtotime($date));
}

function formatTextDate($date)
{
	// January 31st 2012
	return date('F jS, Y', strtotime($date));
}

function formatShortTime($date)
{
	// 13:21
	return date('H:i', strtotime($date));
}

function formatFullTime($date)
{
	// 13:21:53
	return date('H:i:s', strtotime($date));
}

function formatShortDateTime($date)
{
	return formatShortDate($date) . ' ' . formatShortTime($date);
}
