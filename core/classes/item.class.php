<?php 


class chiron_item {
  public $id;
  public $source;
  public $date;
  public $title;
  public $text;
  public $url;
  public $image;
  public $item_meta;
  public $chiron_item_db;
  

  public function __construct(){
	$this->chiron_item_db = new chiron_item_db();
 }

  public function load($array){
    $this->id = $array['id'];
    $this->source = $array['id_source'];
    $this->date = $array['timestamp']; 	
    $this->title = chiron_clean_string($array['title']);  
    $this->text = chiron_clean_string($array['text']); 	
    $this->url = $array['url'];
    $this->image = $array['image'];
    $this->item_meta = array();    
  }

  // Expects a SimplePie-Item
  public function fill($item) {
    $this->date = $item->get_date("Y-m-d H:i:s");
    $this->title = chiron_clean_string($item->get_title()); 
	$this->text = chiron_clean_string($item->get_content());
    $this->url = chiron_clean_string($item->get_permalink());
  }
  
  public function exists(){ 
    $return = $this->chiron_item_db->url_exists($this->url);
    if($return[0]==0){
      return false;
    }else {
      return true;
    }
  }
  
  public function add(){
    if(!$this->exists()){
      $date = $this->date;
      if($date == "0000-00-00 00:00:00"){
        	$date = time();
      }else{
			$date = strtotime($date);
	  }
      $return = $this->chiron_item_db->item_add($this->source, $date, $this->title, $this->text, $this->url);
      return 1;   
    }else{
      return 0;
    }
  }
  
  
  
  // Item Meta Actions
    
  // Check wether one Meta-Data-set exists  
  public function exists_meta($user, $meta_key, $meta_value){
    
  }  
  
  // Add Meta Data-Set
  public function add_meta($user, $meta_key, $meta_value){
    
  }
  
  // Delete Meta Data-Set
  public function delete_meta($meta_id){
    
  }
    
  // Load ALL Meta-Data of one Item, no matter which user they belong to
  public function load_all_meta(){
    
  }
  
  // Load all Meta-Data of one Item, that belong to one User
  public function load_users_meta($user){
  
  }
  
  
}