<?php
		require_once __DIR__.'/vendor/autoload.php';

		
		$dotenv = Dotenv\Dotenv::createImmutable('../env/');
		$dotenv->load();

		$name = getenv('APP_NAME');
		echo $name;

