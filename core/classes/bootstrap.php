<?php

include 'chiron_db.php';
include 'chiron_core.class.php';
include 'chiron_core.db-class.php';
include 'source.class.php';
include 'source.db-class.php';
include 'category.class.php';
include 'category.db-class.php';
include 'item.class.php';
include 'item.db-class.php';
include 'subscription.class.php';
include 'subscription.db-class.php';

$chiron_db = new chiron_db(CHIRON_DB_SRV, CHIRON_DB_USR, CHIRON_DB_PWD, CHIRON_DB_DBS, CHIRON_DB_PRE);
$chiron = new chiron_core($chiron_db);

// Helper-Function for UTF8-Frakups
// Checking wether a String is UTF8
function chiron_is_utf8($string) {
    return (bool) preg_match('//u', $string);
} 


// Making all Strings UTF8, which aren't UTF8
function chiron_clean_string($string){
	if(!chiron_is_utf8($string)){
		return utf8_encode($string);
	}else{
		return $string;
	}
} 
