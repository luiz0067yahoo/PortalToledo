
<?php if($destaque==0){//true ?>
<div class="col-sm-3 d-flex flex-wrap pt-3 "  style="align-content: space-between;">
    <?php if ($first_row==1){ ?>
        <div class="w-100   d-flex flex-wrap  text-color-<?php if(count($elements_noticias)>$index)  echo $menu_index;?> px-0" style="height:auto;">
            <h3><b><?php if (isset($menu_principal) && !empty($menu_principal)){echo $menu_principal;}?>&nbsp;</b></h3>
    	</div>
        <hr class="w-100 mt-0 mb-3 bg-menu-<?php if(count($elements_noticias)>$index){  echo $menu_index; }else{ echo " d-none";} ?> " style="min-height:2px;">    </label>
    <?php } ?>
    <hr class="w-100 mb-0 "  style="min-height:1px;background-color:#fcfcfc;">
    <?php $url_full="http://tooeste.com.br$prefix_news/$url";?>
    <?php  $href=(isset($url) && !empty($url)&& !empty($url))?" href=\"".$url_full."\"":"";?>
    <div class="w-100  d-flex flex-wrap ">
    	<div class="w-100   d-flex flex-wrap  text-color-<?php if(count($elements_noticias)>$index)  echo $menu_index;?>" style="height:auto;"><a class="w-100 px-1 " <?php echo $href;?> ><b><?php if (isset($submenu) && !empty($submenu)){echo $submenu;} else{echo $menu_e_submenu;}?>&nbsp;</b></a>
    	</div>
        <hr class="w-100 mt-0 mb-3 bg-menu-<?php if(count($elements_noticias)>$index){  echo $menu_index; }else{ echo " d-none";} ?> " style="min-height:2px;">
    	
    	<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
    		<a <?php echo $href;?> class="w-100 rounded proportion-3x4 text-white d-flex align-items-center justify-content-center bg-menu-<?php if(count($elements_noticias)>$index)  echo $menu_index;?> " style="background-position: center center;background-size:cover;background-image:url('http://tooeste.com.br/uploads/<?php echo $path; ?>/1024x768/<?php  echo $foto_principal;?>');"></a>
    		<a class="w-100  link-color-<?php  echo $menu_index;?>" style="height:60px" <?php if(isset($url)){?>href="http://tooeste.com.br<?php echo $prefix_news; ?>/<?php echo $url;?>"<?php }?>  >
    		     <h6 class="title-news"><?php echo $titulo;?>&nbsp;</h6>
    	    </a>
    	<?php }else{ ?>
    		<a <?php echo $href;?>  class="w-100  proportion-3x4 d-flex   link-color-<?php  echo $menu_index;?>"  >
    	        <p class="link-color-<?php  echo $menu_index;?>" ><b><?php echo $titulo;?>&nbsp;</b></p>		
    		</a>
        	<a class="w-100  link-color-<?php  echo $menu_index;?>" style="height:60px">
        	</a>
    	<?php } ?>
    </div>
</div>
<?php } else {?>
<?php $url_full="http://tooeste.com.br$prefix_news/$url";?>
<?php  $href=(isset($url) && !empty($url)&& !empty($url))?" href=\"".$url_full."\"":"";?>
<div class="col-sm-3 d-flex flex-wrap pt-3 "  style="align-content: space-between;">
    <div class="w-100  bg-menu-<?php  echo $menu_index;?> d-flex flex-wrap ">
        <a <?php echo $href;?> class="w-100  bg-menu-<?php  echo $menu_index;?> d-flex flex-wrap p-1">
            <div class="w-100 mb-0 bg-transparent"  style="min-height:1px;"></div>
            <div class="w-100  d-flex flex-wrap " style="color:inherit;">
            	<div class="w-100   d-flex flex-wrap  " style="height:auto;">
            	    <label class="w-100 px-1 " <?php echo $href;?> >
            	        <b>
            	            <?php if (isset($submenu) && !empty($submenu)){echo $submenu;} else{echo $menu_e_submenu;}?>&nbsp;
            	        </b>
            	    </label>
            	</div>
                <hr class="w-100 mt-0 mb-3  bg-white" style="min-height:2px;">
            	<?php if(isset($foto_principal)&& !empty($foto_principal)){?>
            		<label  class="w-100 rounded proportion-3x4  d-flex align-items-center justify-content-center  " style="background-position: center center;background-size:cover;background-image:url('http://tooeste.com.br/uploads/<?php echo $path; ?>/1024x768/<?php  echo $foto_principal;?>');">
            		    
            		</label>
            		<label class="w-100  " style="height:80px" <?php if(isset($url)){?>href="http://tooeste.com.br<?php echo $prefix_news; ?>/<?php echo $url;?>"<?php }?>  >
            		     <h6 class="title-news"><?php echo $titulo;?>&nbsp;</h6>
            	    </label>

            	<?php }else{ ?>
            		<label <?php echo $href;?>  class="w-100  proportion-3x4 d-flex  "  >
            	        <p class="" ><b><?php echo $titulo;?>&nbsp;</b></p>		
            		</label>
                	<label class="w-100 " style="height:80px"></label>
            	<?php } ?>
            </div>
    	</a>
    </div>
</div>
<?php } ?>
