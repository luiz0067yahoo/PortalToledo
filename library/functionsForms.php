<?php
    /*
		    global $_DELETE = array(); 
            global $_PUT = array();
            
            if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'DELETE')) {
                parse_str(file_get_contents('php://input'), $_DELETE);
            }
            if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
                parse_str(file_get_contents('php://input'), $_PUT);
            }

    */
	

	
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
		if (!function_exists("getParameter")){
		function getParameter($parameter)
		{
		    
			return isset($_POST[$parameter])?$_POST[$parameter]:(
			    isset($_GET[$parameter])?$_GET[$parameter]:(
			        isset($_PUT[$parameter])?$_PUT[$parameter]:(
			            isset($_DELETE[$parameter])?$_DELETE[$parameter]:(
			                isset($_PATCH[$parameter])?$_PATCH[$parameter]:null
		                )
		            )
			    )
			);
			//PATCH
		}
	}	
	
	if (!function_exists("issetParameter")){
		function issetParameter($parameter)
		{
		    
			return isset($_POST[$parameter])?isset($_POST[$parameter]):(
			    isset($_GET[$parameter])?isset($_GET[$parameter]):(
			        isset($_PUT[$parameter])?isset($_PUT[$parameter]):(
			            isset($_DELETE[$parameter])?isset($_DELETE[$parameter]):(
			                isset($_PATCH[$parameter])?isset($_PATCH[$parameter]):false
		                )
		            )
			    )
			);
			//PATCH
		}
	}
	if (!function_exists("notEmptyParameter")){
		function notEmptyParameter($parameter)
		{
		    
			return (
				issetParameter($parameter) 
				&&
				(
					!empty($_POST[$parameter])?!empty($_POST[$parameter]):(
						!empty($_GET[$parameter])?!empty($_GET[$parameter]):(
							!empty($_PUT[$parameter])?!empty($_PUT[$parameter]):(
								!empty($_DELETE[$parameter])?!empty($_DELETE[$parameter]):(
									!empty($_PATCH[$parameter])?!empty($_PATCH[$parameter]):false
								)
							)
						)
					)
			    )
			);
			
			//PATCH
		}
	}
	if (!function_exists("arrayKeyExistsParameter")){
		function arrayKeyExistsParameter($parameter)
		{
		    
			return isset($_POST[$parameter])?array_key_exists($parameter,$_POST):(
			    isset($_GET[$parameter])?array_key_exists($parameter,$_GET):(
			        isset($_PUT[$parameter])?array_key_exists($parameter,$_PUT):(
			            isset($_DELETE[$parameter])?array_key_exists($parameter,$_DELETE):(
			                isset($_PATCH[$parameter])?array_key_exists($parameter,$_PATCH):false
		                )
		            )
			    )
			);
			//PATCH
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

	
?>