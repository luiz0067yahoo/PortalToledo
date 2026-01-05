<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$first_row=0;
$destaque=false;

$indexIdMenu=0;

$sql=" select ";  
$sql.=" fotos.nome, "; 
$sql.=" album_fotos.id, "; 
$sql.=" fotos.foto, ";
$sql.=" album_fotos.nome as album,";  
$sql.=" IF(filho.id_menu is null, concat(filho.nome,'/',album_fotos.nome),"; 
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_fotos.nome)) as url, "; 
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from album_fotos "; 
$sql.=" left join fotos on(fotos.id_album=album_fotos.id) "; 
$sql.=" left join menus filho on(filho.id=album_fotos.id_menu) "; 
$sql.=" left join menus pai on (pai.id=filho.id_menu) "; 
$sql.=" where  "; 
$sql.=" (pai.id_menu is null) "; 
$sql.=" and (album_fotos.nome = :ler_categoria) "; 
$sql.=" and ("; 
$sql.=" (((filho.nome=:ler_menu ) or (pai.nome=:ler_menu)) and (:ler_sub_menu=''))"; 
$sql.=" or "; 
$sql.=" ((filho.nome=:ler_sub_menu ) and (pai.nome=:ler_menu)) " ; 
$sql.=" )"; 
$sql.=" limit 0 , 9"; 
 
 
$params=array("ler_menu"=>$GLOBALS["ler_menu"],"ler_sub_menu"=>$GLOBALS["ler_sub_menu"],"ler_categoria"=>$GLOBALS["ler_categoria"]);


$result_fotos=DAOquery($sql,$params,true,"");
$elements_fotos=$result_fotos["elements"];
$index=0;
$id="";
$nome="";
$url="";
$foto="";
$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Informação ao seu Alcance";
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(count($elements_fotos)>$index){
	$element_foto=$elements_fotos[$index];
	$id=(isset($element_foto["id"]) && !empty($element_foto["id"]))?$element_foto["id"]:"";
	$nome=(isset($element_foto["nome"]) && !empty($element_foto["nome"]))?$element_foto["nome"]:"";
	$url=(isset($element_foto["url"])&& !empty($element_foto["url"]))?$element_foto["url"]:"";
	$foto=(isset($element_foto["foto"])&& !empty($element_foto["foto"]))?$element_foto["foto"]:"";
	$GLOBALS["og_title"]="Tooeste - ".$GLOBALS['ler_menu'].((isset($GLOBALS['ler_sub_menu'])&& !empty($GLOBALS['ler_sub_menu']))?"/".$GLOBALS['ler_sub_menu']:"");
    $GLOBALS["og_description"]=$nome;
	$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/album/800x600/".$foto;
	$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
DAOquery("UPDATE album_fotos SET acesso=acesso+1 WHERE id=:id",["id"=>$id],false,"");
top();
for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
    if($GLOBALS["menus"][$menu_index-1]["nome"]==$GLOBALS["ler_menu"])
        break;
}
?>
<div class="row mt-3">
	<div class="col-sm-9" >
        <div class="  menu-<?php echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h2 text-uppercase text-white"><?php echo $GLOBALS["ler_menu"];?></p></div>
        <?php if($GLOBALS["ler_sub_menu"]!=""){?>
            <div class="  menu<?php echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h3 text-uppercase text-white"><?php echo $GLOBALS["ler_sub_menu"]; ?></p></div>
        <?php } ?>
         <?php if($GLOBALS["ler_categoria"]!=""){?>
            <div class="  menu<?php echo $menu_index;?>"><p class="bg-menu-<?php echo $menu_index;?> p-1 h3 text-uppercase text-white"><?php echo $GLOBALS["ler_categoria"]; ?></p></div>
        <?php } ?>
	</div>

</div>			


<?
	for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
        if($GLOBALS["menus"][$menu_index-1]["nome"]==$GLOBALS["ler_menu"])
            break;
    }
?>
<?php for ($j = 0; $j < 3; $j++) {?>
<div class="row mt-3 block-row-2 ">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+($j*3);
		$nome="";
		$url="";
		$foto="";
		if(count($elements_fotos)>$index){
			$element_foto=$elements_fotos[$index];
			$nome=(isset($element_foto["nome"]) && !empty($element_foto["nome"]))?$element_foto["nome"]:"";
			$url=(isset($element_foto["url"])&& !empty($element_foto["url"]))?$element_foto["url"]:"";
			$foto=(isset($element_foto["foto"])&& !empty($element_foto["foto"]))?$element_foto["foto"]:"";
		}
    ?>
        <div class=" col-sm-3 h-100  " style="align-content: space-between;">
	    <?php if(isset($foto)&& !empty($foto)){?>
            <a class=" galleryItem card" href="<?php echo $GLOBALS["base_url"];?>/uploads/album/800x600/<?php  echo $foto;?>" title="<?php echo $nome; ?>">
				<img src="<?php echo $GLOBALS["base_url"];?>/uploads/album/320x240/<?php  echo $foto;?>" class="card-img proportion-3x4" style="object-fit: cover;">
                <label class="h-25 mt-3 p-2  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    	    </a>
		<?php }?>
	    </div>
	<?php }?>
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
<div class="row mt-3 block-row-2 ">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+($j*3);
		$nome="";
		$url="";
		$foto="";
		if(count($elements_fotos)>$index){
			$element_foto=$elements_fotos[$index];
			$nome=(isset($element_foto["nome"]) && !empty($element_foto["nome"]))?$element_foto["nome"]:"";
			$url=(isset($element_foto["url"])&& !empty($element_foto["url"]))?$element_foto["url"]:"";
			$foto=(isset($element_foto["foto"])&& !empty($element_foto["foto"]))?$element_foto["foto"]:"";
		}
    ?>
    <div class=" col-sm-3 h-100  " style="align-content: space-between;">
	    <?php if(isset($foto)&& !empty($foto)){?>
            <a class=" galleryItem card" href="<?php echo $GLOBALS["base_url"];?>/uploads/album/800x600/<?php  echo $foto;?>" title="<?php echo $nome; ?>">
				<img src="<?php echo $GLOBALS["base_url"];?>/uploads/album/320x240/<?php  echo $foto;?>" class="card-img proportion-3x4" style="object-fit: cover;">
                <label class="h-25 mt-3 p-2  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    	    </a>
		<?php }?>
    </div>
	<?php }?>
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

		



		

<?php for ($j = 7; $j < 10; $j++) {?>
<div class="row mt-3 block-row-2 ">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+($j*3);
		$nome="";
		$url="";
		$foto="";
		if(count($elements_fotos)>$index){
			$element_foto=$elements_fotos[$index];
			$nome=(isset($element_foto["nome"]) && !empty($element_foto["nome"]))?$element_foto["nome"]:"";
			$url=(isset($element_foto["url"])&& !empty($element_foto["url"]))?$element_foto["url"]:"";
			$foto=(isset($element_foto["foto"])&& !empty($element_foto["foto"]))?$element_foto["foto"]:"";
		}
    ?>
   <div class=" col-sm-3 h-100  " style="align-content: space-between;">
	    <?php if(isset($foto)&& !empty($foto)){?>
            <a class=" galleryItem card" href="<?php echo $GLOBALS["base_url"];?>/uploads/album/800x600/<?php  echo $foto;?>" title="<?php echo $nome; ?>">
				<img src="<?php echo $GLOBALS["base_url"];?>/uploads/album/320x240/<?php  echo $foto;?>" class="card-img proportion-3x4" style="object-fit: cover;">
                <label class="h-25 mt-3 p-2  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    	    </a>
		<?php }?>
    </div>
	<?php }?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(0+$j));?></div>
	</div>
</div>
<?php }?>		


<?php 
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/most_views.php";
	foot();  
   
?>