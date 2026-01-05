	    <?php if (($GLOBALS['ler_menu']!="Patrocinador")&&(empty($GLOBALS["ler_titulo_noticia"]))) { ?>

			<div class="row mt-3 block-row-2">
				<div class="col-sm-12 pt-3 d-flex align-content-end flex-wrap " >
					<div class="w-100 proportion-4x1 desktop-show  d-none" ><?php banner("banner meio noticia 3")?></div>
					<div class="w-100 proportion-3x4 mobile-show d-none" ><?php banner_mobile("banner meio noticia 3")?></div>
				</div>
			</div>	  
        	<?php }?>

<hr>


	


		<div class="row mt-3 d-flex justify-content-center">
			<div class="col-sm-<?php echo ($GLOBALS['ler_menu']!="Patrocinador")?12:9;  ?>">
				<h3><b>MAIS VISTAS</b></h3>
			</div>
		</div>

<?php
$day = date('w');
$week_start = date('Y-m-d', strtotime('-'.$day.' days'));
$week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));

$P_Dia = date("Y-m-01");
$U_Dia = date("Y-m-t");

$P_Dia = date('Y-m-d', strtotime("-7 days",strtotime(date("Y-m-d"))));
$U_Dia = date("Y-m-d");

$params=array(
    'data_inicio'=>$P_Dia,
    'data_fim'=>$U_Dia
);


$conditions_news="((noticias.date_update between :data_inicio and :data_fim))";
$conditions_photos="(album_fotos.date_update between :data_inicio and :data_fim)";
$conditions_videos="(album_videos.date_update between :data_inicio and :data_fim)";

$sql="  ( ";
$sql.="  select ";
$sql.=" 'news' as block_type, ";
$sql.=" noticias.foto_principal, ";
$sql.=" noticias.titulo, ";
$sql.=" noticias.subtitulo, ";
$sql.=" concat(CAST(noticias.conteudo_noticia AS CHAR(20) CHARACTER SET utf8),' ...') as intro,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url,";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, ";
$sql.=" noticias.date_insert, ";
$sql.=" noticias.acesso ";
$sql.=" from noticias ";
$sql.=" left join menus filho on(filho.id=noticias.id_menu) ";
$sql.=" left join menus pai  on (pai.id=filho.id_menu) "; 
$sql.=" where ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and (pai.nome != 'Fotos') ";
$sql.=" and (pai.nome != 'Vídeos') ";
$sql.=" and (filho.nome != 'Fotos') ";
$sql.=" and (filho.nome != 'Vídeos') ";
$sql.=" and(";
$sql.="  $conditions_news ";
$sql.="  ) ";//news
$sql.="  ) ";//news
$sql.=" union ";
$sql.=" ( ";
$sql.=" select ";
$sql.=" 'photo' as block_type, ";
$sql.=" (select fotos.foto from fotos where(fotos.id_album=album_fotos.id) order by fotos.id desc limit 0,1) as foto_principal, ";
$sql.=" album_fotos.nome as titulo, ";
$sql.=" '' as subtitulo, ";
$sql.=" '' as intro, ";
$sql.=" IF(filho.id_menu is null, concat(filho.nome,'/',album_fotos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_fotos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, ";
$sql.=" album_fotos.date_insert, ";
$sql.=" album_fotos.acesso ";
$sql.=" from album_fotos ";
$sql.=" left join menus filho on(filho.id=album_fotos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and ((pai.nome = 'Fotos') or (filho.nome = 'Fotos'))";
$sql.=" and (";
$sql.="  $conditions_photos ";
$sql.=" ) ";
$sql.=" ) ";
$sql.=" union ";// photos
$sql.=" ( ";// photos
$sql.=" select ";
$sql.=" 'video' as block_type, ";
$sql.=" (select videos.video from videos where(videos.id_album=album_videos.id) order by videos.id desc limit 0,1) as foto_principal, ";
$sql.=" album_videos.nome as titulo, ";
$sql.=" '' as subtitulo, ";
$sql.=" '' as intro, ";
$sql.=" IF(filho.id_menu is null, concat(filho.nome,'/',album_videos.nome),";
$sql.=" concat(pai.nome,'/',filho.nome,'/',album_videos.nome)) as url, ";
$sql.=" IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu,";
$sql.=" filho.nome as submenu,";
$sql.=" IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, ";
$sql.=" album_videos.date_insert, ";
$sql.=" album_videos.acesso ";
$sql.=" from album_videos ";
$sql.=" left join menus filho on(filho.id=album_videos.id_menu) ";
$sql.=" left join menus pai on (pai.id=filho.id_menu) ";
$sql.=" where  ";
$sql.=" (pai.id_menu is null) ";
$sql.=" and ((pai.nome = 'Vídeos') or (filho.nome = 'Vídeos'))";
$sql.=" and (";
$sql.="  $conditions_videos ";
$sql.=" ) ";
$sql.=" ) ";//videos
$sql.=" order by acesso desc ";
$sql.=" limit 0 , 9";
$result_all=DAOquery($sql,$params,true,"");
$elements_noticias=$result_all["elements"];
//echo $sql;
?>

<?php for ($j = 0; $j <= 2; $j++) {?>
<div class="row mt-3 block-row-2 d-flex justify-content-center">
	<?php for ($i = 0; $i < 3; $i++) {?>
    <?php 
		$index=$i+($j*3);
	    $path="";
        $prefix_news="/ler";
        $block_type="";
		$menu_principal=""; 
        $menu_e_submenu=""; 
        $submenu="";
        $titulo=""; 
        $subtitulo=""; 
        $intro=""; 
        $url=""; 
        $foto_principal="";
		if(count($elements_noticias)>$index){
			$element=$elements_noticias[$index];
			$block_type=(isset($element["block_type"])&& !empty($element["block_type"]))?$element["block_type"]:"";
			$titulo=(isset($element["titulo"])&& !empty($element["titulo"]))?$element["titulo"]:"";
    	    $subtitulo=(isset($element["subtitulo"])&& !empty($element["subtitulo"]))?$element["subtitulo"]:"";
			$intro=(isset($element["intro"])&& !empty($element["intro"]))?$element["intro"]:"";
			$menu_principal=(isset($element["menu_principal"])&& !empty($element["menu_principal"]))?$element["menu_principal"]:"";
			$menu_e_submenu=(isset($element["menu_e_submenu"])&& !empty($element["menu_e_submenu"]))?$element["menu_e_submenu"]:"";
			$submenu=(isset($element["submenu"])&& !empty($element["submenu"]))?$element["submenu"]:"";
			$url=(isset($element["url"])&& !empty($element["url"]))?$element["url"]:"";
			$foto_principal=(isset($element["foto_principal"])&& !empty($element["foto_principal"]))?$element["foto_principal"]:"";
			$date_insert=(isset($element["date_insert"])&& !empty($element["date_insert"]))?$element["date_insert"]:"";
			$acesso=(isset($element["acesso"])&& !empty($element["acesso"]))?$element["acesso"]:"";
	        $indexIdMenu=0;

		}
		for ($menu_index = 1; $menu_index <= count($GLOBALS["menus"]); $menu_index++){
            if($GLOBALS["menus"][$menu_index-1]["nome"]==$menu_principal)
                break;
        }
		if($block_type=='photo'){
		    $path="album";
		}
		else if($block_type=='news'){
    	    $path="noticias";
		}
		if($block_type=='video'){
		?>
		<div class="col-sm-3 h-100" >
		<div class="h-25 pt-3  menu<?php echo $menu_index;?>"><a class="link-color-<?php echo $menu_index;;?> " href="http://tooeste.com.br/<?php echo $url;?>"><?php echo $titulo;?></a></div>
		<div class="w-100 h-75 bg-menu-<?php echo $menu_index;?> " >
			<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
			<a href="http://tooeste.com.br/<?php echo $url;?>"><center><iframe class="mx-auto" src="https://www.youtube.com/embed/<?php echo $foto_principal;?>" type="text/html" width="100%"	height="100%" frameborder=0></iframe></center></a>
			<?php }?>
		</div>
	</div>
		<?php
		}
		else{
		    require($_SERVER['DOCUMENT_ROOT'].'/mvc/view/site/mini_news.php');
		}
        
	}?>

	<?php if ($GLOBALS['ler_menu']!="Patrocinador") { ?>
	<div class="col-sm-3 pt-3 d-flex align-content-end flex-wrap " style="padding-bottom:60px;" >
		<div class="w-100 proportion-3x4 " ><?php banner("banner lateral ".(10+$j));?></div>
	</div>
	<?php }?>
</div>
<?php }?>

<?php /*

( select 'news' as block_type, noticias.foto_principal, noticias.titulo, noticias.subtitulo, concat(CAST(noticias.conteudo_noticia AS CHAR(20) CHARACTER SET utf8),' ...') as intro, IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url, IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu, filho.nome as submenu, IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, noticias.date_insert, noticias.acesso from noticias left join menus filho on(filho.id=noticias.id_menu) left join menus pai on (pai.id=filho.id_menu) where (pai.id_menu is null) and (pai.nome != 'Fotos') and (pai.nome != 'Vídeos') and (filho.nome != 'Fotos') and (filho.nome != 'Vídeos') and( ((noticias.date_update between '2022-06-01' and '2022-06-30')) ) order by noticias.acesso desc ) union ( select 'photo' as block_type, (select fotos.foto from fotos where(fotos.id_album=album_fotos.id) order by fotos.id desc limit 0,1) as foto_principal, album_fotos.nome as titulo, '' as subtitulo, '' as intro, IF(filho.id_menu is null, concat(filho.nome,'/',album_fotos.nome), concat(pai.nome,'/',filho.nome,'/',album_fotos.nome)) as url, IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu, filho.nome as submenu, IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, album_fotos.date_insert, album_fotos.acesso from album_fotos left join menus filho on(filho.id=album_fotos.id_menu) left join menus pai on (pai.id=filho.id_menu) where (pai.id_menu is null) and ((pai.nome = 'Fotos') or (filho.nome = 'Fotos')) and ( (album_fotos.date_update between '2022-06-01' and '2022-06-30') ) order by album_fotos.acesso desc ) union ( select 'video' as block_type, (select videos.video from videos where(videos.id_album=album_videos.id) order by videos.id desc limit 0,1) as foto_principal, album_videos.nome as titulo, '' as subtitulo, '' as intro, IF(filho.id_menu is null, concat(filho.nome,'/',album_videos.nome), concat(pai.nome,'/',filho.nome,'/',album_videos.nome)) as url, IF(filho.id_menu is null, concat(convertUrl(filho.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome))) as menu_e_submenu, filho.nome as submenu, IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal, album_videos.date_insert, album_videos.acesso from album_videos left join menus filho on(filho.id=album_videos.id_menu) left join menus pai on (pai.id=filho.id_menu) where (pai.id_menu is null) and ((pai.nome = 'Vídeos') or (filho.nome = 'Vídeos')) and ( (album_videos.date_update between '2022-06-01' and '2022-06-30') ) order by album_videos.acesso desc ) limit 0 , 9
		

*/?>