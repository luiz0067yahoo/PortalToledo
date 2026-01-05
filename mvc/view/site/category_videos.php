
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$indexIdMenu=0;
$sql=" select ";
$sql.=" album_videos.id, ";
$sql.=" v.nome as nome_video, ";
$sql.=" v.video, ";
$sql.=" album_videos.nome, IF(filho.id_menu is null, concat(filho.nome,'/',album_videos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_videos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from album_videos ";
$sql.=" left join videos v on(v.id_album=album_videos.id) ";
$sql.=" left join menus filho on(filho.id=album_videos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and (album_videos.nome = :ler_categoria) ";
$sql.=" and (";
$sql.=" (((filho.nome=:ler_menu ) or (pai.nome=:ler_menu)) and (:ler_sub_menu=''))";
$sql.=" or ";
$sql.=" ((filho.nome=:ler_sub_menu ) and (pai.nome=:ler_menu)) ";
$sql.=" )";
$sql.=" limit 0 , 9";
$params=["ler_menu"=>$GLOBALS["ler_menu"],"ler_sub_menu"=>$GLOBALS["ler_sub_menu"],"ler_categoria"=>$GLOBALS["ler_categoria"]];
$result=DAOquery($sql,$params,true,"");
$elements_videos=$result["elements"];
$index=0;
$nome="";
$url="";
$video="";
$GLOBALS["og_title"]="Tooeste";
$GLOBALS["og_description"]="Informação ao seu Alcance";
$GLOBALS["og_image"]=$GLOBALS["base_url"]."/uploads/menu/320x240/".$GLOBALS["logo_site"];
$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(count($elements_videos)>$index){
	$element_video=$elements_videos[$index];
	$id=(isset($element_video["id"]) && !empty($element_video["id"]))?$element_video["id"]:"";
	$nome_video=(isset($element_video["nome_video"]) && !empty($element_video["nome_video"]))?$element_video["nome_video"]:"";
	$url=(isset($element_video["url"])&& !empty($element_video["url"]))?$element_video["url"]:"";
	$video=(isset($element_video["video"])&& !empty($element_video["video"]))?$element_video["video"]:"";
	$GLOBALS["og_title"]="Tooeste - ".$GLOBALS['ler_menu'].((isset($GLOBALS['ler_sub_menu'])&& !empty($GLOBALS['ler_sub_menu']))?"/".$GLOBALS['ler_sub_menu']:"");
    $GLOBALS["og_description"]=$nome_video;
	$GLOBALS["og_image"]="https://img.youtube.com/vi/$video/hqdefault.jpg";
	$GLOBALS["og_url"]='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}
DAOquery("UPDATE album_videos SET acesso=acesso+1 WHERE id=:id",["id"=>$id],false,"");
top();
$first_row=0;
$destaque=false;

for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
    if($GLOBALS["menus"][$menu_index-1]["nome"]==$GLOBALS["ler_menu"])
        break;
}
?>
<div class="row mt-3 justify-content-center">
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



<?php for ($j = 0; $j < 3; $j++) {?>
<div class="row mt-3 block-row-2 ">
	<?php for ($i = 0; $i < 3; $i++) {
    	$index=$i+($j*3);
		$nome="";
		$url="";
		$video="";
		if(count($elements_videos)>$index){
			
			$element_video=$elements_videos[$index];
			$nome=(isset($element_video["nome"]) && !empty($element_video["nome"]))?$element_video["nome"]:"";
			$url=(isset($element_video["url"])&& !empty($element_video["url"]))?$element_video["url"]:"";
			$video=(isset($element_video["video"])&& !empty($element_video["video"]))?$element_video["video"]:"";
		}
    ?>
        <div class=" col-sm-3 h-100  card">
        <a class=" galleryItem" href="https://www.youtube.com/embed/<?php  echo $video;?>" title="<?php echo $nome; ?>">
			<?php if(isset($video)&& !empty($video)){?>
				<iframe src="https://www.youtube.com/embed/<?php echo $video;?>" type="text/html" width="200px"	height="150px" frameborder=0></iframe>
			<?php }?>
            <label class="h-25 pt-3  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?> </label>
    		
	    </a>
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
		$video="";
		if(count($elements_videos)>$index){
			$element_video=$elements_videos[$index];
			$nome=(isset($element_video["nome"]) && !empty($element_video["nome"]))?$element_video["nome"]:"";
			$url=(isset($element_video["url"])&& !empty($element_video["url"]))?$element_video["url"]:"";
			$video=(isset($element_video["video"])&& !empty($element_video["video"]))?$element_video["video"]:"";
		}
    ?>
        <div class=" col-sm-3 h-100  card">
        <a class=" galleryItem" href="https://www.youtube.com/embed/<?php  echo $video;?>" title="<?php echo $nome; ?>">
			<?php if(isset($video)&& !empty($video)){?>
				<iframe src="https://www.youtube.com/embed/<?php echo $video;?>" type="text/html" width="200px"	height="150px" frameborder=0></iframe>
			<?php }?>
            <label class="h-25 pt-3  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    		
	    </a>
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
		$video="";
		if(count($elements_videos)>$index){
			$element_video=$elements_videos[$index];
			$nome=(isset($element_video["nome"]) && !empty($element_video["nome"]))?$element_video["nome"]:"";
			$url=(isset($element_video["url"])&& !empty($element_video["url"]))?$element_video["url"]:"";
			$video=(isset($element_video["video"])&& !empty($element_video["video"]))?$element_video["video"]:"";
		}
    ?>
        <div class=" col-sm-3 h-100  card">
        <a class=" galleryItem" href="https://www.youtube.com/embed/<?php  echo $video;?>" title="<?php echo $nome; ?>">
			<?php if(isset($video)&& !empty($video)){?>
				<iframe src="https://www.youtube.com/embed/<?php echo $video;?>" type="text/html" width="200px"	height="150px" frameborder=0></iframe>
			<?php }?>
            <label class="h-25 pt-3  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    		
	    </a>
	    </div>
	<?php }?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(1+$j));?></div>
	</div>
</div>
<?php }?>		


<?php 
	include $_SERVER['DOCUMENT_ROOT']."/mvc/view/site/most_views.php";
	foot();  
   
?>