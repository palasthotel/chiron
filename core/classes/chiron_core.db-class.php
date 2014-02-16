<?php

class chiron_core_db {
		
		
		
		public function __construct(){
			
		}
		
		public function sources_count(){
			global $chiron_db;
			$query = "SELECT count(id) FROM `".$chiron_db->prefix."chiron_source`";
			$chiron_db->query($query);
			return $chiron_db->fetch_array();
		}
		
		
		public function sources_get_all(){
			global $chiron_db;
			$sources = array();
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_source ORDER BY title";
	    	$result = $chiron_db->query($query) or print('Query failed: ' . mysql_error());
	    	$feeds = array();
	    	while($source = $chiron_db->fetch_array($result)){
	      		$sources[$source["id"]] = $source;
	    	}
			return $sources;
		}
		
		public function sources_get_least_updated($number = 5){
			global $chiron_db;
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_source ORDER BY lastchecked ASC limit ".$number.";";
		    $query_result = $chiron_db->query($query) or print('Query failed: '.mysql_error());
			$result = array();
		    while($source_array = $chiron_db->fetch_array($result)) {
				$source_object = new chiron_source();
				$source_object->load($source_array);
				$result[] = $source_object;
				
			}
			return $result;
		}
		
		
		
		public function items_count(){
			global $chiron_db;
			$query = "SELECT count(id) FROM `".$chiron_db->prefix."chiron_item`";
			$chiron_db->query($query);
			return $chiron_db->fetch_array();
		}
		
		
}


?>