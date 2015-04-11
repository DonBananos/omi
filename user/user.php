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
	private $password; //String
	private $created; //Datetime
	private $active; //Boolean
	private $roleId; //Integer
	private $role; //String
	
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
	
	public function createUser($username, $email, $password)
	{
		/*
		 * If you don't understand this function, you probably shouldn't be 
		 * looking in the Source Code. Try mobbing floors instead.!
		 */
		//Validate Data
		//INSERT INTO MYSQL database
		//Return result (True, False)
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

