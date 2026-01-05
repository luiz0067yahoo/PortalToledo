<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


    $indexIdMenu=0;
    $params=null;
    $sql="  select ";
    $sql.=" noticias.foto_principal, ";
    $sql.=" noticias.titulo, ";
    $sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url,";
    $sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal";
    $sql.=" from noticias ";
    $sql.=" left join menus filho on(filho.id=noticias.id_menu) ";
    $sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
    $sql.=" where ";
    $sql.=" (noticias.slide_show) ";
    $sql.=" and(";
    $sql.="  ((:ler_menu='')and(:ler_sub_menu=''))";//home
    $sql.="  or ";
    $sql.="  (((filho.nome=:ler_menu)or(pai.nome=:ler_menu))and(:ler_sub_menu=''))";//menu
    
    $sql.="  or ";
    $sql.="  ((pai.nome=:ler_menu)and(filho.nome=:ler_sub_menu))";//sub menu
    
    $sql.="  )";
    $sql.=" order by noticias.id desc";
    $sql.=" limit 0 , 6";
    $params=array("ler_menu"=>$GLOBALS["ler_menu"],"ler_sub_menu"=>$GLOBALS["ler_sub_menu"]);
	$result_slide_show=DAOquery($sql,$params,true,"");
	$elements= $result_slide_show["elements"];
?>
<div id="carouselExampleIndicators" class="carousel slide height-parent  " data-ride="carousel" style="overflow:hidden">   
  <ol class="carousel-indicators mb-0" >
    <?php for($i=0;$i<count($elements);$i++){ ?>
    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>" class="<?php if($i==0) echo "active" ?>"></li>
    <?php } ?>
  </ol>
  <div class="carousel-inner height-parent ">
<?php 
    
   
        
    for($i=0;$i<count($elements);$i++){ 
        $element=$elements[$i];
        $foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
        $titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
        $menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
        $url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
       
       for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
    		if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
    			break;
    	}

        
    ?>
    <div class="carousel-item <?php if($i==0) echo "active" ?> height-parent  bg-menu-<?php echo $menu_index; ?>">
        <a href="https://<?php echo $_SERVER["HTTP_HOST"];?>/ler/<?php echo $url; ?>"class="height-parent text-decoration-none">
      <div class="d-flex align-content-end flex-wrap " style="width:100%;height:100%;background: url('<?php echo $GLOBALS["base_url"];?>/uploads/noticias/800x600/<?php echo $foto_principal;?>') no-repeat center  ; background-size:  100% 100%;">
          <h4 class="w-100 text-center pb-4 m-0 text-white" style="display: inline-block;text-shadow: 0px 0px 5px rgba(0,0,0,1);background-color:rgba(0,0,0,0.5)"> <?php echo $titulo;?> </h4>
</div>
    
  
        </a>
    </div>
    <?php } ?>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
   <?php if(count($elements)>1){ ?>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
  <?php }?>
</div>