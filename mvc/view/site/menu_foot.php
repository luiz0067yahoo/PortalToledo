					<div class="row d-flex flex-wrap text-center px-1 pt-1 "style="color:#1d2554;" >
							<?php
                                $menus=array();
								$sql="select id,nome,convertUrl(nome) as nome_url from menus where (menus.ocultar=0) and (id_menu is :id_menu) and nome!='home'";
								$result_menu=DAOquery($sql,["id_menu"=>null],true,"");
								if (isset($result_menu["elements"])){
									$menus=$result_menu["elements"];
									$GLOBALS["menus"]=$menus;
								}
						        for($i=1;$i<=count($menus);$i++){
								    $menu_nome=$result_menu["elements"][$i-1]["nome"];
								    $menu_id=$result_menu["elements"][$i-1]["id"];
								    $nome_url=$result_menu["elements"][$i-1]["nome_url"];
								    $prefixo="/ler";
                                    if ((strtolower($menu_nome)=="fotos") or (strtolower($menu_nome)=="vídeos"))
                                        $prefixo="";
								?>
								<div class=" bg-menu-foot"   style="flex-grow: 1;	flex-shrink: 1;	flex-basis: auto;">
									<div class="w-100 bg-menu-foot text-left text-uppercase " >
									    <a  class="nav-link text-capitalize dropdown-item h6" href="#"  ><?php echo $menu_nome;?></a>
									</div>
									<?php
        								$sub_menus=array();
        								$sql="select id,nome,convertUrl(nome) as nome_url,tema,icone from menus where (id_menu = $menu_id)";
        								$result_sub_menu=DAOquery($sql,"",true,"");
        								if (isset($result_sub_menu["elements"])){
									?>
									<div class="w-100 bg-menu-foot text-left text-uppercase " >
									    <?php
									        for($j=1;$j<=count($result_sub_menu["elements"]);$j++){ 
                                                $icone=$result_sub_menu["elements"][$j-1]["icone"];
                                                $sub_menu_nome=$result_sub_menu["elements"][$j-1]["nome"];
                                                $tema=$result_sub_menu["elements"][$j-1]["tema"];
                                                $sub_menu_nome_url=$result_sub_menu["elements"][$j-1]["nome_url"];
									    ?>
    										<a  class="dropdown-item h6" href="<?php echo $GLOBALS["base_url"]?><?php echo $prefixo;?>/<?php echo $nome_url; ?>/<?php echo $sub_menu_nome_url;?>/">
    										    <?php if(isset($icone)&&false){ ?><br><img src="<?php echo "https://$_SERVER[HTTP_HOST]";?>/uploads/menu/160x120/<?php echo $icone;?>" class="d-block rounded-circle w-25 square"  ><?php } ?>
    										    <?php echo $sub_menu_nome; ?>
    									    	<?php if (isset($tema) && !empty($tema)){?>
    										        <br><b><?php echo $tema; ?></b>
    										         
    										    <?php } ?>
    										</a>
								        <?php }?>
									</div>
									<?php }?>
								</div>
								<?php }?>

					</div>