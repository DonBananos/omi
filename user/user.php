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
		
		//SELECT * FROM user WHERE user_id = $id
		//Query
		//Set each value according to resultset.
		//$this->setFirstname($firstname);
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
		return date('d/m-Y', strtotime($this->created));
	}
	
	public function getDatetimeCreated()
	{
		return date('d/m-Y h:i:s', strtotime($this->created));
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
		$this->password = $password;
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
		$db = new mysqli($host, $user, $password, $database, $port, $socket);
		$sql = "SELECT role_name FROM role WHERE role_id = ?";
		$statement = $db->prepare($sql);
		$statement->bind_param("i", $this->roleId);
		//Something to get the role_name
		$role = ""; //set $role to role_name..
		$this->role = $role;
	}
}

