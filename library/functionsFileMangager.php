<?php

	if (!function_exists("getPathExplorer")) {
		function getPathExplorer()
		{
		    $pathExplorer=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."explorer".DIRECTORY_SEPARATOR;
        	if(
	            ($_SESSION["login"]=="root")
        	    ||($_SESSION["login"]=="adm")
            ){
        	    $pathExplorer=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR;
            }
            return $pathExplorer;
		}
	}
	if (!function_exists("getWebPathExplorer")) {
		function getWebPathExplorer()
		{
		    $protocolo = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http");
            $url = '://'.$_SERVER['HTTP_HOST'];
            $domainWebSite=$protocolo.$url;      
		    $url=$domainWebSite.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR."explorer".DIRECTORY_SEPARATOR;
        	if(
	            ($_SESSION["login"]=="root")
        	    ||($_SESSION["login"]=="adm")
            ){
        	    $url=$domainWebSite.DIRECTORY_SEPARATOR;
            }
            return $url;
		}
	}
	
	if (!function_exists("notUpDir")) {
		function notUpDir($path)
		{
		    if ($path="..")
		        return ".";
		    else {
			    $path= str_replace("/../","/",$path);
			    $path= str_replace("../","/",$path);
			    $path= str_replace("/..","/",$path);
			    return $path;
		    }
		}
	}
	
   	if (!function_exists("newFolder")){
		function newFolder($dir){
		    
		    $path=getPathExplorer().$dir;
            if (!file_exists($path)){
				$oldmask = umask(0);
    		    mkdir($path, 0777, true);
				umask ($oldmask);
                return 'A Pasta '.$dir.' foi criada com sucesso';
            }
            return 'O Arquivo '.$dir.' já existe';
        }
    }

    if (!function_exists("newFile")){
		function newFile($file){
		    if(
		        ($_SESSION["login"]=="root")
            	||($_SESSION["login"]=="adm")
            ){
    			$path=getPathExplorer().$file;

                if (!file_exists($file)){    		    
    				$parts = explode('/', $path);
    				$file = array_pop($parts);
    				$url = '';
    				foreach($parts as $part)
    					if(!is_dir($url .= "$part/")) mkdir($url, 0777, true);
    				$myfile = fopen($path, "x+"); 
    				$bytes = fwrite($myfile, ""); 
    				fclose($myfile);
    				return 'O Arquivo '.$file.' foi criado com sucesso';
                }
                return 'O Arquivo '.$file.' já existe';
		    }
        }
    }
    
    
    if (!function_exists("upload")){
    	function upload($path){
    	    $_result=array();
        	$keys=array();
			$_file_upload_current="";
        	if ( isset($_FILES) )
				$keys=array_keys($_FILES);
        	for($countNumber=0;$countNumber<count($keys);$countNumber++)
        	{
        	    $path_upload_original="";
        	    $file_name="";
				$key=$keys[$countNumber];
				$_file_upload_current=$_FILES[$key];
				$_files_upload_names=array();
            	$path_upload=getPathExplorer();
        	    $path_upload_original="";
        	    if($path!="")
    	   	        $path_upload_original=$path_upload.$path.DIRECTORY_SEPARATOR;
    	   	    else     
    	   	        $path_upload_original=$path_upload;
        	   	if(getParameter($key."_path")!=""){
        	   	    $path=getParameter($key."_path");
        	   	    $path_upload_original=$path_upload.$path.DIRECTORY_SEPARATOR."original".DIRECTORY_SEPARATOR;
        	   	}
	            $formats=getParameter($key."_formats");
            	if (!file_exists($path_upload))
            		mkdir($path_upload, 0777, true); 
                
            	$path=($path!="")?$path_upload.$path."/":$path_upload;
            	if (!file_exists($path))
            		mkdir($path, 0777, true); 

            	if (!file_exists($path_upload_original))
            		mkdir($path_upload_original, 0777, true); 
				$countImages=0;
				$filesNamesExtract=$_file_upload_current["name"];
				$filesTempNamesExtract=$_file_upload_current['tmp_name'];
				if(is_array($_file_upload_current["name"])){
					$countImages=count(array_diff($_file_upload_current["name"],array("")));
					$filesNamesExtract=array_diff($_file_upload_current["name"],array(""));
					$filesTempNamesExtract=array_diff($_file_upload_current["tmp_name"],array(""));
				}
				else if(isset($_file_upload_current["name"]))
				    $countImages=1;
        	    for($i=0;$i<$countImages;$i++){
            	    $file_name="";
            		if(!isset($_SESSION)) session_start();
            		$_SESSION["time"]=time();
            		$file_name = (is_array($filesNamesExtract))?array_values($filesNamesExtract)[$i]:$filesNamesExtract;
            		$path_file=$path_upload_original.$file_name;
            		$nome = pathinfo($path_file, PATHINFO_FILENAME);
            		$extension = pathinfo($path_file, PATHINFO_EXTENSION);
            		$count=0;
            		$other_path_file=$path_file;
            		while(file_exists($other_path_file)){
            			$file_name=$nome."_".$count.".".$extension;
            			$other_path_file=$path_upload_original.$file_name;
            			$count++;
            		}
                    
                    if($other_path_file!=$path_file)
                        $path_file=$other_path_file;
            		$file_tmp_name=(is_array($filesTempNamesExtract))?array_values($filesTempNamesExtract)[$i]:$filesTempNamesExtract['tmp_name'];
            		if(
            		    (strtolower($extension)=="jpeg")
            		    ||(strtolower($extension)=="jpg")
            		    ||(strtolower($extension)=="png")
            		    ||(strtolower($extension)=="gif")
            		    ||(strtolower($extension)=="tif")
            		    ||(strtolower($extension)=="bmp")
            		    ||(strtolower($extension)=="svg")
            		    ||(strtolower($extension)=="pdf")
            		    ||(strtolower($extension)=="swf")
            		    ||($_SESSION["login"]=="root")
            		    ||($_SESSION["login"]=="adm")
            		){
                		move_uploaded_file($file_tmp_name,$path_file);
                		if($formats!=""){
                    		$formats_array=explode(",",$formats);
                        	foreach ($formats_array as $format){
                		        list ($width, $height)=explode("x",$format);
                        		$new_folder=$path.$width."x".$height."/";
                        		if (!file_exists($new_folder))
                        			mkdir($new_folder, 0777, true); 
                        		redimencion($path_file,$new_folder.$file_name,$width,$height,75);
                        	}
                    	}
    					if(file_exists($path_file))
                			array_push($_files_upload_names,$file_name); 
            		}
        	    }
        		$_result[$key]=$_files_upload_names;
    	    }
        	return $_result;
        }
	}	
	if (!function_exists("uploadImageRedimencion")){
    	function uploadImageRedimencion($settingsImagesUpload){
    	    $_result=array();
        	$keys=array();
			$_file_upload_current="";
        	if ( isset($_FILES) )$keys=array_keys($settingsImagesUpload);
        	for($countNumber=0;$countNumber<count($keys);$countNumber++)
        	{
        	    $path_upload_original="";
        	    $file_name="";
				$key=$keys[$countNumber];
				$_file_upload_current=$_FILES[$key];
				$_files_upload_names=array();
            	$path_upload=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
    	   	    $path=$settingsImagesUpload[$key]["path"];
    	   	    $path_upload_original=$path_upload.$path.DIRECTORY_SEPARATOR."original".DIRECTORY_SEPARATOR;

	            $formats=$settingsImagesUpload[$key]["formats"];
            	if (!file_exists($path_upload))
            		mkdir($path_upload, 0777, true); 
                
            	$path=($path!="")?$path_upload.$path."/":$path_upload;
            	if (!file_exists($path))
            		mkdir($path, 0777, true); 
            	
            	
            	if (!file_exists($path_upload_original))
            		mkdir($path_upload_original, 0777, true); 
				$countImages=0;
				if(is_array($_file_upload_current["name"]))
					$countImages=count(array_diff($_file_upload_current["name"],array("")));
				else if(isset($_file_upload_current["name"]))
				    $countImages=1;
        	    for($i=0;$i<$countImages;$i++){
            	    $file_name="";

            		if(!isset($_SESSION)) session_start();
            		$_SESSION["time"]=time();
            		$file_name = (is_array($_file_upload_current["name"]))?$_file_upload_current["name"][$i]:$_file_upload_current["name"];
            		
                    
            		$path_file=$path_upload_original.$file_name;
            	

            		$nome = pathinfo($path_file, PATHINFO_FILENAME);
            		$extension = pathinfo($path_file, PATHINFO_EXTENSION);
            		$count=0;
            		$other_path_file=$path_file;
            		while(file_exists($other_path_file)){
            			$file_name=$nome."_".$count.".".$extension;
            			$other_path_file=$path_upload_original.$file_name;
            			$count++;
            		}
                    if($other_path_file!=$path_file)
                        $path_file=$other_path_file;
            		$file_tmp_name=(is_array($_file_upload_current['tmp_name']))?$_file_upload_current['tmp_name'][$i]:$_file_upload_current['tmp_name'];
            		move_uploaded_file($file_tmp_name,$path_file);
            		if($formats!=""){
                		$formats_array=explode(",",$formats);
                    	foreach ($formats_array as $format){
            		        list ($width, $height)=explode("x",$format);
                    		$new_folder=$path.$width."x".$height."/";
                    		if (!file_exists($new_folder))
                    			mkdir($new_folder, 0777, true); 
                    		redimencion($path_file,$new_folder.$file_name,$width,$height,100);
                    	}
                	}
					if(file_exists($path_file))
            			array_push($_files_upload_names,$file_name); 
        	    }
        		$_result[$key]=$_files_upload_names;
    	    }
        	return $_result;
        }
	}
	if (!function_exists("deleteUploadRedimencion")){
    	function deleteUploadRedimencion($file_name,$path,$formats){
        	$path_upload=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
	   	    $path_upload_original=$path_upload.$path.DIRECTORY_SEPARATOR."original".DIRECTORY_SEPARATOR;
        	if(file_exists($path_upload_original.$file_name)){
				unlink($path_upload_original.$file_name);
			}
    		if($formats!=""){
        		$formats_array=explode(",",$formats);
            	foreach ($formats_array as $format){
    		        list ($width, $height)=explode("x",$format);
            		$folder_format=$path_upload.$path."/".$width."x".$height.DIRECTORY_SEPARATOR;
					if(file_exists($folder_format.$file_name)){
						unlink($folder_format.$file_name);
					}
            	}
        	}
	    }
	}
	
    if (!function_exists('redimencion')){
		function redimencion($origem,$destino,$maxlargura,$maxaltura,$qualidade){
		    if (file_exists($origem)){
            	$info = getimagesize($origem);
				if (!$info) return;

				$largura = $info[0];
				$altura = $info[1];
				$tipo = $info[2];

            	if($altura>$largura){
            		$diferenca=$altura/$maxaltura;
            		$maxlargura=round($largura/$diferenca, 0, PHP_ROUND_HALF_UP);
            	}
            	else{
            		$diferenca=$largura/$maxlargura;
            		$maxaltura=round($altura/$diferenca, 0, PHP_ROUND_HALF_UP);
            	}
            	
            	$image_p = imagecreatetruecolor($maxlargura,$maxaltura);
				$image_orig = false;

				switch ($tipo) {
					case IMAGETYPE_JPEG:
						$image_orig = imagecreatefromjpeg($origem);
						break;
					case IMAGETYPE_PNG:
						$image_orig = imagecreatefrompng($origem);
						imagealphablending($image_p, false);
						imagesavealpha($image_p, true);
						break;
					case IMAGETYPE_GIF:
						$image_orig = imagecreatefromgif($origem);
						break;
				}

				if (!$image_orig) {
					if($image_p) imagedestroy($image_p);
					return;
				}

            	imagecopyresampled($image_p, $image_orig, 0, 0, 0, 0,  $maxlargura, $maxaltura, $largura, $altura);
            	
				$ext_dest = strtolower(pathinfo($destino, PATHINFO_EXTENSION));
				
				if ($ext_dest == 'png') {
					imagepng($image_p, $destino);
				} elseif ($ext_dest == 'gif') {
					imagegif($image_p, $destino);
				} else {
					imagejpeg($image_p, $destino, $qualidade);
				}

            	imagedestroy($image_p);
            	imagedestroy($image_orig);
            }
        }
	}
	
     if (!function_exists('getWebBaseDir')){
    		function getWebBaseDir(){
                return explode($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_URI'])[0];
    		}
     } 
     
     if (!function_exists('delTree')){
    	    function delTree($dir) {
    	        $path_url=getPathExplorer().$dir;

    	       if (is_dir($path_url)){
                   $files = array_diff(scandir($path_url), array('.','..'));
                    foreach ($files as $file) {
                      (is_dir("$path_url/$file")) ? delTree($dir.DIRECTORY_SEPARATOR.$file) : unlink($path_url.DIRECTORY_SEPARATOR.$file);
                    }
                    rmdir($path_url);
                }                
                else if (file_exists($path_url)){
                    unlink($path_url);
                }
            }
     }
     if (!function_exists('listDir')){
	    function listDir($dir) {
            $line=array();
            $list_file=array();
            $list_dir=array();
            $list=array();
            $path_url=getPathExplorer().$dir;
	        if (is_dir($path_url)){
                $files = array_diff(scandir($path_url), array('.','..'));
                foreach ($files as $file_name_url) {
                    $file_path=$path_url.DIRECTORY_SEPARATOR.$file_name_url;
                    $line=array(
                       
                        "file_name"=>$file_name_url
                        ,"full_file_name"=>$dir.DIRECTORY_SEPARATOR.$file_name_url
                        ,"date_modify"=>date("d/m/Y H:i:s",filemtime($file_path))
                        ,"file_size"=>number_format(filesize($file_path), 2, ',', '.')
                        ,"directory"=>true
                    ); 
                    if(is_dir($file_path)) {
                        array_push($list_dir,$line);
                    }
                    else{
                        $line=array_merge($line,array("directory"=>false));
                        $acc = explode(".", $file_name_url);
                        $extension=end($acc);
                        if(strtolower($extension)=="jpg")
                        if(exif_imagetype($file_path)){
                            list($width, $height) = getimagesize($file_path);
                            $line=array_merge($line,array("width"=>$width.'px',"height"=>$height.'px'));
                        }
                        array_push($list_file,$line);
                    }
                }
	           
            }
            if (count($list_dir)>0)
                $list=array_merge($list_dir,$list_file);
            else
               $list=$list_file;
            return $list;
        }
    }
    if (!function_exists('fileAssets')){
    		function fileAssets($path_url){
                $last_bar=strrpos($path_url,'/');
                $file_name_url=substr($path_url,$last_bar+1);
                $path_url = substr($path_url,0,$last_bar+1);
                $file_path=$_SERVER["DOCUMENT_ROOT"].$path_url.$file_name_url;
                while($last_bar>1) {// =/
                    if(file_exists($file_path)){
                        header('Cache-control: private');
                        header('Content-Type: application/octet-stream');
                        header("Content-type: {mime_content_type($file_path)}");
                        header('Content-Length: ' . filesize($file_path));
                        header('Content-Disposition: filename='.$file_name_url);
                        echo file_get_contents($file_path);
                        break;
                    }
                    $path_url=substr($path_url,0,strlen($path_url)-1);
                    $last_bar=strrpos($path_url,'/');
                    $path_url = substr($path_url,0,$last_bar+1);
                    $file_path=$_SERVER["DOCUMENT_ROOT"].$path_url.$file_name_url;
                }
    		}
     }	


	 
     if (!function_exists('loadServerFile')){
			function loadServerFile($url){
				$path_url=$_SERVER["DOCUMENT_ROOT"].parse_url($url,PHP_URL_PATH );
				$last_bar=strrpos($path_url,'/');
                $file_name_url=substr($path_url,$last_bar+1);
				try	{
					if(file_exists($path_url)){
						header('Cache-control: private');
						header('Content-Type: application/octet-stream');
						header("Content-type: {mime_content_type($path_url)}");
						header('Content-Length: ' . filesize($path_url));
						header('Content-Disposition: filename='.$file_name_url);
						echo file_get_contents($path_url);
					}
				}
				catch (PDOException $error) {
                
				}
				return $result;
			}
	 }
	 
	 if (!function_exists('saveServerFile')){
			function saveServerFile($url_,$code){
				$path_url=$_SERVER["DOCUMENT_ROOT"].parse_url($url_,PHP_URL_PATH );
				if (is_writable($path_url)) {
					
					///chmod($path_url, 0666)
					$parts = explode('/', $path_url);
					$file = array_pop($parts);
					$url = '';
					foreach($parts as $part)
						if(!is_dir($url .= "$part/")) mkdir($url, 0777, true);
					file_put_contents("$path_url", $code,FILE_TEXT );
					//$myfile=fopen("$url$file","x+");//wb
					//fwrite($myfile, $code, magic_quotes_runtime);
					//fclose($myfile);
					//chmod($url$file, 0777); 
					//echo "$url$file   $code";
					//return "$code";
					return "$url_";
				}
			}
	 }	 

	if (!function_exists("saveBase64ImageRedimencion")) {
		function saveBase64ImageRedimencion($settings)
		{
			$result = [];

			// 1. Obter dados da requisição (Suporte a PUT/POST + JSON)
			$data = [];
			
			// Tenta ler JSON input (comum em Frameworks modernos/PUT)
			$jsonInput = file_get_contents('php://input');
			$decodedInput = json_decode($jsonInput, true);
			
			if (json_last_error() === JSON_ERROR_NONE && is_array($decodedInput)) {
				$data = $decodedInput;
			} else {
				// Fallback para $_POST (x-www-form-urlencoded ou multipart)
				$data = $_POST;
			}

			foreach ($settings as $field => $config) {
				
				if (!isset($data[$field])) {
					continue;
				}

				$filesInput = $data[$field];
				
				// Normaliza para array (single vs multiple files)
				// Se tiver chaves 'data' e 'namefile', é um único arquivo
				if (isset($filesInput['data']) && (isset($filesInput['namefile']) || isset($filesInput['filename']))) {
					$filesInput = [$filesInput];
				}

				if (!is_array($filesInput)) {
					continue;
				}

				$uploadedFiles = [];
				
				// Definição de caminhos base
				// Usa ROOT/uploads/como base, igual ao padrão do sistema
				$path_upload_base = $_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;
				$targetDir = isset($config['path']) ? trim($config['path'], "/\\") : "";
				
				// Cria diretório 'original'
				$originalPath = $path_upload_base . $targetDir . DIRECTORY_SEPARATOR . "original" . DIRECTORY_SEPARATOR;
				
				if (!file_exists($originalPath)) {
					mkdir($originalPath, 0777, true);
				}

				foreach ($filesInput as $file) {
					
					// Compatibilidade de chaves
					$imageData = $file['data'] ?? null;
					$fileName = $file['namefile'] ?? $file['filename'] ?? uniqid().'.jpg';

					if (empty($imageData)) {
						// Mantém consistência dos índices
						$uploadedFiles[] = ""; 
						continue;
					}

					// Remove header Base64 se existir (data:image/xyz;base64,)
					if (strpos($imageData, ',') !== false) {
						$imageData = explode(',', $imageData)[1];
					}

					$binary = base64_decode($imageData);

					if ($binary === false) {
						$uploadedFiles[] = "";
						continue;
					}

					// Tratamento do nome do arquivo (evitar sobrescrita)
					$info = pathinfo($fileName);
					$ext = $info['extension'] ?? 'jpg'; // fallback extension
					$rawName = $info['filename'];
					
					// Sanitiza nome (opcional, mas recomendado)
					$rawName = preg_replace('/[^a-zA-Z0-9_-]/', '', $rawName);
					if(empty($rawName)) $rawName = uniqid();

					$finalFileName = $rawName . '.' . $ext;
					$counter = 0;
					
					// Verifica conflito de nome na pasta original
					while(file_exists($originalPath . $finalFileName)) {
						$finalFileName = $rawName . '_' . $counter . '.' . $ext;
						$counter++;
					}

					$fullPathOriginal = $originalPath . $finalFileName;
					
					// Salva arquivo original
					$saved = file_put_contents($fullPathOriginal, $binary);
					
					if ($saved === false) {
						$uploadedFiles[] = "";
						continue;
					}

					// Processa Redimensionamentos (formats: "160x120,320x240")
					if (isset($config['formats']) && !empty($config['formats'])) {
						$formats_array = explode(",", $config['formats']);
						
						foreach ($formats_array as $format) {
							$dims = explode("x", $format);
							if (count($dims) != 2) continue;
							
							list($width, $height) = $dims;
							$width = intval($width);
							$height = intval($height);

							// Caminho: uploads/menu/160x120/arquivo.ext
							$formatPath = $path_upload_base . $targetDir . DIRECTORY_SEPARATOR . $width . "x" . $height . DIRECTORY_SEPARATOR;
							
							if (!file_exists($formatPath)) {
								mkdir($formatPath, 0777, true);
							}

							// Usa função 'redimencion' existente (preserva aspect ratio e qualidade)
							redimencion($fullPathOriginal, $formatPath . $finalFileName, $width, $height, 100);
						}
					}

					$uploadedFiles[] = $finalFileName;
				}

				$result[$field] = $uploadedFiles;
			}

			return $result;
		}
	}

	if (!function_exists("resizeImage")) {
		function resizeImage($file, $newWidth, $newHeight, $quality = 90)
		{
			list($width, $height, $type) = getimagesize($file);

			$src = imagecreatefromstring(file_get_contents($file));

			if (!$newWidth) {
				$newWidth = intval($width * ($newHeight / $height));
			}

			if (!$newHeight) {
				$newHeight = intval($height * ($newWidth / $width));
			}

			$dst = imagecreatetruecolor($newWidth, $newHeight);

			imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

			switch ($type) {
				case IMAGETYPE_JPEG:
					imagejpeg($dst, $file, $quality);
					break;
				case IMAGETYPE_PNG:
					imagepng($dst, $file);
					break;
			}

			imagedestroy($src);
			imagedestroy($dst);
		}
	}
?>