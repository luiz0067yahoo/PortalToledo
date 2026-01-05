<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    $Connection=null;
 	if (!function_exists("BlockSQLInjection")) {
		function BlockSQLInjection($str)
		{
			return str_replace("'","''",$str);
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
	if (!function_exists("DAOopen")) {
		function DAOopen()
		{
            try { 
                //session_start();
                if (!isset($_SESSION["Connection"])){
                    $Connection=null;
                	$servername		=	"localhost";
                	$username		=	"u455891610_dogecoin";
                	$password	=	"G/1PN~$[4Uc";
                	$database	=	"u455891610_elonmusk";
                	$str_connecta = "mysql:host=$servername;dbname=$database;";
                	$dsn_Options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
                    $Connection = new PDO($str_connecta, $username, $password	, $dsn_Options);
        	        $_SESSION["Connection"]=$Connection;
                }
                return $_SESSION["Connection"];
            } catch (PDOException $error) {
                echo 'Connection error: ' . $error->getMessage();
            }
		}
	}
	if (!function_exists("DAOclose")) {
		function DAOclose()
		{
		   //$Statement=null;
			$Connection=null;
			$_SESSION["Connection"]=$Connection;
		}
	}
	
	if (!function_exists("DAOquery")) {
	    function DAOquery($sql,$params){
			$data=array();
			$titles=array();
			$result=array();
	        if ($sql!="")
    		try {
    		    $Connection_=DAOopen();
    		    $Statement__ = null;
    			$Statement__ = $Connection_->prepare($sql);
    			if (is_array($params))
    			foreach ($params as $key_param => $value){
    			     $Statement__->bindParam(":$key_param",$params[$key_param]);
    			}
    			if ($Statement__->execute()) {
    			    while ($result_= $Statement__->fetch(PDO::FETCH_ASSOC)) 
        			{
        			    $linhe_=array();
        			    foreach ($result_ as $key_result => $value_result ){
        			       if(count($data)==0)
        				    array_push($titles,$key_result); 
        				   array_push($linhe_,$value_result); 
        			    }
        				array_push($data,$linhe_);
        			}
        			//DAOclose();
        			if (count($data)>0)
        			    $result= array("title"=>$titles,"data"=>$data);
    			}
    		}
    		catch (Exception $error) {
    		   $result= 'Error: ' . $error->getMessage();
    		}
	        return $result;
	    }
	}
	
	
	if (!function_exists("DAOqueryInsert")) {
	    function DAOqueryInsert($table,$params){
	        try {
	            $sql="INSERT INTO $table ";
    	        $fields_array=array_keys($params);
    	        $field_str=implode(",",$fields_array);
    	        $field_params_str=":".join(",:",$fields_array);
    	        $sql.=" ($field_str) VALUES ($field_params_str);";
    	        DAOquery($sql,$params);
                DAOquery("commit;","");
                return true;
	        }
	        catch (Exception $error) {
	            return false;
	        }
	    }
	}
	
	if (!function_exists("DAOqueryUpdate")) {
	    function DAOqueryUpdate($table,$params,$conditions_params){
	        try {
    	        $fields_values="";
    	        $conditions="";
    	        foreach ($params as $key => $value  )
    	            $fields_values.=($fields_values=="")?" $key = :$key "  : ", $key = :$key ";
    	        foreach ($conditions_params as $key => $value  )
    	            $conditions=($conditions=="")?"($key= :$key)"  : " and ($key= :$key)";
    	        $sql="UPDATE $table SET $fields_values WHERE ( $conditions );";
    	        $params=($params+$conditions_params);
    	        DAOquery($sql,$params);
                DAOquery("commit;","");
                return true;
	        }
	        catch (Exception $error) {
	            return false;
	        }
	    }
	}
	
	if (!function_exists("DAOqueryDelete")) {
	    function DAOqueryDelete($table,$conditions_params){
	        $conditions="";
	        foreach ($conditions_params as $key => $value  ){
	            $conditions.=($conditions=="")?"($key= :$key)"  : " and ($key= :$key)";
	        }
	        $sql="DELETE FROM $table WHERE ($conditions)";
	        return DAOquery($sql,$conditions_params);
	    }
	}
	
	if (!function_exists("DAOquerySelect")) {
	    function DAOquerySelect($table,$fields_params,$joins,$conditions_params){
	        $fields_str="";
	        $conditions="";
	        foreach ($fields_params as $fields_key => $fields_value){
	            if(!strpos($fields_value,"."))
	                $fields_params[$fields_key]=$table.".".$fields_value;
	        }
	        $fields_str= implode(",",$fields_params);
	        $sql="SELECT $fields_str FROM $table $joins";
	        if(is_array($conditions_params))
	        foreach ($conditions_params as $key => $value  ){
                if(!strpos($key,"."))
	                $conditions.=($conditions=="")?"($table.$key = :$key)"  : " or ($table.$key = :$key)";
	           else
	                $conditions.=($conditions=="")?"($key = :$key)"  : " or ($key = :$key)";
	        }
	        if ($conditions!="")
	          $sql.=" WHERE ($conditions)";
	        return DAOquery($sql,$conditions_params);
	    }
	}
	
	if (!function_exists("DAOquerySelectLikeOR")) {
	    function DAOquerySelectLikeOR($table,$fields_params,$joins,$conditions_params,$orderby=""){
	        $fields_str="";
	        $conditions="";
	        foreach ($fields_params as $fields_key => $fields_value){
	            if(!strpos($fields_value,"."))
	                $fields_params[$fields_key]=$table.".".$fields_value;
	        }
	        $fields_str= implode(",",$fields_params);
	        $sql="SELECT $fields_str FROM $table $joins";
	        if(is_array($conditions_params))
	        foreach ($conditions_params as $key => $value  ){
	            $conditions_params[$key]="%$value%";
	            $conditions.=($conditions=="")?"($key like :$key)"  : " or ($key like :$key)";
	        }
	        if ($conditions!="")
	          $sql.=" WHERE ($conditions)";
	        if ($orderby!="")
	          $sql.=" ORDER BY $orderby";
	        return DAOquery($sql,$conditions_params);
	    }
	}
	
	if (!function_exists("DAOquerySelectLast")) {
	    function DAOquerySelectLast($table,$fields_params,$joins){
	        foreach ($fields_params as $fields_key => $fields_value){
	            if(!strpos($fields_value,"."))
	                $fields_params[$fields_key]=$table.".".$fields_value;
	        }
	        $fields= implode(",",$fields_params);
	        $sql="SELECT $fields FROM $table $joins WHERE ($table.id=(SELECT MAX(t.ID) FROM $table t))";
	        return DAOquery($sql,"");
	    }
	}
	
	if (!function_exists("DAOquerySelectById")) {
	    function DAOquerySelectById($table,$fields_params,$joins,$id){
	        $conditions_params=array("id"=>"$id");
	        return DAOquerySelect($table,$fields_params,$joins,$conditions_params);
	    }
	}
	
	if (!function_exists("getParameter")){
		function getParameter($parameter)
		{
			return isset($_POST[$parameter])?$_POST[$parameter]:(isset($_GET[$parameter])?$_GET[$parameter]:"");
		}
	}	
	
	if (!function_exists("sessionCount")){
		function sessionCount()
		{
		    session_start();
		    $time_= "00:00";
    		$agora= time();
        	$minutos=$agora-$_SESSION["time"];
        	$dez_minutos=10*60;
        	$restantes=$dez_minutos-$minutos;
        	if ($restantes<=0){
        		session_destroy();
        	}
        	else{
        		 $time_= date("i:s",$restantes);
        	}
        	return  $time_;
		}
	}
	
	if (!function_exists("getIDYouTube")) {
		function getIDYouTube($url){
            $parts = parse_url($url);
            if(isset($parts['query'])){
                parse_str($parts['query'], $qs);
                if(isset($qs['v'])){
                    return $qs['v'];
                }else if(isset($qs['vi'])){
                    return $qs['vi'];
                } 
            }
            if(isset($parts['path'])){
                $path = explode('/', trim($parts['path'], '/'));
                return $path[count($path)-1];
            }
            return false;
        }
		
	}
	
    if (!function_exists("setIDYouTube")){
		function setIDYouTube(){
              return "http://www.youtube.com/watch?v=$id";
        }
	}	
	
	if (!function_exists("createAuthenticityToken")){
		function createAuthenticityToken(){
            if(!isset($_SESSION)) session_start();
            $_SESSION["AuthenticityToken"]=hash('sha512', time());
            return $_SESSION["AuthenticityToken"];
        }
       
	}
	if (!function_exists("createAuthenticityToken")){
		function createAuthenticityToken(){
            if(!isset($_SESSION)) session_start();
            $_SESSION["AuthenticityToken"]=hash('sha512', time());
            return $_SESSION["AuthenticityToken"];
        }
       
	}
	
	if (!function_exists("checkAuthenticityToken")){
		function checkAuthenticityToken($AuthenticityToken){
            if(!isset($_SESSION)) session_start();
            if($_SESSION["AuthenticityToken"]!=$AuthenticityToken){
                echo "Erro de autenticação de token";
                exit();
            }
        }
	}	
    if (!function_exists("getAuthenticityToken")){
		function getAuthenticityToken(){
            if(!isset($_SESSION)) session_start();
            else if(!isset($_SESSION["AuthenticityToken"])) return $_SESSION["AuthenticityToken"];
        }
	}	
    if (!function_exists("newFolder")){
		function newFolder($dir){
		    $path=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$dir;
            if (!file_exists($path)){
    		    mkdir($path, 0777, true);
                return 'A Pasta $folder foi criada com sucesso';
            }
            return 'O Arquivo $folder já existe';
        }
    }
    if (!function_exists("newFile")){
		function newFile($path,$fileName){
            if (!file_exists($path)){
    		    mkdir($path, 0777, true);
                return 'A Pasta $folder foi criada com sucesso';
            }
            return 'O Arquivo $folder já existe';
        }
    }











    if (!function_exists("upload")){
    	function upload($path){
    	    $_result=array();
        	
        	if ( isset($_FILES) )
			echo json_encode ($_FILES);
        	foreach ($_FILES as $key => $_file_upload_current  )
        	{
				
				$_files_upload_names=array();
            	$path_upload=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;
        	    $path_upload_original=$path_upload;
        	   	if(isset($path)){
        	   	    $path_upload_original=$path_upload.$path.DIRECTORY_SEPARATOR;
        	   	}
        	   	else{
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
				$quantidade_fotos=0;
				if(is_array($_file_upload_current["name"]))
					$quantidade_fotos=count(array_diff($_file_upload_current["name"],array("")));
				
        	    for($i=0;$i<$quantidade_fotos;$i++){
            		if(!isset($_SESSION)) session_start();
            		$_SESSION["time"]=time();
            		$file_name = (is_array($_file_upload_current["name"]))?$_file_upload_current["name"][$i]:$_file_upload_current["name"];
            		$path_file=$path_upload_original.$file_name;
            		
            		$nome = pathinfo($path_file, PATHINFO_FILENAME);
            		$extensao = pathinfo($path_file, PATHINFO_EXTENSION);
            		$count=0;
            		while(file_exists($path_file)){
            			$file_name=$nome."_".$count.".".$extensao;
            			$path_file=$path_upload_original.$file_name;
            			$count++;
            		}
            		$file_tmp_name=(is_array($_file_upload_current['tmp_name']))?$_file_upload_current['tmp_name'][$i]:$_file_upload_current['tmp_name'];
            		move_uploaded_file($file_tmp_name,$path_file);
            		if($formats!=""){
                		$formats_array=explode(",",$formats);
                    	foreach ($formats_array as $format){
            		        list ($largura, $altura)=explode("x",$format);
                    		$nova_pasta=$path.$largura."x".$altura."/";
                    		if (!file_exists($nova_pasta))
                    			mkdir($nova_pasta, 0777, true); 
                    		redimensiona($path_file,$nova_pasta.$file_name,$largura,$altura,75);
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

















	
    if (!function_exists('redimensiona')){
		function redimensiona($origem,$destino,$maxlargura,$maxaltura,$qualidade){
		    if (file_exists($origem)){
            	list($largura, $altura) = getimagesize($origem);
            	if($altura>$largura){
            		$diferenca=$altura/$maxaltura;
            		$maxlargura=$largura/$diferenca;
            	}
            	else{
            		$diferenca=$largura/$maxlargura;
            		$maxaltura=$altura/$diferenca;
            	}
            	$image_p = ImageCreateTrueColor($maxlargura,$maxaltura)	or die("Cannot Initialize new GD image stream");	
            	$origem = imagecreatefromjpeg($origem);
            	imagecopyresampled($image_p, $origem, 0, 0, 0, 0,  $maxlargura, $maxaltura, $largura, $altura);
            	imagejpeg($image_p, $destino, $qualidade);
            	imagedestroy($image_p);
            	imagedestroy($origem);
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
    	       $path_url=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$dir;
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
	       $path_url=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$dir;
	      
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
     if (!function_exists('domainURL')){
    		function domainURL(){
    		    $protocolo = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http");
                $url = '://'.$_SERVER['HTTP_HOST'];
                return $protocolo.$url; 
    		}
     }	  
     if (!function_exists('loadPage')){
    		function loadPage($url){
                $doc = new DOMDocument();
                $doc->loadHTMLFile($url);
                return $doc->saveHTML();
    		}
     }	     
     if (!function_exists('sendEmail')){
    		function sendEmail($host,$username,$password,$subject,$email,$name,$urlBody){
                require '../vendor/autoload.php';
    		    $mail = new PHPMailer();
    		    $mail->IsSMTP();
                try {
                    $mail->Host = $host; // Endereço do servidor SMTP (Autenticação, utilize o host mail.seudomínio.com.br)
                    $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
                    $mail->Port = 587; // Usar 587 porta SMTP
                    $mail->Username = $username; // Usuário do servidor SMTP (endereço de email)
                    $mail->Password = $password; // Senha do servidor SMTP (senha do email usado)
                    
                    //Define o remetente
                    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= 
                    $mail->SetFrom($username, 'Nome'); //deve ser o mesmo da autenticação
                    $mail->AddReplyTo($username, 'Nome'); //Seu e-mail
                    $mail->Subject = $subject;//Assunto do e-mail
                    
                    
                    //Define os destinatário(s)
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->AddAddress($email,$name);
                    
                    //Campos abaixo são opcionais 
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
                    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
                    //$mail->AddAttachment('images/phpmailer.gif'); // Adicionar um anexo
                    
                    
                    //Define o corpo do email
                    $mail->MsgHTML(loadPage($urlBody)); 
                    
                    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
                    //$mail->MsgHTML(file_get_contents('arquivo.html'));
                    
                    $mail->Send();

                    //caso apresente algum erro é apresentado abaixo com essa exceção.
                }catch (phpmailerException $e) {
                    $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
                }
    		    
    		}
     }	
	
	
	
/*
function getIDYouTube($url){
   parse_str( parse_url( $url, PHP_URL_QUERY ), $youtubeID );
   if (array_key_exists("v", $youtubeID))
    {
        $id = $youtubeID['v'];
        return $id;
    }else{
        $explode = explode("/", $url);
        return end($explode);
    }
}


$data = new stdClass;
$data->name = "purencool";
$data->age = "old";
$data->nellypot = "no";
As a side point you can create a StdClass by casting an array below is an example.

$data = array(
"name" => "purencool",
"age" => "old",
"nellypot" => "no"
);
*/	
	
?>
