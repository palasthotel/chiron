<?php

class chiron_source_db{
	
	public function url_count($url){
		global $chiron_db;
		$query = "SELECT count(url) FROM `".$chiron_db->prefix."chiron_source` WHERE url='".$url."'";
		$chiron_db->query($query);
		return $chiron_db->fetch_array();
	}
	
	public function source_load($id){
		global $chiron_db;
		$query = "SELECT * FROM `".$chiron_db->prefix."chiron_source` WHERE id='".$id."'";
	    $result = $chiron_db->query($query);
		return $chiron_db->fetch_array();
	}
	
	public function source_add($title, $type, $url){
		global $chiron_db;
		$query = "INSERT INTO `".$chiron_db->prefix."chiron_source` (`id`, `title`, `id_source_type`,  `url` ) VALUES ( NULL , '".$title."', '".$type."', '".$url."');";
	    $result = $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);;
		return $result;
	}
	
	public function source_update($id, $title, $url){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_source set title = '".$title."', url='".$url."' WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
	
	public function set_lastchecked($id, $timestamp){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_source set lastchecked = ".$timestamp." WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
	
	public function set_status($id, $status){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_source set status = '".$status."' WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
	
	public function get_id_by_url($url){
		global $chiron_db;
		$query = "SELECT id FROM `".$chiron_db->prefix."chiron_source` WHERE url='".$url."'";
	    $result = $chiron_db->query($query);
		$row = $chiron_db->fetch_array();
		return $row['id'];
 	}
}


?>