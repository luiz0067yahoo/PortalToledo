<?php

		// Source - https://stackoverflow.com/a/73337745
		// Posted by Luiz Ferreira
		// Retrieved 2026-01-05, License - CC BY-SA 4.0

		$GLOBALS["_PUT"]=null;
		if($_SERVER['REQUEST_METHOD'] == 'PUT') {
			$form_data= json_encode(file_get_contents("php://input"));
			$key_size=52;
			$key=substr($form_data, 1, $key_size);
			$acc_params=explode($key,$form_data);
			array_shift($acc_params);
			array_pop($acc_params);
			foreach ($acc_params as $item){
				$start_key=' name=\"';
				$end_key='\"\r\n\r\n';
				$start_key_pos=strpos($item,$start_key)+strlen($start_key);
				$end_key_pos=strpos($item,$end_key);
				
				$key=substr($item, $start_key_pos, ($end_key_pos-$start_key_pos));
				
				$end_value='\r\n';
				$value=substr($item, $end_key_pos+strlen($end_key), -strlen($end_value));
				$_PUT[$key]=$value;
			}
			$GLOBALS["_PUT"]=$_PUT;
		}

		if (!function_exists("getParameter")){
			function getParameter($parameter)
			{
				$value=null;
				// Coloque isso no início do método login() ou em qualquer outro método que queira suportar JSON
				$contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

				// Normaliza o valor (pode vir com charset, etc.)
				$contentType = trim(strtolower($contentType));
				$contentType = strtok($contentType, ';'); // remove "; charset=utf-8" se existir

				if (($contentType === 'application/json')&&($_SERVER['REQUEST_METHOD'] != 'GET')&&($_SERVER['REQUEST_METHOD'] != 'DELETE')) {
					$input = file_get_contents('php://input');
					$data = json_decode($input, true);

					if (json_last_error() !== JSON_ERROR_NONE) {
						///http_response_code(400);
						//throw new Exception("JSON inválido.");
						return;
					}
					$value=isset($data[$parameter]) ? $data[$parameter] : null;
				}
				else{
					if(($_SERVER['REQUEST_METHOD'] == 'POST')&& (isset($_POST[$parameter]))){
						$value=$_POST[$parameter];
					}
					else if(($_SERVER['REQUEST_METHOD'] == 'PUT')&& (isset($GLOBALS["_PUT"][$parameter])))
					{
						$value=$GLOBALS["_PUT"][$parameter];
					}
					else if(($_SERVER['REQUEST_METHOD'] == 'DELETE')&& (isset($_DELETE[$parameter]))){
						$value=$_DELETE[$parameter];
					}
					else if(($_SERVER['REQUEST_METHOD'] == 'PATCH')&& (isset($_PATCH[$parameter]))){
						$value=$_PATCH[$parameter];
					}
					else if(isset($_GET[$parameter])){
						$value=$_GET[$parameter];
					}
				}
				return $value;
			}
		}   



			if (!function_exists("issetParameter")) {
				function issetParameter($parameter)
				{
					// Usa getParameter para verificar existência (retorna null se não existir)
					// getParameter já prioriza POST > PUT > DELETE > PATCH > GET
					return getParameter($parameter) !== null;
				}
			}

			if (!function_exists("notEmptyParameter")) {
				function notEmptyParameter($parameter)
				{
					// Primeiro verifica se existe
					if (!issetParameter($parameter)) {
						return false;
					}

					// Pega o valor via getParameter (centralizado)
					$value = getParameter($parameter);

					// empty() trata "", "0", 0, null, false, [] como vazio
					return !empty($value);
				}
			}

			if (!function_exists("arrayKeyExistsParameter")) {
				function arrayKeyExistsParameter($parameter)
				{
					// Verifica se a chave existe (mesmo com valor null)
					// Ordem: POST > GET > PUT (seguindo a prioridade comum)
					// Coloque isso no início do método login() ou em qualquer outro método que queira suportar JSON
					$contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

					// Normaliza o valor (pode vir com charset, etc.)
					$contentType = trim(strtolower($contentType));
					$contentType = strtok($contentType, ';'); // remove "; charset=utf-8" se existir

					if ($contentType === 'application/json') {
						$input = file_get_contents('php://input');
						$data = json_decode($input, true);

						if (json_last_error() !== JSON_ERROR_NONE) {
							//http_response_code(400);
							//throw new Exception("JSON inválido.");
							return false;
						}
						if (array_key_exists($parameter, $data)) {
							return true;
						}
					}
					if (array_key_exists($parameter, $_POST)) {
						return true;
					}

					if (array_key_exists($parameter, $_GET)) {
						return true;
					}

					if (isset($GLOBALS["_PUT"]) && array_key_exists($parameter, $GLOBALS["_PUT"])) {
						return true;
					}

					// Caso adicione parsing para DELETE/PATCH no futuro
					// if (isset($GLOBALS["_DELETE"]) && array_key_exists($parameter, $GLOBALS["_DELETE"])) { return true; }
					// if (isset($GLOBALS["_PATCH"]) && array_key_exists($parameter, $GLOBALS["_PATCH"])) { return true; }

					return false;
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
            else if(isset($_SESSION["AuthenticityToken"])) return $_SESSION["AuthenticityToken"];
        }
	}	
   

     if (!function_exists('domainURL')){
    		function domainURL(){
    		    $protocolo = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http");
                $url = '://'.$_SERVER['HTTP_HOST'];
                return $protocolo.$url; 
    		}
     }	  

	
		if (!function_exists("upper_case_acent")){
		    function upper_case_acent($text){
		        return strtoupper(strtr($text ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
		    }}
		if (!function_exists("myUrlDecode")){
		    function myUrlDecode($parameter)
    		{
    		    $parameter=urldecode($parameter);
    		    $parameter= str_replace("%3F", "?", $parameter);
    		    $parameter= str_replace("%3A", "?", $parameter);
    		    $parameter= str_replace("%3B", ";", $parameter);
    		    //$parameter= str_replace("%253A", ":", $parameter);
    		    $parameter= str_replace("%25", ",", $parameter);
    		    $parameter= str_replace("%2F", "/", $parameter);
    		    return $parameter;
    		}
		}
?>