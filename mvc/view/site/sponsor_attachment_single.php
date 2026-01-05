<?php
//$encoding = mb_internal_encoding(); 
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');

$indexIdMenu=0;

$sql="  select ";
$sql.=" anuncios_anexos.id, ";
$sql.=" anuncios_anexos.date_insert, ";
$sql.=" anuncios_anexos.date_update, ";
$sql.=" anuncios_anexos.foto_principal, ";
$sql.=" anuncios_anexos.titulo, ";
$sql.=" anuncios_anexos.subtitulo, ";
$sql.=" concat(CAST(anuncios_anexos.conteudo_anuncio_anexo AS CHAR(20) CHARACTER SET utf8),' ...') as intro,";
$sql.=" anuncios_anexos.conteudo_anuncio_anexo, ";
$sql.=" anuncios_anexos.fonte, ";
$sql.=" anuncios.nome as anuncio ";
$sql.=" from anuncios_anexos ";
$sql.=" left join anuncios  on(anuncios.id=anuncios_anexos.id_anuncio) ";
$sql.=" where";
$sql.=" (anuncios.nome like :ler_sub_menu)";
$sql.=" and";
$sql.=" (anuncios_anexos.titulo like :ler_titulo_noticia)";
$sql.=" limit 0 , 1";


$findSub_menu=simbolTo_($GLOBALS['ler_sub_menu']);
$findTituloNoticia=simbolTo_($GLOBALS['ler_titulo_noticia']);

$result=DAOquery($sql,['ler_sub_menu'=>$findSub_menu,'ler_titulo_noticia'=>$findTituloNoticia],true,"");

$i=0;
$elements=[];
if (isset($result["elements"]) &&  !empty($result["elements"]))
    $elements=$result["elements"];



$id_noticia="";
$titulo="";
$subtitulo="";
$foto_principal="";
$conteudo_anuncio_anexo="";
$fonte="";
$menu_principal="";


$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Informação ao seu Alcance";
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];

$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path="anuncios_anexos";
if(count($elements)>0){
	$element=$result["elements"][$i];


	$id_noticia=$element["id"];
	$date_insert=$element["date_insert"];
	$date_update=$element["date_update"];
	$titulo=$element["titulo"];
	$subtitulo=$element["subtitulo"];
	$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
	$conteudo_anuncio_anexo=$element["conteudo_anuncio_anexo"];
	$fonte=$element["fonte"];

	$GLOBALS["og_title"]="Tooeste - ".$titulo;
	$GLOBALS["og_description"]=$subtitulo;
	$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/anuncios_anexos/800x600/".$foto_principal;
	$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	DAOquery("UPDATE `anuncios_anexos` SET acesso=1 WHERE (acesso is null) and (id=:id)",["id"=>$id_noticia],false,"");
	DAOquery("UPDATE `anuncios_anexos` SET acesso=acesso+1 WHERE id=:id",["id"=>$id_noticia],false,"");
}
top();
if(count($elements)>0){
?>
<div class="row mt-3 block-row-3 justify-content-center">
    <div class="col-sm-9 " >
        <div class="  menu<?php echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h6 text-uppercase text-white"><?php echo myUrlDecode($GLOBALS["ler_menu"]);?></p></div>
        <?php if($GLOBALS["ler_sub_menu"]!=""){?>
            <div class="  menu<?php echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h6 text-uppercase text-white"><?php echo myUrlDecode($GLOBALS["ler_sub_menu"]); ?></p></div>
        <?php } ?>
		<div class="menu<?php echo $menu_index;?>"><p class="text-color-<?php echo $menu_index;?> h4"><?php echo strtoupper(strtr($titulo ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ")); ?></p></div>
		<br>
		<div class=" menu<?php echo $menu_index;?>"><p class="text-muted h4"><?php echo strtoupper(strtr($subtitulo ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ")); ?></p></div>

		<img class=" pt-3 w-100" src="<?php echo $GLOBALS["base_url"];?>/uploads/anuncios_anexos/800x600/<?php echo $foto_principal;?>">
		
		<hr>
		<p class="mt-3 text-dark h5">Fonte: <?php echo $fonte;?></p>
		<p class="text-dark h5">Data: <?php echo (new DateTime($date_update))->format('d/m/Y')?></p>

	</div>  
</div>	



<?php 
}
else include  $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/content_404.php";
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/most_views.php";
     foot();  
   
?>