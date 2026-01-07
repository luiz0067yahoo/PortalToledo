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
                case "ckeditor_upload":
                    $CKEditorFuncNum = getParameter('CKEditorFuncNum');
                    $upload_result = upload($url); // Call existing upload function
                    
                    // The upload functions returns array('field_name' => array('file_name'))
                    // We expect only one file for CKEditor drag&drop or simple upload
                    $keys = array_keys($upload_result);
                    if (count($keys) > 0) {
                        $field_name = $keys[0];
                        $files = $upload_result[$field_name];
                        if (count($files) > 0) {
                            $filename = $files[0];
                            // Construct the web path
                            $webPath = getWebPathExplorer() . ($url ? $url . '/' : '') . $filename;
                            
                            // Return the script for CKEditor
                             $message = 'Image uploaded successfully';
                             echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$webPath', '$message');</script>";
                        } else {
                             echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'Unable to upload file');</script>";
                        }
                    } else {
                         echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '', 'No file uploaded');</script>";
                    }
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