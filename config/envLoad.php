<?php
    //echo realpath(__DIR__.'/../');
    require_once realpath(__DIR__.'/../').'/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__.'/../').'/');
    $dotenv->load();
   