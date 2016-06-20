<?php 

spl_autoload_register(function ($className){
		$className = str_replace('\\', '/', $className);
		$file = $_SERVER['DOCUMENT_ROOT'].$className.".php";
		require_once $file;
	});
	
	//if you want this file to be included automatically on every page on your server, set it as global file using htaccess..add the below line to your .htaccess file and remove the the forward slashes.
	
	//php_value auto_prepend_file /full/path/to/file/autoloader.php
