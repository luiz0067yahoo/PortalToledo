<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/template.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$indexIdMenu=0;

$sql=" select ";
$sql.=" anuncios_fotos.nome, ";
$sql.=" anuncios_fotos.foto ";
$sql.=" from anuncios_fotos ";
$sql.=" where  ";
$sql.=" (id_anuncio = :id_anuncio) ";
$params=array("id_anuncio"=>$id_anuncio);

$result_fotos=DAOquery($sql,$params,true,"");
$elements_fotos=$result_fotos["elements"];
$index=0;
$nome="";
$foto="";
$total=count($elements_fotos);
$lines=ceil($total/3);

?>
<?php for ($j = 0; $j < $lines; $j++) {?>
<div class="row mt-3 block-row-2  justify-content-center">
	<?php for ($i = 0; $i < 3; $i++) {
        $index=$i+($j*3);
		$nome="";
		$foto="";
		if(count($elements_fotos)>$index){
			$element_foto=$elements_fotos[$index];
			$nome=(isset($element_foto["nome"]) && !empty($element_foto["nome"]))?$element_foto["nome"]:"";
			$foto=(isset($element_foto["foto"])&& !empty($element_foto["foto"]))?$element_foto["foto"]:"";
		}
    ?>
        <div class=" col-sm-3 h-100  " style="align-content: space-between;">
	    <?php if(isset($foto)&& !empty($foto)){?>
            <a class=" galleryItem card" href="<?php echo $GLOBALS["base_url"];?>/uploads/anuncios/800x600/<?php  echo $foto;?>" title="<?php echo $nome; ?>">
				<img src="<?php echo $GLOBALS["base_url"];?>/uploads/anuncios/320x240/<?php  echo $foto;?>" class="card-img proportion-3x4" style="object-fit: cover;">
                <label class="h-25 mt-3 p-2  menu<?php echo $menu_index;?> link-color-<?php echo $menu_index;?> " ><?php echo $nome; ?></label>
    	    </a>
		<?php }?>
	    </div>
	<?php }?>
</div>
<?php }?>