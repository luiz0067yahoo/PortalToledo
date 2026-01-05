<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//erros out

require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');
$query= getParameter("query");
$words=explode(' ', $query);
$conditions_news="";
$conditions_photos="";
$conditions_videos="";
$params=[];
$index_word=0;
foreach ($words as &$word) {
    $index_word++;
    $words[$index_word-1]="%".upper_case_acent($word)."%";
    $condition="((UPPER(titulo) like :word$index_word) or (UPPER(subtitulo) like :word$index_word) or (UPPER(conteudo_noticia) like :word$index_word))";
    $conditions_news=($conditions_news=="")?$condition:"$conditions and $condition";
    $condition="((UPPER(album_fotos.nome) like :word$index_word) or (album_fotos.id in(select fotos.id_album from fotos where (UPPER(fotos.nome) like :word$index_word)  order by fotos.id desc )))";
    $conditions_photos=($conditions_photos=="")?$condition:"$conditions and $condition";
    $condition="((UPPER(album_videos.nome) like :word$index_word) or (album_videos.id in(select videos.id_album from videos where (UPPER(videos.nome) like :word$index_word)  order by videos.id desc )))";
    $conditions_videos=($conditions_videos=="")?$condition:"$conditions and $condition";
    $params=$params+["word$index_word"=>$words[$index_word-1]];
}
//custom params search

$sql="  ( ";
$sql.="  select ";
$sql.=" 'news' as block_type, ";
$sql.=" noticias.id, ";
$sql.=" noticias.foto_principal, ";
$sql.=" noticias.titulo, ";
$sql.=" noticias.subtitulo, ";
$sql.=" concat(CAST(noticias.conteudo_noticia AS CHAR(20) CHARACTER SET utf8),' ...') as intro,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from noticias ";
$sql.=" left join menus filho on(filho.id=noticias.id_menu) ";
$sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
$sql.=" where ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and (pai.nome != 'Fotos') ";
$sql.=" and (pai.nome != 'Vídeos') ";
$sql.=" and (filho.nome != 'Fotos') ";
$sql.=" and (filho.nome != 'Vídeos') ";
$sql.=" and(";
$sql.="  $conditions_news ";
$sql.="  ) ";//news
$sql.="  ) ";//news
$sql.=" union ";
$sql.=" ( ";
$sql.=" select ";
$sql.=" 'photo' as block_type, ";
$sql.=" album_fotos.id, ";
$sql.=" (select fotos.foto from fotos where(fotos.id_album=album_fotos.id) order by fotos.id desc limit 0,1) as foto_principal, ";
$sql.=" album_fotos.nome as titulo, ";
$sql.=" '' as subtitulo, ";
$sql.=" '' as intro, ";
$sql.=" IF(filho.id_menu is null, concat(filho.nome,'/',album_fotos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_fotos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from album_fotos ";
$sql.=" left join menus filho on(filho.id=album_fotos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and ((pai.nome = 'Fotos') or (filho.nome = 'Fotos'))";
$sql.=" and (";
$sql.="  $conditions_photos ";
$sql.=" ) ";
$sql.=" ) ";
$sql.=" union ";// photos
$sql.=" ( ";// photos
$sql.=" select ";
$sql.=" 'video' as block_type, ";
$sql.=" album_videos.id, ";
$sql.=" (select videos.video from videos where(videos.id_album=album_videos.id) order by videos.id desc limit 0,1) as foto_principal, ";
$sql.=" album_videos.nome as titulo, ";
$sql.=" '' as subtitulo, ";
$sql.=" '' as intro, ";
$sql.=" IF(filho.id_menu is null, concat(filho.nome,'/',album_videos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_videos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from album_videos ";
$sql.=" left join menus filho on(filho.id=album_videos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and ((pai.nome = 'Vídeos') or (filho.nome = 'Vídeos'))";
$sql.=" and (";
$sql.="  $conditions_videos ";
$sql.=" ) ";
$sql.=" ) ";//videos
$sql.=" order by id desc ";
$sql.=" limit 0 , 27";
$result_all=DAOquery($sql,$params,true,"");
$elements_noticias=$result_all["elements"];

$GLOBALS["og_title"]="Tooeste Informação ao seu alcance";
$GLOBALS["og_description"]="Resultados da busca de ".$query;
$GLOBALS["og_image"]="https://www.tooeste.com.br/assets/img/logo310x310.jpg";
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


top();
$i=1;
//echo json_encode($result);
?>
<div class="row mt-3 justify-content-center">
	<div class="col-sm-9" >
	     <div class="row  block-row-1 ">
            <div class="col-sm-12 h-100" >
                <div ><p class=" p-1 h2 ">Resultado da busca de "<?php echo $query; ?>"</p></div>
        	</div>  
	    </div>
	</div>
</div>



<?php for ($j = 0; $j < 3; $j++) {?>
<div class="row mt-3 block-row-2 d-flex flex-wrap align-items-stretch">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+$j*3;
	    $path="";
        $prefix_news="/ler";
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
		if(isset($elements_noticias) && !empty($elements_noticias))
		if(is_array($elements_noticias) && count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$block_type=(isset($element["block_type"])&& !empty($element["block_type"]))?$element["block_type"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
    	    $subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
	        $indexIdMenu=0;
		}
		for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
            if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
                break;
        }
		if($block_type=='photo'){
		    $path="album";
		}
		else if($block_type=='news'){
    	    $path="noticias";
		}
		if($block_type=='video'){
		?>
		<div class="col-sm-3 h-100" >
		<div class="h-25 pt-3  menu<?php echo $menu_index;?>"><a class="link-color-<?php echo $menu_index;;?> " href="http://tooeste.com.br/<?php echo $url;?>"><?php echo $titulo;?></a></div>
		<div class="w-100 h-75 bg-menu-<?php echo $menu_index;?> " >
			<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
			<a href="http://tooeste.com.br/<?php echo $url;?>"><center><iframe class="mx-auto" src="https://www.youtube.com/embed/<?php echo $foto_principal;?>" type="text/html" width="100%"	height="100%" frameborder=0></iframe></center></a>
			<?php }?>
		</div>
	</div>
		<?php
		}
		else{
		    require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
		}
        
	}?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(1+$j));?></div>
	</div>
</div>
<?php }?>




	    <div class="row mt-3 block-row-2">
        	<div class="col-sm-12 pt-3 d-flex align-content-end flex-wrap " >
        		<div class="w-100 proportion-5x1 " ><?php banner("banner meio noticia 1")?></div>
        	</div>
        </div>	  
	    

		
<?php for ($j = 3; $j < 6; $j++) {?>
<div class="row mt-3 block-row-2 d-flex flex-wrap align-items-stretch">
	<?php for ($i = 0; $i < 3; $i++) {        
	    $index=$i+$j*3;
		$path="";
        $prefix_news="/ler";
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$block_type=(isset($element["block_type"])&& !empty($element["block_type"]))?$element["block_type"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
    	    $subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
	        $indexIdMenu=0;
		}
		for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
            if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
                break;
        }
		if($block_type=='photo'){
		    $path="album";
		}
		else if($block_type=='news'){
    	    $path="noticias";
		}
		if($block_type=='video'){
		?>
		<div class="col-sm-3 h-100" >
		<div class="h-25 pt-3  menu<?php echo $menu_index;?>"><a class="link-color-<?php echo $menu_index;;?> " href="http://tooeste.com.br/<?php echo $url;?>"><?php echo $titulo;?></a></div>
		<div class="w-100 h-75 bg-menu-<?php echo $menu_index;?> " >
			<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
			<a href="http://tooeste.com.br/<?php echo $url;?>"><center><iframe class="mx-auto" src="https://www.youtube.com/embed/<?php echo $foto_principal;?>" type="text/html" width="100%"	height="100%" frameborder=0></iframe></center></a>
			<?php }?>
		</div>
	</div>
		<?php
		}
		else{
		    require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
		}  
	}?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(1+$j));?></div>
	</div>
</div>
<?php }?>



	

     


	    <div class="row mt-3 block-row-2">
        	<div class="col-sm-12 pt-3 d-flex align-content-end flex-wrap " >
        		<div class="w-100 proportion-5x1 " ><?php banner("banner meio noticia 2")?></div>
        	</div>
        </div>	  
	    











<?php for ($j = 6; $j < 9; $j++) {?>
<div class="row mt-3 block-row-2 d-flex flex-wrap align-items-stretch">
	<?php for ($i = 0; $i < 3; $i++) {
	    $index=$i+$j*3;
		$path="";
        $prefix_news="/ler";
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$block_type=(isset($element["block_type"])&& !empty($element["block_type"]))?$element["block_type"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
    	    $subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
	        $indexIdMenu=0;
		}
		for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
            if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
                break;
        }
		if($block_type=='photo'){
		    $path="album";
		}
		else if($block_type=='news'){
    	    $path="noticias";
		}
		if($block_type=='video'){
		?>
		<div class="col-sm-3 h-100" >
		<div class="h-25 pt-3  menu<?php echo $menu_index;?>"><a class="link-color-<?php echo $menu_index;;?> " href="http://tooeste.com.br/<?php echo $url;?>"><?php echo $titulo;?></a></div>
		<div class="w-100 h-75 bg-menu-<?php echo $menu_index;?> " >
			<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
			<a href="http://tooeste.com.br/<?php echo $url;?>"><center><iframe class="mx-auto" src="https://www.youtube.com/embed/<?php echo $foto_principal;?>" type="text/html" width="100%"	height="100%" frameborder=0></iframe></center></a>
			<?php }?>
		</div>
	</div>
		<?php
		}
		else{
		    require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
		}   
    }?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(1+$j));?></div>
	</div>
</div>
<?php }?>
<?php 
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/most_views.php";
	foot();  
	
?>