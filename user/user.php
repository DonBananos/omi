<?php

/* 
 * The User Object is used when needing a single user.
 * This object is only for getting and setting User Values.
 * 
 * @author Mike Jensen < mj@mjsolutions.dk >
 */

class User
{
	private $id; //Integer
	private $username; //String
	private $email; //String
	private $hashedPassword; //String
	private $created; //Datetime
	private $active; //Boolean
	private $roleId; //Integer
	private $role; //String
	private $activationCode; //String
	
	function __construct($id = null) //set to null if id is not in constructor
	{
		if(!empty($id))
		{
			$this->id = $id;
		}
	}
	
	public function setValuesAccordingToId($id = null)
	{
		/*
		 * This function selects all values from the user table associated with
		 * the specific user id given as parameter. These values are returned as
		 * resultset, and set to their given attributes in the object.
		 * Function can be called from outside the object, since collections of
		 * users will be using this object to create an instance of each user,
		 * by using the same object, but changing all attributes.
		 */
		if(empty($id))
		{
			$id = $this->id;
		}
		
		$sql = "SELECT username, user_email, user_password, user_created, user_active, user_role_id, user_activation_code FROM user WHERE user_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if($stmt === false){
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $id); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($username, $email, $password, $created, $active, $roleId, $activationCode); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if($stmt->num_rows == 1){
			$this->setId($id);
			$this->setUsername($username);
			$this->setEmail($email);
			$this->setPassword($password);
			$this->setCreated($created);
			$this->setActive($active);
			$this->setRoleId($roleId);
			$this->setRole();
			$this->setActivationCode($activationCode);
		}return true;
	}
	
	/*
	 * If you don't understand this function, you probably shouldn't be 
	 * looking in the Source Code. Try mobbing floors instead.!
	 */
	public function createUser($username, $email, $password)
	{
		$inUse = $this->checkIfValuesAreInUse($username, $email);
		if($inUse != false)
		{
			return $inUse;
		}
		$hashedPass = $this->hashPass($password);
		
		$this->generateActivationCode();
		
		return $this->saveCreatedUser();
	}
	
	private function checkIfValuesAreInUse($username, $email)
	{
		$errors = 0;
		if(!$this->checkIfValueExists('username', $username)) //Check if username is in use
		{
			$error = 'Username';
			$errors++;
		}
		if(!$this->checkIfValueExists('email', $email)) //Check if email is in use
		{
			if($errors == 1)
			{
				$error .= ' and ';
			}
			$error .= 'Email';
			$errors++;
		}
		if($errors > 0) //If email and/or username is in use
		{
			$error .= ' is already in use';
			return $error;
		}
		$this->username = $username;
		$this->email = $email;
		return false;
	}
	
	private function checkIfValueExists($whatToCheck, $valueToCheck)
	{
		if($whatToCheck == 'username'){
			$sql = "SELECT COUNT(*) as users FROM user WHERE username = ?";
		}
		elseif($whatToCheck == 'email'){
			$sql = "SELECT COUNT(*) as users FROM user WHERE user_email = ?";
		}
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if($stmt === false){
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('s', $valueToCheck); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($users); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if($users > 0){
			return false;
		}return true;
	}
	
	private function hashPass($password)
	{
		//Some najz hashing...
		$hashedPass = hash_hmac('sha512', $password, $this->getSalt($this->username, $password));
		return $hashedPass;
	}
	
	private function getSalt($username, $password)
	{
		//Her skal Boecks smarte salt generator ind!
		$salt = 'salt123456789';
		return $salt;
	}
	
	private function generateActivationCode()
	{
		$initial = true; //Initial value is true, so while loop runs first time
		$existing = $initial; //Setting existing to initial value
		while($existing == true)
		{
			//Generate actiationCcode between 40 and 80 characters
			$activationCode = $this->generateRandomString(40, 80);
			if($this->checkIfActivationCodeIsExisting($activationCode))
			{
				$existing = true;
			}
			else
			{
				$existing = false;
			}
		}
		$this->activationCode = $activationCode;
	}
	
	private function generateRandomString($least_number_of_characters, $max_number_of_characters)
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
	
	private function checkIfActivationCodeIsExisting($activationCode)
	{
		$sql = "SELECT COUNT(*) as codes FROM user WHERE user_activation_code = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if($stmt === false){
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('s', $activationCode); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($codes); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if($codes > 0){
			return true;
		}return false;
	}
	
	private function saveCreatedUser()
	{
		//Create SQL Query
		$sql = "INSERT INTO user (username, user_email, user_password, user_created, user_active, user_role_id, user_activation_code) VALUES (?, ?, ?, NOW(), 1, 1, ?)";
		
		//Prepare Statement
		$stmt = $dbCon->prepare($sql);
		if($stmt === false)
		{
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		
		//Bind parameters.
		$stmt->bind_param('ssss', $this->username, $this->email, $this->hashedPassword, $this->activationCode);
		
		//Execute
		$stmt->execute();
		
		//Get ID of user just saved
		$id = $stmt->insert_id;
		
		$stmt->close();
		if($id > 0)
		{
			$this->id = $id;
			return true;
		}
		return false;
	}
	
	private function sendVerificationEmail()
	{
		/*
		 * This function sends the email needed for activation of account.
		 */
		$mail = $this->generateVerificationEmail();
		if(mail($mail['to'], $mail['subject'], $mail['message'], $mail['headers']))
		{
			return true;
		}
		return false;
	}
	
	private function generateVerificationEmail()
	{
		/*
		 * This function generated an array of all data to be send in e-mail for
		 * activation of account.
		 */
		//Array with all mail data
		$mail = array();
		
		//Receiver of email
		$mail['to'] = $this->email; 
			
		//Subject of email
		$mail['subject'] = 'Activate your OMI account';
		
		//Message of the email
		$mail['message'] = $this->generateVerificationEmailView();
		
		//Headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: Online Movie Index <omi@mjsolutions.dk>' . "\r\n";
		
		$mail['headers'] = $headers;
		
		return $mail;
	}
	
	private function generateVerificationEmailView()
	{
		/*
		 * This function creates the View to send to the user when activation is
		 * needed.
		 */
		
		//REMEMBER TO CHANGE LINKS!!!!! <---------------------------- ¤¤¤¤¤¤¤¤¤¤
		$data = 
				'
					Can\'t see this e-mail? No problem, activate your account <a href="http://www.omi.mjsolutions.dk/activate/'.$this->id.'/'.$this->activationCode.'/">here</a>!
					<html>
						<body>
							<style>
								body
								{
									background-image: url(/onlineMovieIndex/includes/img/background/hexabump_@2X.png); //Set in link to background image when online
								}
								.wrapper
								{
									max-width: 835px;
									width: 80%;
									margin: 0 auto;
									margin-top: 15px;
								}
								h1
								{
									color: #99ccff;
								}
								h3
								{
									color: #ccc;
								}
								.btn
								{
									display: inline-block;
									padding: 6px 12px;
									margin-bottom: 0;
									font-size: 14px;
									font-weight: normal;
									line-height: 1.42857143;
									text-align: center;
									white-space: nowrap;
									vertical-align: middle;
									-ms-touch-action: manipulation;
										touch-action: manipulation;
									cursor: pointer;
									-webkit-user-select: none;
									   -moz-user-select: none;
										-ms-user-select: none;
											user-select: none;
									background-image: none;
									border: 1px solid transparent;
									text-decoration: none;
								}
								.btn:focus,
								.btn:active:focus,
								.btn.active:focus,
								.btn.focus,
								.btn:active.focus,
								.btn.active.focus 
								{
									outline: thin dotted;
									outline: 5px auto -webkit-focus-ring-color;
									outline-offset: -2px;
								}
								.btn:hover,
								.btn:focus,
								.btn.focus 
								{
									color: #333;
									text-decoration: none;
								}
								.btn:active,
								.btn.active 
								{
									background-image: none;
									outline: 0;
									-webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
											box-shadow: inset 0 3px 5px rgba(0, 0, 0, .125);
								}
								.btn-primary 
								{
									color: #fff;
									background-color: #337ab7;
									border-color: #2e6da4;
								}
								.btn-primary:hover,
								.btn-primary:focus,
								.btn-primary.focus,
								.btn-primary:active,
								.btn-primary.active,
								.open > .dropdown-toggle.btn-primary 
								{
									color: #fff;
									background-color: #286090;
									border-color: #204d74;
								}
								.btn-primary:active,
								.btn-primary.active,
								.open > .dropdown-toggle.btn-primary 
								{
									background-image: none;
								}
								.btn-primary.disabled,
								.btn-primary[disabled],
								fieldset[disabled] .btn-primary,
								.btn-primary.disabled:hover,
								.btn-primary[disabled]:hover,
								fieldset[disabled] .btn-primary:hover,
								.btn-primary.disabled:focus,
								.btn-primary[disabled]:focus,
								fieldset[disabled] .btn-primary:focus,
								.btn-primary.disabled.focus,
								.btn-primary[disabled].focus,
								fieldset[disabled] .btn-primary.focus,
								.btn-primary.disabled:active,
								.btn-primary[disabled]:active,
								fieldset[disabled] .btn-primary:active,
								.btn-primary.disabled.active,
								.btn-primary[disabled].active,
								fieldset[disabled] .btn-primary.active 
								{
									background-color: #337ab7;
									border-color: #2e6da4;
								}
								.btn-primary .badge 
								{
									color: #337ab7;
									background-color: #fff;
								}
								a
								{
									color: #99ccff;
								}
								.bottom-text
								{
									color: grey;
									text-align: center;
								}
							</style>
							<div class="wrapper">
								<h1>
									Thank you for joining the Online Movie Index!
								</h1>
								<h3>
									Welcome '.$this->username.'<br>
									You\'ve succesfully registered your new account at Online Movie
									Index. To finalize your registration, please activate your 
									account through the link below!
								</h3>
								<a href="http://www.omi.mjsolutions.dk/activate/'.$this->id.'/'.$this->activationCode.'/" class="btn btn-primary">Activate you account</a>
								<br>
								<br>
								<h3>
									Alternatively, your can follow the link below, or paste it into
									your browser.<br>
									<a href="http://www.omi.mjsolutions.dk/activate/'.$this->id.'/'.$this->activationCode.'/">http://www.omi.mjsolutions.dk/activate/'.$this->id.'/'.$this->activationCode.'/</a>
								</h3>
								<hr>
								<small class="bottom-text">
									The Online Movie Index Application is created by CincoWare. &copy; 2015 CincoWare
								</small>
							</div>
						</body>
					</html>
				';
		return $data;
	}
	
	public function checkActivationCode($activationCode)
	{
		if($activationCode == $this->activationCode)
		{
			return $this->setActivationToNull();
		}
		return false;
	}
	
	private function setActivationToNull()
	{
		$sql = "UPDATE user SET user_activation_code = NULL WHERE user_id = ? AND user_activation_code = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if($stmt === false){
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('is', $this->id, $this->activationCode); //Bind parameters.
		$stmt->execute(); //Execute
		$rows = $stmt->affected_rows;
		$stmt->close();
		if($rows != false){
			return true;
		}return false;
	}
	
	public function login($username, $password)
	{
		if($this->checkIfValueExists('username', $username))
		{
			$this->setValuesAccordingToUsername($username);
			$hashedTriedPassword = $this->hashPass($password);
			if($hashedTriedPassword == $this->hashedPassword)
			{
				$_SESSION['signed_in'] = TRUE;
				$_SESSION['active_user'] = new User($this->id);
				return true;
			}
			else
			{
				$respond = 'Username Or Password is incorrect!'; //Password is wrong
			}
		}
		else
		{
			$respond = 'Username Or Password is incorrect!'; //Username is wrong
		}
	}

	private function setValuesAccordingToUsername($username)
	{
		$sql = "SELECT user_id, user_email, user_password, user_created, user_active, user_role_id, user_activation_code FROM user WHERE username = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if($stmt === false){
			trigger_error('SQL Error: '.$dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('s', $username); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($id, $email, $password, $created, $active, $roleId, $activationCode); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if($stmt->num_rows == 1){
			$this->setId($id);
			$this->setUsername($username);
			$this->setEmail($email);
			$this->setPassword($password);
			$this->setCreated($created);
			$this->setActive($active);
			$this->setRoleId($roleId);
			$this->setRole();
			$this->setActivationCode($activationCode);
		}return true;
	}
	
	
	/*
	 * All Getters and Setters (Getters are public, Setters are private)
	 */
	public function getId()
	{
		return $this->id;
	}

	public function getUsername()
	{
		return $this->username;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getDateCreated()
	{
		return date($fullDateFormat, strtotime($this->created));
	}
	
	public function getDatetimeCreated()
	{
		return date($shortDateTimeFormat, strtotime($this->created));
	}
	
	public function getActive()
	{
		return $this->active;
	}
	
	public function getRole()
	{
		return $this->role;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setUsername($username)
	{
		$this->username = $username;
	}
	
	private function setEmail($email)
	{
		$this->email = $email;
	}
	
	private function setPassword($password)
	{
		$this->hashedPassword = $password;
	}
	
	private function setCreated($created)
	{
		$this->created = $created;
	}
	
	private function setActive($active)
	{
		$this->active = $active;
	}
	
	private function setRoleId($roleId)
	{
		$this->roleId = $roleId;
	}
	
	private function setRole()
	{
		$sql = "SELECT role_name FROM role WHERE role_id = ?";
		$stmt = $dbCon->prepare($sql);
		$stmt->bind_param("i", $this->roleId);
		$stmt->execute(); //Execute
		$stmt->bind_result($role); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		$this->role = $role;
	}
	
	private function setActivationCode($activationCode)
	{
		$this->activationCode = $activationCode;
	}
}