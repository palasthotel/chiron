<?php

class chiron_source_db{
	
	public function url_count(){
		global $chiron_db;
		$query = "SELECT count(url) FROM `".$chiron_db->prefix."chiron_source` WHERE url='".$this->url."'";
		$chiron_db->query($query);
		return $chiron_db->fetch_array();
	}
	
	public function source_add($title, $type, $url){
		global $chiron_db;
		$query = "INSERT INTO `".$chiron_db->prefix."chiron_source` (`id`, `title`, `id_source_type`,  `url` ) VALUES ( NULL , '".$title."', '".$type."', '".$url."');";
	    $result = $chiron_db->query($query);
		return $result;
	}
	
	public function set_lastchecked($id, $timestamp){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_source set lastchecked = ".$timestamp." WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
}


?>