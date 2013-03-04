<?php 

class feed extends SimplePie_Source {
  	public $id;
    public $title;
    public $url;
    public $type;
    public $feed_meta;


    function exists(){ 
      global $db;
      $query = "SELECT count(url) FROM feed WHERE url='".$this->url."'";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error());
      $return = mysql_fetch_array($result);
      if($return[0]==0){
        return false;
      }else {
        return true;
      }
    }

    function get_all(){
      $query = "SELECT * FROM feed ORDER BY title";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error());
      $feeds = array();
      while($feed = mysql_fetch_array($result)){
        $feeds[$feed["id"]] = $feed;
      }
      return $feeds;
    }

    function add(){
      if(!$this->exists()){
       $query = "INSERT INTO `feed` (`id`, `title`,  `url` ) VALUES ( NULL , '".$this->title."', '".$this->url."');";
       $result = mysql_query($query) or print('Query failed: ' . mysql_error());
       return 1;   
     }else{
       return 0;
     }
    }

    function update(){ 
       $query = "UPDATE  `feed` SET `title` = '".$this->title."',  `url`  =  '".$this->url."' WHERE `id` = '".$feed->id."';";
       print_r($query);
       $result = mysql_query($query) or print('Query failed: ' . mysql_error());
       return 1;   
     }

     function refresh(){
       $items = array();


       $feed = new SimplePie();
       $feed->set_feed_url($this->url);
       $feed->init();
       $feed->handle_content_type();

       foreach ($feed->get_items() as $item){
         $item->source = $this->id;
         $items[] = $item;
       }

       $counter = 0;
       foreach($items as $item){
          $counter += $item->add();      
       }

       return $counter;
     }
}
