<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


$indexIdMenu=0;

$sql="  select ";
$sql.=" noticias_anexos.foto_principal, ";
$sql.=" noticias_anexos.titulo, ";
$sql.=" noticias_anexos.subtitulo, ";
$sql.=" noticias_anexos.conteudo_noticia_anexo, ";
$sql.=" noticias_anexos.fonte ";
$sql.=" from noticias_anexos ";
$sql.=" where ";
$sql.=" (noticias_anexos.id_noticia = :id_noticias_anexos) ";
$sql.=" limit 0 , 10";
$result=DAOquery($sql,['id_noticias_anexos'=>$id_noticia],true,"");
$elements=$result["elements"];
$fontes="";
for ($i=0;$i<count($elements);$i++){
    $element=$elements[$i];
    $titulo_anexo=$element["titulo"];
    $subtitulo_anexo=$element["subtitulo"];
    $foto_principal_anexo=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
    $conteudo_noticia_anexo=$element["conteudo_noticia_anexo"];
    $fonte_anexo=$element["fonte"];
    $fonte=(isset($fontes)&& !empty($fontes))?$fontes.",".$fonte_anexo:$fonte_anexo;
    ?>
    
    <div class="row  block-row-1">
        <div class="col-sm-12 h-100" >
    		<div class="menu<?php echo $menu_index;?>"><p class="text-color-<?php echo $menu_index;?> h4"><?php echo $titulo_anexo; ?></p></div>
    	</div>  
    </div>
    <div class="row  block-row-1">
        <div class="col-sm-12 h-100" >
    		<div class=" menu<?php echo $menu_index;?>"><p class="text-muted h4"><?php echo $subtitulo_anexo;?></p></div>
    	</div>  
    </div>
    <div class="row py-0 block-row-3">
        <div class="col-sm-7 h-100 mx-auto" >
    			<?php if(isset($foto_principal_anexo)&&(true)){?>
    			<a><img src="<?php echo $GLOBALS["base_url"];?>/uploads/noticias_anexos/1024x768/<?php  echo $foto_principal_anexo;?>" class="rounded mx-auto d-block" ></a>
    			<?php }?>  
    	</div>  
    </div>
    <div class="row py-0 block-row-3">
        <div class="col-sm-12 " >
    			<p class="w-100 text-color-<?php echo $menu_index;?> "><?php echo $conteudo_noticia_anexo;?></p>
    			<BR>
    			<BR>
    	</div>  
    </div>
<?php } ?>