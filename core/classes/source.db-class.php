<?php

class chiron_source_db(){
	
	public function url_count(){
		global $chiron_db;
		$query = "SELECT count(url) FROM `".DB_PRE."chiron_source` WHERE url='".$this->url."'";
		$chiron_db->query($query);
		return $chiron_db->fetch_array();
	}
}


?>