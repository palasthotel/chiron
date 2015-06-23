<?php 

class chiron_source {
  	public $id;
  	public $type;
  	public $title;
  	public $url;  
  	public $lastchecked;
  	public $feed_meta;
	public $status;
	public $lastadded;
	public $error;
  	public $chiron_source_db;

  	public function __construct($id = ""){
		$this->chiron_source_db = new chiron_source_db();
		if($id!=""){
			$array = $this->chiron_source_db->source_load($id);
			$this->load($array);
		}
	}

  public function load($array) {
    $this->id = $array['id'];
	$this->type = $array['type'];
    $this->title = chiron_clean_string($array['title']);
    $this->url = $array['url'];    
	$this->status = $array['status'];    
    $this->lastchecked = $array['lastchecked'];	
  }

	public function load_by_url() {
	    $this->chiron_source_db = new chiron_source_db();
		$this->id = $this->chiron_source_db->get_id_by_url($this->url);
		$array = $this->chiron_source_db->source_load($this->id);
		$this->load($array);	
	}

  public function exists(){            
      $return = $this->chiron_source_db->url_count($this->url);
      if($return[0]==0){
        return false;
      }else {
        return true;
      }
  }

  public function add(){
	if(!$this->exists()){
		if($this->type == ""){
			$this->type = "1";
		}
        $this->chiron_source_db->source_add($this->title, $this->type, $this->url);
		$this->id = $this->chiron_source_db->get_id_by_url($this->url);
       return 1;   
     }else{
       return 0;
     }
  }

  public function update(){
  	$this->chiron_source_db->source_update($this->id, $this->title, $this->url);
    return 1;         
  }

  public function refresh(){
       $items = array();

       $feed = new SimplePie();
       $feed->set_feed_url($this->url);
       $feed->init();
       $feed->handle_content_type();

       foreach ($feed->get_items() as $item){
         $items[] = $item;
       }

       $counter = 0;
       foreach($items as $item){	
          $converted = new chiron_item();
          // Fill with an SimplePie-Item
          $converted->fill($item);
          $converted->source = $this->id;
		  $result = $converted->add();      
		  // If an item is not added, we assume that all following are also already in the DB, so we break
		  if($result == 1){
			$counter ++;
		  }else{
			break;
		  }	
       }
		
		$this->lastadded = $counter;
		if($feed->error!=""){
			$this->set_status("broken");
			$this->error = $feed->error;
		}else{
			$this->set_status("ok");
		}

       $this->chiron_source_db->set_lastchecked($this->id, time());
       return $counter;
     }
  
	public function set_status($status){
		$this->chiron_source_db->set_status($this->id, $status);
	}
  
  // Feed Meta Actions
    
  // Check wether one Meta-Data-set exists  
  public function exists_meta($user, $meta_key, $meta_value){
    
  }  
  
  // Add Meta Data-Set
  public function add_meta($user, $meta_key, $meta_value){
    
  }
  
  // Delete Meta Data-Set
  public function delete_meta($meta_id){
    
  }
    
  // Load ALL Meta-Data of one Feed, nomatter which user they belong to
  public function load_all_meta(){
    
  }
  
  // Load all Meta-Data of one Feed, that belong to one User
  public function load_users_meta($user){
    
  }
  
  // Add a feed to a page (feed_meta_action);   
  public function set_page($user, $page_name){
    
  }
  
  // Remove a feed from a page (feed_meta_action);   
  public function remove_page($user, $page_name){
    
  }
  
  
  
}
