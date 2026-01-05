<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/library/functions.php';
	$app=getParameter("app");
    $functions=getParameter("functions");
    $url=getParameter("url");
    $file_folder=getParameter("file_folder");
    if(isset($_SESSION["usuario"])&&($_SESSION["usuario"]!="root") ){
		$folder=getParameter("folder");
        $folder=notUpDir($folder);
        $url=notUpDir($url);
        $file_folder=notUpDir($file_folder);
    }
    switch ($app){
        case "session":
			if($functions=="logout"){
				logout();
			}
			else echo sessionCount();
            break;    
        case "DAOquery":
			session_start();
			if(isset($_SESSION["usuario"])){
			    $params=(getParameter("params"));
				$sql=getParameter("sql");
				echo json_encode(DAOquery($sql,$params,true,""));
			}
            break;
		case "codeEditor":
			session_start();
			if(isset($_SESSION["usuario"])){
				if($functions=="loadServerFile")
					loadServerFile($url);
				else if($functions=="saveServerFile"){
					$code=getParameter("code");
					echo saveServerFile($url,$code);
				}
			}
            break;
		case "ssh":
			session_start();
			if(isset($_SESSION["usuario"])){
				$code=getParameter("code");
				echo ssh_server_exec($code);
			}
            break;
        case "authenticity_token":
            echo getAuthenticityToken();
            break;
        case "explorer":{
			session_start();
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
					if(!isset($url))
						$path=$url.DIRECTORY_SEPARATOR.$folder;
                    echo newFolder($path);
                break;
				case "new_file":
					$file=getParameter("file");
					$path=$file;
					if(!isset($url))
						$path=$url.DIRECTORY_SEPARATOR.$file;
                    echo newFile($path);
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
        default:{
            
        }
    }
?>