<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include 'config.php';
include 'simplepie.inc';
include 'chiron_db.php';
include 'chiron_core.class.php';
include 'chiron_core.db-class.php';
include 'source.class.php';
include 'source.db-class.php';
include 'category.class.php';
include 'category.db-class.php';
include 'item.class.php';
include 'item.db-class.php';
include 'user.php';
include 'public.php';
include 'private.php';

$chiron_db = new chiron_db(CHIRON_DB_SRV, CHIRON_DB_USR, CHIRON_DB_PWD, CHIRON_DB_DBS, CHIRON_DB_PRE);
$chiron = new chiron_core($chrion_db);
