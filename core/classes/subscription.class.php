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
	
	public function exists(){
		 return $this->chiron_subscription_db->exists($this->id_source, $this->id_user);
	}
	
	public function add(){
		if(!$this->exists()){
			return $this->chiron_subscription_db->add($this->id_source, $this->id_user, $this->id_category);
		}
	}
	
	public function edit_category(){
		if($this->id=="" or !$this->id>0){
			$this->load_id();
		}
		return $this->chiron_subscription_db->edit_category($this->id, $this->id_category);
	}
	
	public function delete(){
		// First, try to load the ID if it isnt.
		if($this->id=="" or !$this->id>0){
			$this->load_id();
		}
		// Second, if we then do have an id, delete it
		if($this->id!="" and $this->id>0){
			$this->chiron_subscription_db->delete($this->id);
		}
	}
	
	public function load($array){
		$this->id = $array['id'];
		$this->id_source = $array['id_source'];
		$this->id_user = $array['id_user'];
		$this->id_category = $array['id_category'];
	}
	
	public function load_by_source_and_user(){
		$array = $this->chiron_subscription_db->get_by_source_and_user($this->id_source, $this->id_user);
		$this->load($array);
	}
	
	public function load_id(){
		$result = $this->chiron_subscription_db->get_id_by_source_and_user($this->id_source, $this->id_user);
		if($result!=0){
			$this->id = $result;
			return true;
		}else{
			return false;
		}
	}
	
}



?>