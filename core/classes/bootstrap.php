<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include 'config.php';
include 'simplepie.inc';
include 'chiron.php';
include 'feed.php';
include 'item.php';

$chiron = new chiron();