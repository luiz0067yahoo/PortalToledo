<?php
$base_server_path_files=$_SERVER['DOCUMENT_ROOT'];
$base_url="https://$_SERVER[HTTP_HOST]";  	
require_once($GLOBALS["base_server_path_files"].'/route.php');
require_once($GLOBALS["base_server_path_files"].'/library/functions.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/404.php');
?>