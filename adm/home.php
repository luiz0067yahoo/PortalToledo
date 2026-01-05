<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$indexIdMenu=0;

$sql="  select ";
$sql.=" noticias.id, ";
$sql.=" noticias.id_menu, ";
$sql.=" noticias.foto_principal, ";
$sql.=" noticias.titulo, ";
$sql.=" noticias.subtitulo, ";
$sql.=" noticias.conteudo_noticia, ";
$sql.=" noticias.fonte, ";
$sql.=" noticias.ocultar, ";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal";
$sql.=" from noticias ";
$sql.=" left join menus filho on(filho.id=noticias.id_menu) ";
$sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
$sql.=" where ";
$sql.=" (pai.id_menu is null) ";
$sql.=" 	 ";
$noticias_campos=array();
$noticias_menu1=array();
$result=DAOquery($sql,array('id'=>$menus[0][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu1=$result["data"];}
if (isset($result["titles"])){$noticias_campos=$result["titles"];}

$noticias_menu2=array();
$result=DAOquery($sql,array('id'=>$menus[1][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu2=$result["data"];}

$noticias_menu3=array();
$result=DAOquery($sql,array('id'=>$menus[2][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu3=$result["data"];}


$noticias_menu4=array();
$result=DAOquery($sql,array('id'=>$menus[3][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu4=$result["data"];}

$noticias_menu5=array();
$result=DAOquery($sql,array('id'=>$menus[4][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu5=$result["data"];}

$noticias_menu6=array();
$result=DAOquery($sql,array('id'=>$menus[5][$indexIdMenu]));
if (isset($result["data"])){$noticias_menu6=$result["data"];}

$indexIdNoticia=0;
$indexId_menuNoticia=1;
$indexFoto_principalNoticia=2;
$indexTituloNoticia=3;
$indexSubTituloNoticia=4;
$indexFonteNoticia=5;
$indexOcultar=6;

?>

<div class="row mt-3 block-row-1">
	
	<div class="col-sm-3 h-100" >
		<div class="h-25 menu1"><a class="link-color-1 "><?php echo $noticias_menu1[0][(array_search('menu_principal',$noticias_campos))];?></a></div>
		<div class="w-100 h-75 bg-menu-1 " >
			<?php try{$Foto_principalNoticia=$noticias_menu1[0][$indexFoto_principalNoticia];if(isset($Foto_principalNoticia)){?>
			<img src="http://tooeste.com.br/uploads/noticias/320x240/<?php  echo $Foto_principalNoticia;?>" class="w-100 h-100">
			<?php }}catch(Exception $e){}?>
		</div>
	</div>


	<div class="col-sm-3 h-100" >
		<div class="h-25 menu2"><a class="link-color-2 ">Esporte</a></div>
		<div class="w-100 h-75 bg-menu-2 " >
			<?php try{$Foto_principalNoticia=$noticias_menu2[0][$indexFoto_principalNoticia];if(isset($Foto_principalNoticia)){?>
			<img src="http://tooeste.com.br/uploads/noticias/320x240/<?php  echo $Foto_principalNoticia;?>" class="w-100 h-100">
			<?php }}catch(Exception $e){}?>
		</div>
	</div>

	<div class="col-sm-3 h-100" >
		<div class="h-25 menu3"><a class="link-color-3 ">Entretenimento</a></div>
		<div class="w-100 h-75 bg-menu-2 " >
			<?php try{$Foto_principalNoticia=$noticias_menu3[0][$indexFoto_principalNoticia];if(isset($Foto_principalNoticia)){?>
			<img src="http://tooeste.com.br/uploads/noticias/320x240/<?php  echo $Foto_principalNoticia;?>" class="w-100 h-100">
			<?php }}catch(Exception $e){}?>
		</div>
	</div>

	
	
	<div class="col-sm-3" >
		<div class="w-100 h-100 bg-menu-4" ></div>
	</div>
</div>
