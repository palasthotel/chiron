<?php


class chiron_subscription {
	
	public $id;
	public $id_user;
	public $id_source;
	public $id_category;	
	public $chiron_subscription_db;
	
	
 	public function __construct(){
		$this->chiron_subscription_db = new chiron_subscription_db();
	}
	
	public function exists($id_user, $id_source){
		
	}
	
	public function add($id_user, $id_source, $id_category){
		
	}
	
	public function edit_category($id, $id_category){
		
	}
	
	public function delete($id){
		
	}
	
	
}



?>