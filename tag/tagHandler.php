<?php

class TagHandler
{
	function __construct()
	{
		
	}
	
	public function search_for_tag($search_term, $limit = 5)
	{
		$tags = array();
		
		if(strlen(trim($search_term)) == 0)
		{
			return null;
		}
		
		$search = "%".$search_term."%";
		global $dbCon;
		
		$sql = "SELECT tag_id, tag_name FROM tag WHERE tag_name LIKE ? LIMIT ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param("si", $search, $limit);
		$stmt->execute(); //Execute
		$stmt->bind_result($tag_id, $tag_name); //Get ResultSet
		while ($stmt->fetch())
		{
			$tags[$tag_id] = $tag_name;
		}
		$stmt->close();
		return $tags;
	}
	
	public function create_new_tag($tag_name, $user_id)
	{
		if($this->check_if_tag_exists($tag_name) != false)
		{
			return $this->check_if_tag_exists($tag_name);
		}
		global $dbCon;
		
		$sql = "INSERT INTO tag (tag_name, tag_creator) VALUES (?, ?);";
		$stmt = $dbCon->prepare($sql);
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param('si', $tag_name, $user_id);
		$stmt->execute();
		$id = $stmt->insert_id;
		$stmt->close();
		if ($id > 0)
		{
			return $id;
		}
		return $dbCon->error;
	}
	
	private function check_if_tag_exists($tag_name)
	{
		global $dbCon;
		
		$sql = "SELECT tag_id FROM tag WHERE tag_name = ?;";
		$stmt = $dbCon->prepare($sql); //Prepare Statement
		if ($stmt === false)
		{
			trigger_error('SQL Error: ' . $dbCon->error, E_USER_ERROR);
		}
		$stmt->bind_param("s", $tag_name);
		$stmt->execute(); //Execute
		$stmt->bind_result($tag_id); //Get ResultSet
		$stmt->fetch();
		$stmt->close();
		if(is_int($tag_id) && $tag_id > 0)
		{
			return $tag_id;
		}
		return false;
	}
}