<?php 


class item extends SimplePie_Item {
  public $id;
  public $source;
  public $date;
  public $title;
  public $text;
  public $url;
  public $image;
  public $item_meta;
  
  public function load($array){
    $this->id = $array['id'];
    $this->source = $array['source'];
    $this->date = $array['date'];
    $this->title = $array['title'];
    $this->text = $array['text'];
    $this->url = $array['url'];
    $this->image = $array['image'];
    $this->item_meta = array();    
  }
  
  public function exists(){ 
    global $db;
    $query = "SELECT count(url) FROM item WHERE url='".mysql_real_escape_string($this->get_permalink())."'";  
    $result = mysql_query($query);
    $return = mysql_fetch_array($result);
    if($return[0]==0){
      return false;
    }else {
      return true;
    }
  }
  
  public function add($feed){
    if(!$this->exists()){
      $date = $item->get_date("Y-m-d H:i:s");
      if($date == "0000-00-00 00:00:00"){
        $date = date("Y-m-d H:i:s");
      }
      $query = "INSERT INTO `item` (`id` , `source` ,  `date` , `title` ,  `text` ,  `url` ) VALUES ( NULL , '".$feed->id."', '".$date."', '".addslashes($this->get_title())."', '".addslashes($this->get_content())."', '".mysql_real_escape_string($this->get_permalink())."' );";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error()); 
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