<?php

// This Class is meant to create a DB-Connection and execute Queries, only.


class chiron_db {
	public $prefix;
	public $schema;
	public $connection;
	
	public function __construct($db_srv, $db_usr, $db_pwd, $db_dbs, $db_pre) {
		$this->connection = mysql_connect($db_srv, $db_usr, $db_pwd);
    	mysql_select_db( $db_dbs) or die('Could not select database');
		$this->prefix = $db_pre;
		$this->load_schema();
	}
	
	public function load_schema(){
		// Table for Sources
		$this->schema['chiron_source'] = array();
		$this->schema['chiron_source']['description'] = chiron_t('Stores all Sources'); 
		$this->schema['chiron_source']['primary key'] = array('id');
		$this->schema['chiron_source']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_source']['fields'] = array();

		$this->schema['chiron_source']['fields']['id'] = array();
		$this->schema['chiron_source']['fields']['id']['description'] = chiron_t('source id');
		$this->schema['chiron_source']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_source']['fields']['id']['size'] ='normal';
		$this->schema['chiron_source']['fields']['id']['not null'] = true;
		$this->schema['chiron_source']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_source']['fields']['id_source_type'] = array();
		$this->schema['chiron_source']['fields']['id_source_type']['description'] = chiron_t('source type id');
		$this->schema['chiron_source']['fields']['id_source_type']['type'] = 'int';
		$this->schema['chiron_source']['fields']['id_source_type']['size'] = 'normal';
		$this->schema['chiron_source']['fields']['id_source_type']['not null'] = true;
		$this->schema['chiron_source']['fields']['id_source_type']['unsigned'] = false;

		$this->schema['chiron_source']['fields']['title'] = array();
		$this->schema['chiron_source']['fields']['title']['description'] = chiron_t('source title');
		$this->schema['chiron_source']['fields']['title']['type'] = 'text';
		$this->schema['chiron_source']['fields']['title']['size'] = 'normal';

		$this->schema['chiron_source']['fields']['url'] = array();
		$this->schema['chiron_source']['fields']['url']['description'] = chiron_t('source url');
		$this->schema['chiron_source']['fields']['url']['type'] = 'text';
		$this->schema['chiron_source']['fields']['url']['size'] = 'normal';

		$this->schema['chiron_source']['fields']['status'] = array();
		$this->schema['chiron_source']['fields']['status']['description'] = chiron_t('sources status');
		$this->schema['chiron_source']['fields']['status']['type'] = 'text';
		$this->schema['chiron_source']['fields']['status']['size'] = 'normal';

		$this->schema['chiron_source']['fields']['lastchecked'] = array();
		$this->schema['chiron_source']['fields']['lastchecked']['description'] = chiron_t('timestamp of last check of this source');
		$this->schema['chiron_source']['fields']['lastchecked']['type'] = 'int';
		$this->schema['chiron_source']['fields']['lastchecked']['size'] ='normal';
		$this->schema['chiron_source']['fields']['lastchecked']['not null'] = true;
		$this->schema['chiron_source']['fields']['lastchecked']['unsigned'] = false;


		// Table for Source Type	
		// Within the Default Setup there are two Types of Sources:
		// 1. RSS and Atom-Feeds
		// 2. Twitter
		$this->schema['chiron_source_type'] = array();
		$this->schema['chiron_source_type']['description'] = chiron_t('Stores all Source-Tpyes');
		$this->schema['chiron_source_type']['primary key'] = array('id');
		$this->schema['chiron_source_type']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_source_type']['fields'] = array();

		$this->schema['chiron_source_type']['fields']['id'] = array();
		$this->schema['chiron_source_type']['fields']['id']['description'] = chiron_t('source type id');
		$this->schema['chiron_source_type']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_source_type']['fields']['id']['size'] = 'normal';
		$this->schema['chiron_source_type']['fields']['id']['not null'] = true;
		$this->schema['chiron_source_type']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_source_type']['fields']['title'] = array();
		$this->schema['chiron_source_type']['fields']['title']['description'] = chiron_t('source type title');
		$this->schema['chiron_source_type']['fields']['title']['type'] = 'text';
		$this->schema['chiron_source_type']['fields']['title']['size'] = 'normal';


		// Table for Source Category	
		// Within the Default Setup there are two Types of Sources:
		$this->schema['chiron_category'] = array();
		$this->schema['chiron_category']['description'] = chiron_t('Stores all Categories');
		$this->schema['chiron_category']['primary key'] = array('id');
		$this->schema['chiron_category']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_category']['fields'] = array();

		$this->schema['chiron_category']['fields']['id'] = array();
		$this->schema['chiron_category']['fields']['id']['description'] = chiron_t('category id');
		$this->schema['chiron_category']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_category']['fields']['id']['size'] = 'normal';
		$this->schema['chiron_category']['fields']['id']['not null'] = true;
		$this->schema['chiron_category']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_category']['fields']['id_user'] = array();
		$this->schema['chiron_category']['fields']['id_user']['description'] = chiron_t('user id');
		$this->schema['chiron_category']['fields']['id_user']['type'] = 'int';
		$this->schema['chiron_category']['fields']['id_user']['size'] = 'normal';
		$this->schema['chiron_category']['fields']['id_user']['not null'] = true;
		$this->schema['chiron_category']['fields']['id_user']['unsigned'] = false;

		$this->schema['chiron_category']['fields']['title'] = array();
		$this->schema['chiron_category']['fields']['title']['description'] = chiron_t('category title');
		$this->schema['chiron_category']['fields']['title']['type'] = 'text';
		$this->schema['chiron_category']['fields']['title']['size'] = 'normal';

		$this->schema['chiron_category']['fields']['weight'] = array();
		$this->schema['chiron_category']['fields']['weight']['description'] = chiron_t('weight id');
		$this->schema['chiron_category']['fields']['weight']['type'] = 'int';
		$this->schema['chiron_category']['fields']['weight']['size'] = 'normal';
		$this->schema['chiron_category']['fields']['weight']['not null'] = true;
		$this->schema['chiron_category']['fields']['weight']['unsigned'] = false;
		

		// Table to map Subscriptions
		$this->schema['chiron_subscription'] = array();
		$this->schema['chiron_subscription']['description'] = chiron_t('Stores all subscriptions');
		$this->schema['chiron_subscription']['primary key'] = array('id');
		$this->schema['chiron_subscription']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_subscription']['fields'] = array();

		$this->schema['chiron_subscription']['fields']['id'] = array();
		$this->schema['chiron_subscription']['fields']['id']['description'] = chiron_t('subscriptions id');
		$this->schema['chiron_subscription']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_subscription']['fields']['id']['size'] = 'normal';
		$this->schema['chiron_subscription']['fields']['id']['not null'] = true;
		$this->schema['chiron_subscription']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_subscription']['fields']['id_source'] = array();
		$this->schema['chiron_subscription']['fields']['id_source']['description'] = chiron_t('Source id');
		$this->schema['chiron_subscription']['fields']['id_source']['type'] = 'int';
		$this->schema['chiron_subscription']['fields']['id_source']['size'] = 'normal';
		$this->schema['chiron_subscription']['fields']['id_source']['not null'] = true;
		$this->schema['chiron_subscription']['fields']['id_source']['unsigned'] = false;

		$this->schema['chiron_subscription']['fields']['id_user'] = array();
		$this->schema['chiron_subscription']['fields']['id_user']['description'] = chiron_t('user id');
		$this->schema['chiron_subscription']['fields']['id_user']['type'] = 'int';
		$this->schema['chiron_subscription']['fields']['id_user']['size'] = 'normal';
		$this->schema['chiron_subscription']['fields']['id_user']['not null'] = true;
		$this->schema['chiron_subscription']['fields']['id_user']['unsigned'] = false;
		
		$this->schema['chiron_subscription']['fields']['id_category'] = array();
		$this->schema['chiron_subscription']['fields']['id_category']['description'] = chiron_t('category id');
		$this->schema['chiron_subscription']['fields']['id_category']['type'] = 'int';
		$this->schema['chiron_subscription']['fields']['id_category']['size'] = 'normal';
		$this->schema['chiron_subscription']['fields']['id_category']['not null'] = true;
		$this->schema['chiron_subscription']['fields']['id_category']['unsigned'] = false;


		// Table for Source Meta	
		$this->schema['chiron_source_meta'] = array();
		$this->schema['chiron_source_meta']['description'] = chiron_t('Stores Metadata for each Source; possibly user depended');
		$this->schema['chiron_source_meta']['primary key'] = array('id');
		$this->schema['chiron_source_meta']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_source_meta']['fields'] = array();

		$this->schema['chiron_source_meta']['fields']['id'] = array();
		$this->schema['chiron_source_meta']['fields']['id']['description'] = chiron_t('source meta id');
		$this->schema['chiron_source_meta']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_source_meta']['fields']['id']['size'] = 'normal';
		$this->schema['chiron_source_meta']['fields']['id']['not null'] = true;
		$this->schema['chiron_source_meta']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_source_meta']['fields']['id_source'] = array();
		$this->schema['chiron_source_meta']['fields']['id_source']['description'] = chiron_t('source id');
		$this->schema['chiron_source_meta']['fields']['id_source']['type'] = 'int';
		$this->schema['chiron_source_meta']['fields']['id_source']['size'] = 'normal';
		$this->schema['chiron_source_meta']['fields']['id_source']['not null'] = true;
		$this->schema['chiron_source_meta']['fields']['id_source']['unsigned'] = false;

		$this->schema['chiron_source_meta']['fields']['id_user'] = array();
		$this->schema['chiron_source_meta']['fields']['id_user']['description'] = chiron_t('user id');
		$this->schema['chiron_source_meta']['fields']['id_user']['type'] = 'int';
		$this->schema['chiron_source_meta']['fields']['id_user']['size'] = 'normal';
		$this->schema['chiron_source_meta']['fields']['id_user']['not null'] = true;
		$this->schema['chiron_source_meta']['fields']['id_user']['unsigned'] = false;

		$this->schema['chiron_source_meta']['fields']['meta_key'] = array();
		$this->schema['chiron_source_meta']['fields']['meta_key']['description'] = chiron_t('source meta key');
		$this->schema['chiron_source_meta']['fields']['meta_key']['type'] = 'text';
		$this->schema['chiron_source_meta']['fields']['meta_key']['size'] = 'normal';

		$this->schema['chiron_source_meta']['fields']['meta_value'] = array();
		$this->schema['chiron_source_meta']['fields']['meta_value']['description'] = chiron_t('source meta value');
		$this->schema['chiron_source_meta']['fields']['meta_value']['type'] = 'text';
		$this->schema['chiron_source_meta']['fields']['meta_value']['size'] = 'normal';


		// Table for Items
		$this->schema['chiron_item'] = array();
		$this->schema['chiron_item']['description'] = chiron_t('Stores all Items'); 
		$this->schema['chiron_item']['primary key'] = array('id');
		$this->schema['chiron_item']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_item']['fields'] = array();

		$this->schema['chiron_item']['fields']['id'] = array();
		$this->schema['chiron_item']['fields']['id']['description'] = chiron_t('item id');
		$this->schema['chiron_item']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_item']['fields']['id']['size'] ='normal';
		$this->schema['chiron_item']['fields']['id']['not null'] = true;
		$this->schema['chiron_item']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_item']['fields']['id_source'] = array();
		$this->schema['chiron_item']['fields']['id_source']['description'] = chiron_t('source id');
		$this->schema['chiron_item']['fields']['id_source']['type'] = 'int';
		$this->schema['chiron_item']['fields']['id_source']['size'] ='normal';
		$this->schema['chiron_item']['fields']['id_source']['not null'] = true;
		$this->schema['chiron_item']['fields']['id_source']['unsigned'] = false;

		$this->schema['chiron_item']['fields']['timestamp'] = array();
		$this->schema['chiron_item']['fields']['timestamp']['description'] = chiron_t('timestamp of the item');
		$this->schema['chiron_item']['fields']['timestamp']['type'] = 'int';
		$this->schema['chiron_item']['fields']['timestamp']['size'] ='normal';
		$this->schema['chiron_item']['fields']['timestamp']['not null'] = true;
		$this->schema['chiron_item']['fields']['timestamp']['unsigned'] = false;

		$this->schema['chiron_item']['fields']['title'] = array();
		$this->schema['chiron_item']['fields']['title']['description'] = chiron_t('item title');
		$this->schema['chiron_item']['fields']['title']['type'] = 'text';
		$this->schema['chiron_item']['fields']['title']['size'] = 'normal';

		$this->schema['chiron_item']['fields']['text'] = array();
		$this->schema['chiron_item']['fields']['text']['description'] = chiron_t('item text');
		$this->schema['chiron_item']['fields']['text']['type'] = 'text';
		$this->schema['chiron_item']['fields']['text']['size'] = 'big';

		$this->schema['chiron_item']['fields']['url'] = array();
		$this->schema['chiron_item']['fields']['url']['description'] = chiron_t('item url');
		$this->schema['chiron_item']['fields']['url']['type'] = 'text';
		$this->schema['chiron_item']['fields']['url']['size'] = 'normal';	
		

		// Table for Item Meta	
		$this->schema['chiron_item_meta'] = array();
		$this->schema['chiron_item_meta']['description'] = chiron_t('Stores Metadata for each Item possibly user depended');
		$this->schema['chiron_item_meta']['primary key'] = array('id');
		$this->schema['chiron_item_meta']['mysql_engine'] = 'InnoDB';
		$this->schema['chiron_item_meta']['fields'] = array();

		$this->schema['chiron_item_meta']['fields']['id'] = array();
		$this->schema['chiron_item_meta']['fields']['id']['description'] = chiron_t('item meta id');
		$this->schema['chiron_item_meta']['fields']['id']['type'] = 'serial';
		$this->schema['chiron_item_meta']['fields']['id']['size'] = 'normal';
		$this->schema['chiron_item_meta']['fields']['id']['not null'] = true;
		$this->schema['chiron_item_meta']['fields']['id']['unsigned'] = false;

		$this->schema['chiron_item_meta']['fields']['id_item'] = array();
		$this->schema['chiron_item_meta']['fields']['id_item']['description'] = chiron_t('item id');
		$this->schema['chiron_item_meta']['fields']['id_item']['type'] = 'int';
		$this->schema['chiron_item_meta']['fields']['id_item']['size'] = 'normal';
		$this->schema['chiron_item_meta']['fields']['id_item']['not null'] = true;
		$this->schema['chiron_item_meta']['fields']['id_item']['unsigned'] = false;

		$this->schema['chiron_item_meta']['fields']['id_user'] = array();
		$this->schema['chiron_item_meta']['fields']['id_user']['description'] = chiron_t('user id');
		$this->schema['chiron_item_meta']['fields']['id_user']['type'] = 'int';
		$this->schema['chiron_item_meta']['fields']['id_user']['size'] = 'normal';
		$this->schema['chiron_item_meta']['fields']['id_user']['not null'] = true;
		$this->schema['chiron_item_meta']['fields']['id_user']['unsigned'] = false;

		$this->schema['chiron_item_meta']['fields']['meta_key'] = array();
		$this->schema['chiron_item_meta']['fields']['meta_key']['description'] = chiron_t('item meta key');
		$this->schema['chiron_item_meta']['fields']['meta_key']['type'] = 'text';
		$this->schema['chiron_item_meta']['fields']['meta_key']['size'] = 'normal';

		$this->schema['chiron_item_meta']['fields']['meta_value'] = array();
		$this->schema['chiron_item_meta']['fields']['meta_value']['description'] = chiron_t('item meta value');
		$this->schema['chiron_item_meta']['fields']['meta_value']['type'] = 'text';
		$this->schema['chiron_item_meta']['fields']['meta_value']['size'] = 'normal';
	}
	
	
	public function query($query){
		$result = mysql_query($query) or print('Query failed: ' . mysql_error());
		$this->result = $result;
		return $result;
	}
	
	public function fetch_array(){
		return mysql_fetch_array($this->result);     		
	}
	

	
	public function get_schema(){
		return $this->schema;
	}
	
	public function schema2query($tablename, $data){
		$query = "";
		$query="create table ".$this->prefix."$tablename (";
		$first=TRUE;
		// Add each Field to the Query
		foreach($data['fields'] as $fieldname=>$fielddata){
			if(!$first){
				$query .= ",";
			}else{
				$first = FALSE;
			}
	            
			$query.="$fieldname ";
			// Check the Field-Types
			if($fielddata['type']=='int'){
				$query.="int ";
			}elseif($fielddata['type']=='text'){
				$query.="text ";
			}elseif($fielddata['type']=='serial'){
				$query.="int ";
			}elseif($fielddata['type']=='varchar'){
				$query.="varchar(".$fielddata['length'].") ";
			}else{
				die("unknown type ".$fielddata['type']);
			}

			if(isset($fielddata['unsigned']) && $fielddata['unsigned']){
				$query.=" unsigned";
			}
			
			if(isset($fielddata['not null']) && $fielddata['not null']){
				$query.=" not null";
			}

			if($fielddata['type']=='serial'){
				$query.=" auto_increment";
			}
		}
		$query.=",constraint primary key (".implode(",", $data['primary key']).")";
		$query.=") ";
		$query.="ENGINE = ".$data['mysql_engine'];
		return $query;
	}
	
	function execute_schema(){
		$results = array();	
		foreach($this->schema as $tablename=>$data){
			$query = $this->schema2query($tablename, $data);
			$results[] = $this->query($query);
		}
		return $results;
	}
	
}


?>