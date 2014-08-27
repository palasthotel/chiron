<?php

class chiron_core_db {
		
		
		
		public function __construct(){
			
		}
		
		// Methods for Sources
		
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
	    	$return = array();
			while($source_array = $chiron_db->fetch_array($result)) {
				$source_object = new chiron_source();
				$source_object->load($source_array);
				$return[$source_object->id] = $source_object;
			}

			return $return;
		}
		
		public function sources_get_least_updated($number = 5){
			global $chiron_db;
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_source ORDER BY lastchecked ASC limit ".$number.";";
		    $query_result = $chiron_db->query($query) or print('Query failed: '.mysql_error());
			$return = array();
		    while($source_array = $chiron_db->fetch_array($result)) {
				$source_object = new chiron_source();
				$source_object->load($source_array);
				$return[$source_object->id] = $source_object;				
			}
			return $return;
		}
		
		public function sources_get_some_by_ids($ids_sources){
			global $chiron_db;
			$where = "";
			if(is_array($ids_sources) and count($ids_sources)>0){
				$wherese = array();
				foreach($ids_sources as $id_source){
					$wherese[] = "id = '".$id_source."'";
				}
				$where = "WHERE ".implode(" OR ", $wherese);
			}
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_source ".$where." ORDER BY title";
		    $query_result = $chiron_db->query($query) or print('Query failed: '.mysql_error()." QUERY [".$query."]");
			$return = array();
		    while($source_array = $chiron_db->fetch_array($result)) {
				$source_object = new chiron_source();
				$source_object->load($source_array);
				$return[$source_object->id] = $source_object;				
			}
			return $return;
			
		}
		
		public function sources_get_item_count(){
			global $chiron_db;
			$query = "SELECT  id_source, count(id_source) FROM ".$chiron_db->prefix."chiron_item GROUP by id_source";
			$query_result = $chiron_db->query($query) or print('Query failed: '.mysql_error()." QUERY [".$query."]");
			$return = array();
			while($itemcount = $chiron_db->fetch_array($result)) {
				$return[$itemcount['id_source']] = $itemcount['count(id_source)'];
			}
			return $return;
		}
		
		
		// Methods for Items
		
		
		public function items_count(){
			global $chiron_db;
			$query = "SELECT count(id) FROM `".$chiron_db->prefix."chiron_item`";
			$chiron_db->query($query);
			return $chiron_db->fetch_array();
		}
		
		public function items_get_by_day($day){
			global $chiron_db;
			$start = strtotime($day." 00:00:00");
			$end = strtotime($day." 23:59:59");
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_item WHERE timestamp >= '$start' AND timestamp <= '$end'";
		    $result = $chiron_db->query($query) or print('Query failed: ' . mysql_error());  
		 	$return = array();
		    while($item = $chiron_db->fetch_array($result)){
		      $object = new chiron_item('', array());
		      $object->load($item);      
		      $return[] = $object;
		    }
			return $return;
		}
		
		public function items_get_by_day_and_sources($day, $ids_sources){
			global $chiron_db;
			
			$start = strtotime($day." 00:00:00");
			$end = strtotime($day." 23:59:59");
			
			$where = "";
			if(is_array($ids_sources) and count($ids_sources)>0){
				$wherese = array();
				foreach($ids_sources as $id_source){
					$wherese[] = "id_source = '".$id_source."'";
				}
				$where = " AND (".implode(" OR ", $wherese).")";
			}
			
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_item WHERE timestamp >= '$start' AND timestamp <= '$end' ".$where;
		    $result = $chiron_db->query($query) or print('Query failed: ' . mysql_error());  
		 	$return = array();
		    while($item = $chiron_db->fetch_array($result)){
		      $object = new chiron_item('', array());
		      $object->load($item);      
		      $return[] = $object;
		    }
			return $return;
		}
		
		
		// Methods for Categories
		
		public function categories_get_all_by_user($id_user){
			global $chiron_db;
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_category WHERE id_user = ".$id_user." ORDER BY weight ASC";
			$result = $chiron_db->query($query) or print('Query failed: ' . mysql_error());  
		 	$return = array();
		    while($array = $chiron_db->fetch_array($result)){
		      $object = new chiron_category();
		      $object->load($array);      
		      $return[$object->id] = $object;
		    }
			return $return;
		}
		
		
		//  Methods for Subscriptions
		
		public function subscriptions_get_all_by_user($id_user){
			global $chiron_db;
			$query = "SELECT * FROM ".$chiron_db->prefix."chiron_subscription WHERE id_user = ".$id_user." ORDER BY id ASC";
			$result = $chiron_db->query($query) or print('Query failed: ' . mysql_error());  
		 	$return = array();
			while($array = $chiron_db->fetch_array($result)){
		      $object = new chiron_subscription();
		      $object->load($array);      
		      $return[$object->id_source] = $object;
		    }
			return $return;			
		}
		
		
}


?>