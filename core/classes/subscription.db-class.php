<?php


class chiron_subscription_db{
	
		
	public function exists($id_source, $id_user){
		global $chiron_db;
		$query = "SELECT count(id) FROM `".$chiron_db->prefix."chiron_subscription` WHERE id_source='".$id_source."' and id_user='".$id_user."'";
		$chiron_db->query($query);
		$result = $chiron_db->fetch_array();
		//print_r($result);
		if($result[0]>0){
			return true;
		}else{
			return false;
		}
	}
	
	public function add($id_source, $id_user, $id_category){
		global $chiron_db;
		$query = "INSERT INTO `".$chiron_db->prefix."chiron_subscription` (`id`, `id_source`, `id_user`,  `id_category` ) VALUES ( NULL , '".$id_source."', '".$id_user."', '".$id_category."');";
		$result = $chiron_db->query($query);
		return $result;
	}
	
	public function edit_category($id, $id_category){
		global $chiron_db;
		$query = "UPDATE ".$chiron_db->prefix."chiron_subscription set id_category = ".$id_category." WHERE id = ".$id."";
		return $chiron_db->query($query) or print("Query failed: ".mysql_error()." Query:".$query);
	}
	
	public function delete($id){
		global $chiron_db;
		$query = "DELETE FROM ".$chiron_db->prefix."chiron_subscription WHERE id=".$id." ";
		$chiron_db->query($query);
		return 1;
	}
	
	public function get_id_by_source_and_user($id_source, $id_user){
		global $chiron_db;
		$query = "SELECT id FROM `".$chiron_db->prefix."chiron_subscription` WHERE id_source='".$id_source."' and id_user='".$id_user."'";
		$chiron_db->query($query);
		$result = $chiron_db->fetch_array();
		//print_r($result);
		if($result['id']>0){
			return $result['id'];
		}else{
			return 0;
		}
	}
	
	public function get_by_source_and_user($id_source, $id_user){
		global $chiron_db;
		$query = "SELECT * FROM `".$chiron_db->prefix."chiron_subscription` WHERE id_source='".$id_source."' and id_user='".$id_user."'";
		$chiron_db->query($query);
		$result = $chiron_db->fetch_array();
		//print_r($result);
		if($result['id']>0){
			return $result;
		}else{
			return 0;
		}
	}
	
}


?>