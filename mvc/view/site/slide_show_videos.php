<?php
    $indexIdMenu=0;
    $params=null;
    $sql_slide_show_videos=" select ";
    if($GLOBALS["ler_sub_menu"]!=''){
        $sql_slide_show_videos.=" videos.video,";
        $sql_slide_show_videos.=" videos.nome,";
    }
    else{
        $sql_slide_show_videos.=" album_videos.nome, ";
        $sql_slide_show_videos.=" (select videos.video from videos where(videos.id_album=album_videos.id) order by videos.id desc limit 0,1) as video, ";
    }
    $sql_slide_show_videos.=" album_videos.nome, IF(filho.id_menu is null, concat(filho.nome,'/',album_videos.nome),";
    $sql_slide_show_videos.=" concat(pai.nome,'/',filho.nome,'/',album_videos.nome)) as url, ";
    $sql_slide_show_videos.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal ";
    $sql_slide_show_videos.=" from album_videos ";
    if($GLOBALS["ler_sub_menu"]!=''){
        $sql_slide_show_videos.=" left join videos on(videos.id_album=album_videos.id) ";
    }
    $sql_slide_show_videos.=" left join menus filho on(filho.id=album_videos.id_menu) ";
    $sql_slide_show_videos.=" left join menus pai on (pai.id=filho.id_menu) ";
    $sql_slide_show_videos.=" where  ";
    $sql_slide_show_videos.=" (pai.id_menu is null) ";
    $sql_slide_show_videos.=" and (";
    $sql_slide_show_videos.=" (((filho.nome=:ler_menu ) or (pai.nome=:ler_menu)) and (:ler_sub_menu=''))";
    $sql_slide_show_videos.=" or ";
    $sql_slide_show_videos.=" ((filho.nome=:ler_sub_menu ) and (pai.nome=:ler_menu))";
    $sql_slide_show_videos.=" )";
    $sql_slide_show_videos.=" limit 0 , 6";
    $params=array("ler_menu"=>$GLOBALS["ler_menu"],"ler_sub_menu"=>$GLOBALS["ler_sub_menu"]);
    $noticias_slide_show=array();
    $noticias_campos_slide_show=array();
	$result_slide_show=DAOquery($sql_slide_show_videos,$params,true,"");
	if (isset($result_slide_show["elements"])){$album_videos_slide_show=$result_slide_show["elements"];}
	if(isset($result_slide_show["title"])){$album_videos_campos_slide_show=$result_slide_show["title"];}
	
?>

<div id="carouselExampleIndicators" class="carousel slide block-row-2" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php for($i=0;$i<count($album_videos_slide_show);$i++){ ?>
    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $i; ?>" class="<?php if($i==0) echo "active" ?>"></li>
    <?php } ?>
  </ol>
  <div class="carousel-inner">
    <?php for($i=0;$i<count($album_videos_slide_show);$i++){ 
        $video=$album_videos_slide_show[$i]['video'];//video
        $nome=$album_videos_slide_show[$i]['nome'];//nome
        $url=$album_videos_slide_show[$i]['url'];//url
        for ($menu_index_slide_show = 1; $menu_index_slide_show <= count($GLOBALS["menus"]); $menu_index_slide_show++){
            if($GLOBALS["menus"][$menu_index_slide_show-1]["nome"]==$album_videos_slide_show[$i]['menu_principal'])
                break;
        }
    ?>
    <div class="carousel-item <?php if($i==0) echo "active" ?> block-row-2 bg-menu-<?php echo $menu_index_slide_show; ?>">
      <center><iframe class="mx-auto" src="https://www.youtube.com/embed/<?php echo $video;?>" type="text/html" width="300px"	height="150px" frameborder=0></iframe></center>
  
    <p class="w-100 text-center mt-1" ><a href="<?php echo $GLOBALS["base_url"]."/".$url;?>" class="link-color-<?php echo $menu_index_slide_show; ?>">  <?php echo $nome;?>  </a></p>
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