<?php 
    include_once 'define.php';
    Session::init();
	function __autoload($className){
        $filename = "libs/". $className .".php";
        if (file_exists($filename)) {
            include_once($filename);
        } else {
            echo "The file $filename does not exist";
        }

    }
 ?>