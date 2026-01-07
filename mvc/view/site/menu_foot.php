<div class="container-fluid px-0 pt-0 bg-menu-foot" style="color:#1d2554;">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-0 text-center text-md-start">
        <?php
		require_once($GLOBALS["base_server_path_files"].'/mvc/model/menusDAO.php');
		$result_menu = (new menusDAO([]))->findMainMenus();
        $menus = $result_menu?$result_menu["elements"]:[];
            foreach ($menus as $menu) {
                $menu_nome = $menu["nome"];
                $menu_id   = $menu["id"];
                $nome_url  = $menu["nome_url"];

                // Define prefixo para URLs (sem /ler para Fotos e Vídeos)
                $prefixo = "/ler";
                if (in_array(strtolower($menu_nome ?? ""), ["fotos", "vídeos"])) {
                    $prefixo = "";
                }
                ?>
                <div class="col px-0">
                    <div class="bg-menu-foot h-100 d-flex flex-column p-3">
                        <!-- Título do menu principal -->
                        <h6 class="text-uppercase fw-bold mb-3">
                            <?php echo htmlspecialchars($menu_nome ?? ""); ?>
                        </h6>

                        <!-- Submenus -->
                        <?php
						$result_sub_menu = (new menusDAO([]))->findSubMenus($menu_id);
                    

                        if (!empty($result_sub_menu["elements"])) {
                            ?>
                            <div class="d-flex flex-column">
                                <?php foreach ($result_sub_menu["elements"] as $sub) {
                                    $sub_menu_nome     = $sub["nome"];
                                    $sub_menu_nome_url = $sub["nome_url"];
                                    $tema  = $sub["tema"] ?? '';
                                    $icone = $sub["icone"] ?? '';

                                    $link = $GLOBALS["base_url"] . $prefixo . "/" . $nome_url . "/" . $sub_menu_nome_url . "/";
                                    ?>
                                    <a href="<?php echo htmlspecialchars($link ?? ""); ?>"
                                       class="dropdown-item py-1 text-capitalize px-0">
                                        <?php echo htmlspecialchars($sub_menu_nome ?? ""); ?>
                                        <?php if (!empty($tema)) { ?>
                                            <br>
                                            <small class="text-muted fw-bold">
                                                <?php echo htmlspecialchars($tema ?? ""); ?>
                                            </small>
                                        <?php } ?>
                                    </a>
                                <?php } ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
            }
        
        ?>
    </div>
</div>
