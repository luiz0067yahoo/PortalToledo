<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


$indexIdMenu=0;
$sql=" select ";
if($GLOBALS["ler_sub_menu"]!=''){
    $sql.=" fotos.foto,";
    $sql.=" fotos.nome,";
}
else{
    $sql.=" album_fotos.nome, ";
    $sql.=" (select fotos.foto from fotos where(fotos.id_album=album_fotos.id) order by fotos.id desc limit 0,1) as foto, ";
}
$sql.=" album_fotos.nome, IF(filho.id_menu is null, concat(filho.nome,'/',album_fotos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_fotos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
$sql.=" from album_fotos ";
if($GLOBALS["ler_sub_menu"]!=''){
    $sql.=" left join fotos on(fotos.id_album=album_fotos.id) ";
}
$sql.=" left join menus filho on(filho.id=album_fotos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and (";
$sql.=" (((filho.nome  like :ler_menu ) or (pai.nome  like :ler_menu)) and ( '' like :ler_sub_menu ))";
$sql.=" or ";
$sql.=" ((filho.nome like :ler_sub_menu ) and (pai.nome like :ler_menu))";
$sql.=" )";
$sql.=" limit 0 , 10";
$findsub_menu="%".simbolTo_($GLOBALS['ler_sub_menu'])."%";
$findMenu="%".simbolTo_($GLOBALS['ler_menu'])."%";
$params=array("ler_menu"=>$findMenu,"ler_sub_menu"=>$findsub_menu);

$result_slide_show=DAOquery($sql,$params,true,"");

?>
<div id="carouselExampleIndicators" class="carousel slide block-row-2" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php for($i=0;$i<count($result_slide_show["elements"]);$i++){ ?>
    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>" class="<?php if($i==0) echo "active" ?>"></li>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php for($i=0;$i<count($result_slide_show["elements"]);$i++){ 
        $foto="";
        try{$foto=$result_slide_show["elements"][$i]["foto"];}catch(Exception $e){}
        $nome=$result_slide_show["elements"][$i]["nome"];
        $url=$result_slide_show["elements"][$i]["url"];
        $menu_principal=$result_slide_show["elements"][$i]["menu_principal"];

        for ($menu_index_slide_show = 1; $menu_index_slide_show <= count($GLOBALS["menus"]); $menu_index_slide_show++){
            if($GLOBALS["menus"][$menu_index_slide_show-1]["nome"]==$menu_principal)
                break;
        }
    ?>
    <div class="carousel-item <?php if($i==0) echo "active" ?> block-row-2 bg-menu-<?php echo $menu_index_slide_show; ?>">
      <div 
      style=" 
  width:100%;
  height:180px;
  background: url('<?php echo $GLOBALS["base_url"];?>/uploads/album/320x240/<?php echo $foto ;?>') no-repeat center  ; 
  "></div>
  
    <p class="w-100 text-center mt-1" ><a href="<?php echo $GLOBALS["base_url"];?>/<?php echo $url;?>" class="link-color-<?php echo $menu_index_slide_show; ?>">  <?php echo $nome;?>  </a></p>
    </div>
    <?php } ?>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>