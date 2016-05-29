<?php

$path = "/onlineMovieIndex/";
$url = "localhost/onlineMovieIndex/";

define("IMAGE_PATH", "_uploads/_images/");
define("BASE_URL", $path);

/*
 * Cookie settings
 */
$domain = "localhost";
$cookie_path = "/omi";

/*
 * Defining the other stuff..
 */
define("ROOT_PATH", "C:/xampp/htdocs".$path);
define("IMAGE_LOCATION", "_uploads/_images/");
define("SERVER", "http://".$_SERVER['SERVER_NAME']);
define("BASE", $path);

/*
 * Accepted filetypes for image upload.
 */
$accepted_filetypes = array("jpg", "jpeg", "JPG", "png", "PNG", "gif", "GIF");
define("HEADER_MAX_WIDTH", 1200);
define("BACKGROUND_MAX_WIDTH", 2500);

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

function generateRandomString($least_number_of_characters, $max_number_of_characters)
{
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$number_of_characters = rand($least_number_of_characters, $max_number_of_characters);
	$random_string = "";
	for ($i = 0; $i < $number_of_characters; $i++)
	{
		$random_string .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $random_string;
}

function upload_image($max_width, $file_input_name)
{
	$path = '';
	$upload_directory = ROOT_PATH . IMAGE_LOCATION;
	$uploaded_url = SERVER . BASE;

	$image_path = $path;
	$upload_directory .= $image_path;
	if (!file_exists($upload_directory))
	{
		mkdir($upload_directory, 0777, true);
	}
	$image_name = get_free_image_name($upload_directory, getImageType($_FILES[$file_input_name]['name']));

	$upload_file = $upload_directory . $image_name;
	$image_path .= IMAGE_LOCATION . $image_name;
	$uploaded_url .= $image_path;

	$file_type = trim(getImageType($_FILES[$file_input_name]['name']));
	global $accepted_filetypes;
	if (!in_array($file_type, $accepted_filetypes))
	{
		return FALSE;
	}

	if ($_FILES[$file_input_name]['size'] > 20000000)
	{
		?>
		<script>alert("The file you're trying to upload is too large. Max size is 20mb");</script>
		<?php
		return FALSE;
	}
	if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_file))
	{
		if (substr(strrchr($upload_file, "."), 1) == 'jpg' OR substr(strrchr($upload_file, "."), 1) == 'jpeg' OR substr(strrchr($upload_file, "."), 1) == 'JPG')
		{
			$image = imagecreatefromjpeg($upload_file);
		}
		elseif (substr(strrchr($upload_file, "."), 1) == 'png' OR substr(strrchr($upload_file, "."), 1) == 'PNG')
		{
			$image = imagecreatefrompng($upload_file);
		}
		elseif (substr(strrchr($upload_file, "."), 1) == 'gif' OR substr(strrchr($upload_file, "."), 1) == 'GIF')
		{
			$image = imagecreatefromgif($upload_file);
		}
		else
		{
			return FALSE;
		}
		//Check for image resizing
		list($width, $height) = getimagesize($upload_file);
		if ($width > $max_width)
		{
			$new_width = $max_width;
			$new_height = $height / $width * $new_width;

			$tmp = imagecreatetruecolor($new_width, $new_height);

			imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			if (substr(strrchr($upload_file, "."), 1) == 'jpg' OR substr(strrchr($upload_file, "."), 1) == 'jpeg' OR substr(strrchr($upload_file, "."), 1) == 'JPG')
			{
				$exif = exif_read_data($upload_file);
				if (!empty($exif['Orientation']))
				{
					switch ($exif['Orientation'])
					{
						case 8:
							$tmp = imagerotate($tmp, 90, 0);
							break;
						case 3:
							$tmp = imagerotate($tmp, 180, 0);
							break;
						case 6:
							$tmp = imagerotate($tmp, -90, 0);
							break;
					}
				}
			}
			if (substr(strrchr($upload_file, "."), 1) == 'jpg' OR substr(strrchr($upload_file, "."), 1) == 'jpeg' OR substr(strrchr($upload_file, "."), 1) == 'JPG')
			{
				imagejpeg($tmp, $upload_file, 100);
			}
			elseif (substr(strrchr($upload_file, "."), 1) == 'png' OR substr(strrchr($upload_file, "."), 1) == 'PNG')
			{
				imagepng($tmp, $upload_file, 0);
			}
			elseif (substr(strrchr($upload_file, "."), 1) == 'gif' OR substr(strrchr($upload_file, "."), 1) == 'GIF')
			{
				imagegif($tmp, $upload_file, 100);
			}
			imagedestroy($tmp);
		}
		else
		{
			$tmp = imagecreatetruecolor($width, $height);

			imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $width, $height);

			if (substr(strrchr($upload_file, "."), 1) == 'jpg' OR substr(strrchr($upload_file, "."), 1) == 'jpeg' OR substr(strrchr($upload_file, "."), 1) == 'JPG')
			{
				$exif = exif_read_data($upload_file);
				if (!empty($exif['Orientation']))
				{
					switch ($exif['Orientation'])
					{
						case 8:
							$tmp = imagerotate($tmp, 90, 0);
							break;
						case 3:
							$tmp = imagerotate($tmp, 180, 0);
							break;
						case 6:
							$tmp = imagerotate($tmp, -90, 0);
							break;
					}
				}
			}
			if (substr(strrchr($upload_file, "."), 1) == 'jpg' OR substr(strrchr($upload_file, "."), 1) == 'jpeg' OR substr(strrchr($upload_file, "."), 1) == 'JPG')
			{
				imagejpeg($tmp, $upload_file, 100);
			}
			elseif (substr(strrchr($upload_file, "."), 1) == 'png' OR substr(strrchr($upload_file, "."), 1) == 'PNG')
			{
				imagepng($tmp, $upload_file, 0);
			}
			elseif (substr(strrchr($upload_file, "."), 1) == 'gif' OR substr(strrchr($upload_file, "."), 1) == 'GIF')
			{
				imagegif($tmp, $upload_file, 100);
			}
			imagedestroy($tmp);
		}

		imagedestroy($image);
		return $image_name;
	}
	return false;
}

function get_free_image_name($folder, $extension)
{
	$name_exists = TRUE;

	while ($name_exists === TRUE)
	{
		$name = generateRandomString(40, 50);

		if (!file_exists($folder . $name . '.' . $extension))
		{
			$name_exists = FALSE;
			return $name . '.' . $extension;
		}
	}
}

function getImageType($imageName)
{
	$extension = substr($imageName, strpos($imageName, '.') + 1);
	return $extension;
}

function get_image_path()
{
	return "http://".$_SERVER['SERVER_NAME'].BASE_URL.IMAGE_PATH;
}