<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	class ControllerReports{
		private $params;
		private $report;
		public function __construct(){
	        $functions=getParameter("functions");
            $url=getParameter("url");
            $file_folder=getParameter("file_folder");
            if(!isset($_SESSION)) session_start();
            if(isset($_SESSION["usuario"]))
            switch ($functions){
                case "delete":
                	$list=explode(",",$file_folder);
                	foreach ($list as $item){
                        delTree($item);
                	}
                break;
                case "upload":
                    echo json_encode(upload($url));
                break;
                case "new_folder":
    				$folder=getParameter("folder");
    				$path=$folder;
    				if(!empty($url))
    					$path=$url.DIRECTORY_SEPARATOR.$folder;
                    echo newFolder($path);
                break;
    			case "new_file":
    				$file=getParameter("file");
    				$path=$file;
    				if(!empty($url))
    					$path=$url.DIRECTORY_SEPARATOR.$file;
                    echo newFile($path);
                break;
                case "webPath":
                    echo getWebPathExplorer();
                break;
                default:{
    				$folder=getParameter("folder");
    				$path=$url;
    				if(isset($folder))
    					$path=$url.DIRECTORY_SEPARATOR.$folder;
                    echo json_encode(listDir($path));
                }
            }
		}
	}
	if(controlAcess())
		$Controller = new ControllerReports();
?>