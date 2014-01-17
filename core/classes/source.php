<?php 

class chiron_source {
  public $id;
  public $type;
  public $title;
  public $url;  
  public $lastchecked;
  public $feed_meta;

  public function load($array) {
    $this->id = $array['id'];
	$this->type = $array['type'];
    $this->title = $array['title'];
    $this->url = $array['url'];    
    $this->lastchecked = $array['lastchecked'];
  }

  public function exists(){ 
      global $db;
      $query = "SELECT count(url) FROM `".DB_PRE."chiron_source` WHERE url='".$this->url."'";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error());
      $return = mysql_fetch_array($result);
      if($return[0]==0){
        return false;
      }else {
        return true;
      }
  }

  public function add(){
      if(!$this->exists()){
       $query = "INSERT INTO `".DB_PRE."chiron_source` (`id`, `title`,  `url` ) VALUES ( NULL , '".$this->title."', '".$this->url."');";
       $result = mysql_query($query) or print('Query failed: ' . mysql_error());
       return 1;   
     }else{
       return 0;
     }
  }

  public function update(){ 
       $query = "UPDATE  `source` SET `title` = '".$this->title."',  `url`  =  '".$this->url."' WHERE `id` = '".$feed->id."';";
       print_r($query);
       $result = mysql_query($query) or print('Query failed: ' . mysql_error());
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
          $converted=new item();
          $converted->fill($item);
          $converted->source=$this->id;
          $counter += $converted->add();      
       }
       $query="UPDATE source set lastchecked = NOW() where id=".$this->id;
       mysql_query($query) or print("Query failed: ".mysql_error());
       return $counter;
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
