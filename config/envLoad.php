<?php
    require_once realpath(__DIR__.'/../').'/vendor/autoload.php';
    if(__DIR__ == '/home/ec2-user/environment/config'){ //ローカルやAWSデプロイの際に必要であったが、Herokuでは不要(GUIで設定)
        $dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__.'/../').'/');
        $dotenv->load();  
    }
    
    
   