<?php include('./functions.php');
?><?php
    $app=getParameter("app");
    $functions=getParameter("functions");
    $url=getParameter("url");
    $file_folder=getParameter("file_folder");
    $folder=getParameter("folder");
    $sql=getParameter("sql");
    $params=getParameter("params");
    if(isset($_SESSION["usuario"])&&($_SESSION["usuario"]!="root") ){
        $folder=notUpDir($folder);
        $url=notUpDir($url);
        $file_folder=notUpDir($file_folder);
    }
    switch ($app){
        case "session":
            echo sessionCount();
            break;    
        case "DAOquery":
            echo json_encode(DAOquery($sql,$params));
            break;
        case "authenticity_token":
            echo getAuthenticityToken();
            break;
        case "explorer":{
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
                    $path=$url.DIRECTORY_SEPARATOR.$folder;
                    echo newFolder($path);
                break;
                default:{
                    echo json_encode(listDir($url));
                }
            }
        }
        default:{
            
        }
    }
?>