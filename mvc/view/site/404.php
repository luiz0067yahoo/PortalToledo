<?php
//echo $_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php';
$base_server_path_files=$_SERVER['DOCUMENT_ROOT'];
$base_url="https://$_SERVER[HTTP_HOST]";  	
require_once($GLOBALS["base_server_path_files"].'/route.php');
require_once($GLOBALS["base_server_path_files"].'/library/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');
$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Página não Encontrada";
$GLOBALS["og_image"]="https://www.tooeste.com.br/assets/img/logo310x310.jpg";
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

top();
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/content_404.php');
foot();  
   
?>