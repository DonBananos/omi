<?php

/* 
 * The User Object is used when needing a single user.
 * This object is only for getting and setting User Values.
 * 
 * @author Mike Jensen < mj@mjsolutions.dk >
 */

class User
{
	private $id;
	private $firstname;
	private $lastname;
	private $username;
	
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
	
	public function createUser($username, $firstname, $lastname)
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

	public function getFirstname()
	{
		return $this->firstname;
	}

	public function getLastname()
	{
		return $this->lastname;
	}

	public function getUsername()
	{
		return $this->username;
	}

	private function setId($id)
	{
		$this->id = $id;
	}

	private function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}

	private function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}

	private function setUsername($username)
	{
		$this->username = $username;
	}
}

