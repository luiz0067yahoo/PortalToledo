<?php 
	try{
        $url="http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		date_default_timezone_set("America/Sao_Paulo");
		$data_atual=date("Y-m-d");
		$hora_atual=date("h:i:s");
		$sql=" INSERT INTO contador_acesso_por_pagina ";
		$sql.=" (url, menu, submenu, categoria, titulo, data_acesso, hora_acesso) "; 
		$sql.=" VALUES  ";
		$sql.=" (  ";
		$sql.=" :url,  ";
		$sql.=" :menu,  ";
		$sql.=" :submenu,  ";
		$sql.=" :categoria,  ";
		$sql.=" :titulo,  ";
		$sql.=" :data_acesso,  ";
		$sql.=" :hora_acesso  ";
		$sql.=" )  ";
		$params=array(
			'url'=>$url,
			'menu'=>$GLOBALS["ler_menu"],
			'submenu'=>$GLOBALS["ler_sub_menu"],
			'categoria'=>$GLOBALS["ler_categoria"],
			'titulo'=>$GLOBALS["ler_titulo_noticia"],
			'data_acesso'=>$data_atual,
			'hora_acesso'=>$hora_atual
		);
		DAOquery($sql,$params,false,"");
			
		
	}catch(Exception $e){}
	
	$sql="  select ";
	$sql.=" count(filho.id) as count_menu ";
	$sql.=" from menus filho  "; 
	$sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
	$sql.=" where ";
	$sql.=" (pai.id_menu is null) ";
	$sql.=" and(";
	$sql.="  (((pai.nome like :ler_menu)or(filho.nome like :ler_menu))and('' like :ler_sub_menu))";
	$sql.="  or ";
	$sql.="  ((pai.nome like :ler_menu)and(filho.nome like :ler_sub_menu))";
	$sql.="  )";
	$findSub_menu=simbolTo_($GLOBALS['ler_sub_menu']);
	$findMenu=simbolTo_($GLOBALS['ler_menu']);
	$result=DAOquery($sql,array('ler_menu'=>$findMenu,'ler_sub_menu'=>$findSub_menu),true,"");
	$elements=$result["elements"];
	
	

	
	if($GLOBALS["ler_menu"]=='Patrocinador'){
	    if($GLOBALS["ler_sub_menu"]!=""){
	        if($GLOBALS["ler_titulo_noticia"]!="")
	            include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/sponsor_attachment_single.php";
	        else
	            include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/sponsor.php";
	    }
	}
    else {
       
    	if(count($elements)>0){
    		$element=$elements[0];
    		if (intval($element["count_menu"])>0){
    			$url="https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    
    			if($GLOBALS["ler_menu"]=='Fotos'){
    				if($GLOBALS["ler_categoria"]!=""){
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_photos.php";
                        
    				}
    				else if(($GLOBALS["ler_menu"]!="")and($GLOBALS["ler_sub_menu"]=="")and($GLOBALS["ler_categoria"]=="")){
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_photos_by_menu.php";
                        
    				}
    				else if($GLOBALS["ler_menu"]!=""){
                        
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_photos_by_menu.php";
    				}
    			}
    			else if($GLOBALS["ler_menu"]=='Vídeos'){
    				if($GLOBALS["ler_categoria"]!=""){
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_videos.php";
    				}
    				else if(($GLOBALS["ler_menu"]!="")and($GLOBALS["ler_sub_menu"]=="")and($GLOBALS["ler_categoria"]=="")){
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_videos_by_menu.php";
    				}
    				else if($GLOBALS["ler_menu"]!=""){
    					 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/category_videos_by_menu.php";
    				}
    			}
    			else if(isset($GLOBALS["ler_titulo_noticia"]) && !empty($GLOBALS["ler_titulo_noticia"])){
    				 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/news.php";
    			}
    			else if(($GLOBALS["ler_menu"]!="")and($GLOBALS["ler_sub_menu"]=="")and($GLOBALS["ler_titulo_noticia"]=="")){
    				 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/news_by_menu.php";
    			}
    			else if($GLOBALS["ler_menu"]!=""){
    				 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/news_by_menu.php";
    			}
    		}
    		else
    			 include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/404.php";
    	}
	}
		

?>