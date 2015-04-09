<?php

/**
 * The User Handler Object takes care of all user actions where it's not about
 * a single, already saved, user in the database.
 * This class takes care of:
 *  - Selecting all users
 *  - Selecting a section of users
 *  - Counting users
 *  - Etc.
 *
 * @author Mike Jensen < mj@mjsolutions.dk >
 */
class userHandler
{
	function __construct()
	{
		//Seriously, that's all it takes?
		//Yeah bro! Freaking PhP yo..
	}
	
	public function selectAllUsers()
	{
		/*
		 * This function fetches all user IDs from the database, and places
		 * them in a singledimensioned Array, with Key according to position in
		 * Array.
		 * The handler then returns the array to the view, which can create a
		 * user object out of each user ID, in a foreach loop, and display the
		 * user by using the user object. Pretty freaking smart. Alternavely, a
		 * user object could be stored in the array for each user, and then the
		 * view would not have to create each user, but this is only possible if
		 * we're absolutely certain that the user object is included before 
		 * EVERY instans of this object, which uses this function.
		 */
		
		$userIds = array();
		//select user IDs for all users
		//array_push user IDs in a while loop
		//return $userIds;
	}
}
