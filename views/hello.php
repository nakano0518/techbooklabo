<?php
require_once '../vendor/autoload.php';

$h = new sampleClass();

$h->Hello();

$db = new DbConnect();

var_dump($db->getUser());
