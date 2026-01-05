<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


$indexIdMenu=0;

$sql="  select ";
$sql.=" anuncios_anexos.foto_principal, ";
$sql.=" anuncios_anexos.titulo, ";
$sql.=" anuncios_anexos.subtitulo, ";
$sql.=" anuncios_anexos.conteudo_anuncio_anexo, ";
$sql.=" anuncios_anexos.fonte ";
$sql.=" from anuncios_anexos ";
$sql.=" where ";
$sql.=" (anuncios_anexos.id_anuncio = :id_anuncios_anexos) ";
$sql.=" limit 0 , 10";
$path="anuncios_anexos";
$prefix_news="";
$result=DAOquery($sql,['id_anuncios_anexos'=>$id_anuncio],true,"");
$elements_anucio_attachment=[];
if(isset($result["elements"]) && !empty($result["elements"]))
    $elements_anucio_attachment=$result["elements"];
$total=count($elements_anucio_attachment);
$lines=ceil($total/3);

?>


<?php  for ($j = 0; $j < $lines; $j++) {?>
<div class="row mt-3 block-row-1 d-flex flex-wrap align-items-stretch justify-content-center">
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
		if(count($elements_anucio_attachment)>$index){
			$element=$elements_anucio_attachment[$index];
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
			$subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$url="Patrocinador/".$nome_anuncio."/".$titulo;
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
		}
		require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
        
	}?>
</div>
<?php }?>