<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
$indexIdMenu=0;

$sql="  select ";
$sql.=" noticias.destaque, ";
$sql.=" noticias.foto_principal, ";
$sql.=" noticias.titulo, ";
$sql.=" noticias.subtitulo, ";
$sql.=" concat(CAST(noticias.conteudo_noticia AS CHAR(20) CHARACTER SET utf8),' ...') as intro,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";

$sql.=" from noticias ";
$sql.=" left join menus filho on(filho.id=noticias.id_menu) ";
$sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
$sql.=" where ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and(";
$sql.="  (((pai.nome like :ler_menu)or(filho.nome like :ler_menu))and(:ler_sub_menu=''))";
$sql.="  or ";
$sql.="  ((pai.nome like :ler_menu)and(filho.nome like :ler_sub_menu))";
$sql.="  )";
$sql.=" order by noticias.id desc   ";
$sql.=" limit 0 , 27";
$findSub_menu=simbolTo_($GLOBALS['ler_sub_menu']);
$findMenu=simbolTo_($GLOBALS['ler_menu']);
$result=DAOquery($sql,array('ler_menu'=>$findMenu,'ler_sub_menu'=>$findSub_menu),true,"");
$elements_noticias=$result["elements"];

$GLOBALS["og_title"]="Tooeste Informação ao seu alcance";
$GLOBALS["og_description"]=$GLOBALS['ler_menu'].((isset($GLOBALS['ler_sub_menu'])&& !empty($GLOBALS['ler_sub_menu']))?$GLOBALS['ler_sub_menu']:"");
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];
$path="noticias";
$prefix_news="/ler";

if(count($elements_noticias)>0){
	$element=$elements_noticias[0];
	$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
	$GLOBALS["og_image"]=$foto_principal;
}	
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

top();

$i=1;
$first_row=0;
for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
    if($GLOBALS["menus"][$menu_index-1]["nome"]==$GLOBALS['ler_menu'])
        break;
}
?>
<div class="row mt-3 justify-content-center">
	<div class="col-sm-9" >
	     <div class="row  block-row-1 ">
            <div class="col-sm-12 h-100" >
                <div class="  menu<?php echo $menu_index;?>"><p class="bg-menu-<?php  echo $menu_index;?> p-1 h2  text-white"><?php echo upper_case_acent($GLOBALS["ler_menu"]); ?></p></div>
                <?php if($GLOBALS["ler_sub_menu"]!=""){?>
                <div class="  menu<?php  echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h2  text-white"><?php echo upper_case_acent($GLOBALS["ler_sub_menu"] ); ?></p></div>
                <?php }?>
        	</div>  
	    </div>
	</div>

</div>





<?php for ($j = 0; $j < 3; $j++) {?>
<div class="row mt-3 block-row-2 d-flex flex-wrap align-items-stretch">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+$j*3;
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
        $destaque=false;
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
			$subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$destaque=(isset($element["destaque"])&& !empty($element["destaque"]))?$element["destaque"]:"";
		}
		require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
        
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
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
        $destaque=false;
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
			$subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$destaque=(isset($element["destaque"])&& !empty($element["destaque"]))?$element["destaque"]:"";

		}
        require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');    
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
		$menu_principal=""; 
		$submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
	    $destaque=false;
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
			$subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$destaque=(isset($element["destaque"])&& !empty($element["destaque"]))?$element["destaque"]:"";
		}
        require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');    
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