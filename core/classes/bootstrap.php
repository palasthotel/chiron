<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include 'config.php';
include 'simplepie.inc';
include 'chiron_db.php';
include 'chiron_core.php';
include 'source.php';
include 'item.php';
include 'user.php';
include 'public.php';
include 'private.php';

$chiron_db = new chiron_db(CHIRON_DB_SRV, CHIRON_DB_USR, CHIRON_DB_PWD, CHIRON_DB_DBS, CHIRON_DB_PRE);
$chiron = new chiron_core($chrion_db);
