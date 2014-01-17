<?php

class chiron_core_db {
		
		
		
		public function __construct(){
			
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
		
}


?>