<?php

class CollectionHandler
{
	function __construct()
	{
		
	}
	
	public function getAllCollectionIdsFromUser($userId)
	{
		global $dbCon;
		$collectionIds = array();
		$sql = "SELECT collection_id FROM collection WHERE collection_user_id = ?";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('i', $userId); //Bind parameters.
		$stmt->execute(); //Execute
		$stmt->bind_result($collectionId); //Get ResultSet
		while($stmt->fetch())
		{
			array_push($collectionIds, $collectionId);
		}
		$stmt->close();
		return $collectionIds;
	}
}

