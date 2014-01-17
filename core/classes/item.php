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

  public function fill($item) {
    $this->date=$item->get_date("Y-m-d H:i:s");
    $this->title=$item->get_title();
    $this->text=$item->get_content();
    $this->url=$item->get_permalink();
  }
  
  public function exists(){ 
    global $db;
    $query = "SELECT count(url) FROM item WHERE url='".mysql_real_escape_string($this->url)."'";  
    $result = mysql_query($query);
    $return = mysql_fetch_array($result);
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
        $date = date("Y-m-d H:i:s");
      }
      $query = "INSERT INTO `item` (`id` , `source` ,  `date` , `title` ,  `text` ,  `url` ) VALUES ( NULL , '".$this->source."', '".$date."', '".addslashes($this->title)."', '".addslashes($this->text)."', '".mysql_real_escape_string($this->url)."' );";
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