<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');


$indexIdMenu=0;

$sql="  SELECT anuncios.* FROM anuncios LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(anuncios.nome=:ler_sub_menu)  limit 0 , 1";

$findNome=simbolTo_($GLOBALS['ler_sub_menu']);

$result=DAOquery($sql,['ler_sub_menu'=>$findNome],true,"");
$i=0;
$elements=$result["elements"];

$nome_anuncio="";
$descricao="";
$foto_expandida="";
$fonte="";
$menu_principal="";
$intro="";
$id_anuncio=0;
$first_row=0;
$destaque=false;




$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Informação ao seu Alcance";
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if(count($elements)>0){
	$element=$result["elements"][$i];


	$id_anuncio=$element["id"];
	$date_insert=$element["date_insert"];
	$date_update=$element["date_update"];
	$nome_anuncio=$element["nome"];
	$intro=$element["introducao"];  
	$intro2=$element["introducao2"];  
	$descricao=$element["descricao"];
	$facebook=$element["facebook"];  
	$twitter=$element["twitter"];  
	$youtube=$element["youtube"];  
	$instagram=$element["instagram"];  
	$whatsapp=$element["whatsapp"];  
	$endereco=$element["endereco"]; 
	$telefone=$element["telefone"]; 
	$e_mail=$element["e_mail"]; 
	$website=$element["website"]; 
	
	$foto_expandida=(isset($element["foto_expandida"])&& !empty($element["foto_expandida"]))?$element["foto_expandida"]:"";
	$foto=(isset($element["foto"])&& !empty($element["foto"]))?$element["foto"]:"";

 
        
	$GLOBALS["og_title"]="Tooeste - ".$nome_anuncio;
	$GLOBALS["og_description"]="";
	$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/anuncio/1024x768/".$foto_expandida;
	$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//DAOquery("UPDATE noticias SET acesso=acesso+1 WHERE id=:id",["id"=>$id_noticia],false,"");

}
top();
if(count($elements)>0){
	for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
		if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
			break;
	}
?>
<?php if(isset($nome_anuncio)&&!empty($nome_anuncio)) {?>
<div class="row mt-3">
	<div class="col-sm-12" >
        <div class="text-color-<?php echo $menu_index;?>"><p class=" p-1 h2 text-uppercase text-center" style="font-weight: 900;font-family: 'Nunito Sans'"><?php echo $nome_anuncio ;?></p></div>
	</div>
</div>	
<?php } ?>
<?php if(isset($intro)&&!empty($intro)) {?>
<div class="row mt-3 ">
    <div class="col-sm-12 h-100" >
		<h5 class="w-100 d-flex justify-content-center text-color-<?php echo $menu_index;?> text-center">
			<div style="height:20px;width:20px;margin-right:20px"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z"/></svg></div>
			<?php echo $intro;?>
		</h5>	
	</div>
</div>
<?php } ?>
<?php if(isset($intro2)&&!empty($intro2)) {?>
<div class="row mt-3 ">
    <div class="col-sm-12 h-100"  >
		<h3 class="w-100 rounded text-center text-color-<?php echo $menu_index;?>" style="font-weight: 900;font-family: 'Nunito Sans';background-color:#EEEDE7;"><?php echo $intro2;?></h3>
	</div>
</div>
<?php } ?>
<?php if(isset($descricao)&&!empty($descricao)) {?>
<div class="row mt-3 ">
    <div class="col-sm-12 h-100" >
		<p class="w-100 text-color-<?php echo $menu_index;?>"><?php echo $descricao;?></div>
	</div>
</div>
<?php } ?>
<?php if(isset($foto_expandida)&&!empty($foto_expandida)) 
if(file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/anuncio/1024x768/".$foto_expandida)){
?>
<div class="row mt-3 justify-content-center pb-4">
	<div class="col-sm-9 button-social-links" >
		<?php if(isset($whatsapp)){ ?>
			<a class="p-2 w-100 d-flex justify-content-center align-items-center rounded h4 bg-success" style="min-height:70px;height:auto" target="blank"  href="https://wa.me/<?echo filter_var($whatsapp, FILTER_SANITIZE_NUMBER_INT);?>">
				<i style="width:40px;height:40px;margin-right:10px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg></i>
				<div >
					Conversar no Whatsapp <br>
					<?echo  $whatsapp; ?>
				</div>
			</a>
		<?php } ?>
	</div>
</div>
<div class="row mt-3 justify-content-center pb-4">
	<div class="col-sm-7 " >
		<img class="w-100 proportion-3x4" src="<?php echo $GLOBALS["base_url"];?>/uploads/anuncio/1024x768/<?php echo $foto_expandida;?>">
	</div>
</div>
<?php } 

    include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/social_media.php";
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/sponsor_photos.php";
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/sponsor_attachments.php";

}
else include  $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/content_404.php";

	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/most_views.php";
    foot();  
   
?>