<?php
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
$dotenv->load();

$h = new sampleClass();

$h->Hello();

echo getenv("DB_USERNAME");

$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"),getenv("DB_HOST"));

echo $db->getUser();

echo $db->getDsn();

$db->createPdo();

var_dump($db->getPdo());
