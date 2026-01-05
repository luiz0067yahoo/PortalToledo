				<nav class="navbar navbar-expand-lg w-100 p-0">
					<div class="container-fluid p-0 align-items-stretch" >
							<ul id="navbarNavDropdown" class="show navbar-nav navbar-collapse collapse  h5 text-center  w-100 align-items-stretch "style="min-height:0px" >
							<?php
                                $menus=array();
								$sql="select id,nome,convertUrl(nome) as nome_url from menus where (menus.ocultar=0) and (id_menu is :id_menu) and (nome!='home') and not(id is null)";
								$result_menu=DAOquery($sql,["id_menu"=>null],true,"");
								if (isset($result_menu["elements"])){
									$menus=$result_menu["elements"];
									$GLOBALS["menus"]=$menus;
								}
								$i=1;
						        for($i=1;$i<=count($menus);$i++){
								    $menu_nome=$result_menu["elements"][$i-1]["nome"];
								    $menu_id=$result_menu["elements"][$i-1]["id"];
								    $nome_url=$result_menu["elements"][$i-1]["nome_url"];
								    $prefixo="/ler";
                                    if ((strtolower($menu_nome)=="fotos") or (strtolower($menu_nome)=="vídeos"))
                                        $prefixo="";
								?>
								<li class=" col nav-item  text-color-<?php echo $i; ?> "  style="padding-left: 0;padding-right: 0; min-width:<?php echo round(strlen($menu_nome)*11.3);?>px"  >
									
									<a id="menu<?php echo $i; ?>"   class="p-0 nav-link dropdown-toggle text-capitalize h-100 d-flex justify-content-center align-items-center" href="#"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $menu_nome;?></a>
									<?php
        								$sub_menus=array();
        								$sql="select id,nome,convertUrl(nome) as nome_url,tema,icone from menus where (id_menu = $menu_id)";
        								$result_sub_menu=DAOquery($sql,"",true,"");
        								if (isset($result_sub_menu["elements"])){
									?>
									<div style="<?php if($i==6) echo "left:-150px; min-width:340px;" ;?>" class="dropdown-menu bg-menu-<?php echo $i; ?> text-left text-uppercase " id="submenu<?php echo $i; ?>" aria-labelledby="menu<?php echo $i; ?>">
									    <?php
									        for($j=1;$j<=count($result_sub_menu["elements"]);$j++){ 
                                                $icone=$result_sub_menu["elements"][$j-1]["icone"];
                                                $sub_menu_nome=$result_sub_menu["elements"][$j-1]["nome"];
                                                $tema=$result_sub_menu["elements"][$j-1]["tema"];
                                                $sub_menu_nome_url=$result_sub_menu["elements"][$j-1]["nome_url"];
									    ?>
    										<a style="<?php if($i==6) echo "color:#000000;"?>" class="dropdown-item" href="<?php echo $GLOBALS["base_url"]?><?php echo $prefixo;?>/<?php echo $nome_url; ?>/<?php echo $sub_menu_nome_url;?>/">
    										    <?php if(isset($icone)){ ?><br><img src="<?php echo "https://$_SERVER[HTTP_HOST]";?>/uploads/menu/160x120/<?php echo $icone;?>" class="d-block rounded-circle " style="width:64px;height:64px;" ><?php } ?>
    										    <?php echo $sub_menu_nome; ?>
    										    <?php if (isset($tema) && !empty($tema)){?>
    										        <br><b><?php echo $tema; ?></b>
    										    <?php } ?>
    										</a>
								        <?php }?>
									</div>
									<?php }?>
								</li>
								<?php }?>
								<li class=" col nav-item  text-color-<?php echo $i+1; ?> "  style="padding-left: 0;padding-right: 0; min-width:<?php echo round(strlen($menu_nome)*11.3);?>px"  >
									<a id="menu<?php echo $i+1; ?>"   class="p-0 nav-link text-capitalize h-100 d-flex justify-content-center align-items-center" href="<?php echo $GLOBALS["base_url"]?>/contato">Contato</a>
								</li>
								<a class="link_top" style="color:#5DADE2!important;"  href="https://www.tempo.com/toledo_parana-l116233.htm">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-sun-fill" viewBox="0 0 16 16">
									<path d="M11.473 11a4.5 4.5 0 0 0-8.72-.99A3 3 0 0 0 3 16h8.5a2.5 2.5 0 0 0 0-5h-.027z"/>
									<path d="M10.5 1.5a.5.5 0 0 0-1 0v1a.5.5 0 0 0 1 0v-1zm3.743 1.964a.5.5 0 1 0-.707-.707l-.708.707a.5.5 0 0 0 .708.708l.707-.708zm-7.779-.707a.5.5 0 0 0-.707.707l.707.708a.5.5 0 1 0 .708-.708l-.708-.707zm1.734 3.374a2 2 0 1 1 3.296 2.198c.199.281.372.582.516.898a3 3 0 1 0-4.84-3.225c.352.011.696.055 1.028.129zm4.484 4.074c.6.215 1.125.59 1.522 1.072a.5.5 0 0 0 .039-.742l-.707-.707a.5.5 0 0 0-.854.377zM14.5 6.5a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
										</svg> 
								</a>
							</ul>
					</div>
				</nav>
