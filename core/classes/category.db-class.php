<?php


class chiron_category_db { 
	
	public function category_add($user, $title, $weight){
		global $chiron_db;
		$query = "INSERT INTO `".$chiron_db->prefix."chiron_category` (`id` , `id_user` ,  `title` ,  `weight` ) VALUES ( NULL , '".$user."', '".addslashes($title)."', '".$weight."' );";
	    return $chiron_db->query($query) or print('Query failed: ' . mysql_error());
	}
	
	public function category_load($id){
		global $chiron_db;
		$query = "SELECT * FROM `".$chiron_db->prefix."chiron_category` WHERE id='".$id."'";
	    $result = $chiron_db->query($query);
		return $chiron_db->fetch_array();
	}
	
	public function category_update($id, $title, $weight){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_category set title = '".$title."', weight='".$weight."' WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
	
	public function category_delete(){
		
	}
	
	
}
