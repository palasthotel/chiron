<?php


class chiron_category{
	
	public $id;
	public $user;
	public $title;
	public $weight;
	public $chiron_category_db;
	
	public function __construct($id = ""){
		$this->chiron_category_db = new chiron_category_db();
		if($id!=""){
			$array = $this->chiron_category_db->category_load($id);
			$this->load($array);
		}
	}
	
	public function load($array){
		$this->id = $array['id'];
		$this->user = $array['user'];
		$this->title = $array['title'];
		$this->weight = $array['weight'];
	}
	
	public function add(){
		return $this->chiron_category_db->category_add($this->user, $this->title, $this->weight);
	}
	
	public function update(){
		return $this->chiron_category_db->category_update($this->id, $this->title, $this->weight);
	}
	
}