<?php
define('SESSION_UPDATE_FLAG', 'banner_index_updated_on_this_load');
    
    // ----------------------------------------------------
    // FUNÇÃO PRINCIPAL SEQUENCIAL (NOVA)
    // $type_name: o tipo de banner (ex: "home principal")
    // $position_index: 1 para o 1º banner, 2 para o 2º, etc.
    // ----------------------------------------------------
    if (!function_exists("exibir_banner_sequencial")) {
        function exibir_banner_sequencial($type_name, $position_index)
        {
            // Chave de sessão para armazenar o ÍNDICE GLOBAL
            $session_key_global = 'global_banner_start_index_' . str_replace(' ', '_', $type_name);
            $carousel_id = 'banner-carousel-' . str_replace(' ', '-', $type_name) . '-' . $position_index;

            try {
                // Sua consulta SQL (presumida)
                $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:type_name)";
                $result = DAOquery($sql, array("type_name" => $type_name), true, "");
                
                $elements = [];
                $total_banners = 0;
                
                if (!empty($result["elements"])) {
                    $elements = $result["elements"];
                    $total_banners = count($elements);
                }
                
                if ($total_banners === 0) {
                    return; // Não há banners para exibir
                }
                
                // 1. Lógica de Sincronização e Sessão
                
                // Pega o índice global salvo na sessão (ponto de partida da sequência)
                $global_start_index = isset($_SESSION[$session_key_global]) ? (int)$_SESSION[$session_key_global] : 0;
                
                // 2. Calcula o índice do banner a ser EXIBIDO NESTA POSIÇÃO
                // Fórmula: (Índice Global + Posição - 1) % Total
                // A posição é -1 porque o array começa em 0.
                $display_index = ($global_start_index + ($position_index - 1)) % $total_banners;

                // 3. Atualiza o Índice Global para a PRÓXIMA requisição (apenas uma vez por refresh)
                // Se a flag ainda não foi definida, atualiza o índice e define a flag.
                if (!isset($_SESSION[SESSION_UPDATE_FLAG])) {
                    $next_global_start_index = ($global_start_index + 1) % $total_banners;
                    $_SESSION[$session_key_global] = $next_global_start_index;
                    $_SESSION[SESSION_UPDATE_FLAG] = true;
                }

                // 4. Estrutura HTML/Bootstrap para rotação a cada 9s
                
                echo '<div id="' . $carousel_id . '" class="carousel slide w-100 p-2 my-2" data-ride="carousel" data-interval="7000" style="background-color:#eee;">';
                echo '  <div class="carousel-inner">';
                
                // Itera sobre TODOS os banners (mas só o $display_index recebe a classe 'active')
                for ($i = 0; $i < $total_banners; $i++) {
                    $element = $elements[$i];
                    $Foto = $element["foto"];
                    $Nome = $element["nome"];
                    
                    // Apenas o banner CULCULADO recebe a classe 'active' para ser o primeiro a aparecer
                    $active_class = ($i == $display_index) ? ' active' : '';
                    
                    if ((isset($Foto)) && file_exists($_SERVER["DOCUMENT_ROOT"]."/uploads/anuncio/1024x768/".$Foto)) {                    
                    ?>
                        <div class="carousel-item<?php echo $active_class; ?>">
                            <a class="w-100 d-flex flex-wrap " href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" title="<?php echo $Nome; ?>" style="text-decoration:none;">
                                <img src="<?php echo "https://$_SERVER[HTTP_HOST]";?>/uploads/anuncio/1024x768/<?php echo $Foto;?>" class="m-2 p-0 d-block w-100 proportion-5x1 rounded" alt="<?php echo $Nome; ?>">
                            </a>
                        </div>
                    <?php
                    }
                }
                
                echo '  </div>'; // Fecha carousel-inner
                
                // Controles de navegação (Opcional)
                if ($total_banners > 1) {
                    echo '<a class="carousel-control-prev" href="#' . $carousel_id . '" role="button" data-slide="prev">';
                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    echo '<span class="sr-only">Anterior</span>';
                    echo '</a>';
                    echo '<a class="carousel-control-next" href="#' . $carousel_id . '" role="button" data-slide="next">';
                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    echo '<span class="sr-only">Próximo</span>';
                    echo '</a>';
                }
                
                echo '</div>'; // Fecha carousel slide
                
            } catch (Exception $e) {
                // Log de erro
            }
        }
    }
    
    // ----------------------------------------------------
    // ADAPTANDO AS FUNÇÕES EXISTENTES (WRAPPERS)
    // ----------------------------------------------------
    
    // random_banner agora aceita o índice da posição
    if (!function_exists("random_banner")) {
        // Adicionamos um valor padrão de 1 para não quebrar chamadas antigas
        function random_banner($type_name, $position_index = 1)
        {
            exibir_banner_sequencial($type_name, $position_index);
        }
    }

    // random_banner_mobile usa o segundo parâmetro como o índice da posição
    if (!function_exists("random_banner_mobile")) {
        function random_banner_mobile($type_name, $position_index)
        {
            exibir_banner_sequencial($type_name, $position_index);
        }
    }

?>
<?php
if (!function_exists("banner")) {

    function banner($type_name) {
        ?>
        <div  class="carousel slide w-100 h-100" data-bs-ride="carousel" >
            <div class="carousel-inner h-100">
                <?php
                $Foto = "";
                $url = "";
                $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:type_name)";
                $result = DAOquery($sql, array("type_name" => $type_name), true, "");
                $elements = $result["elements"];
                for ($i = 0; $i < count($elements); $i++) {
                    $element = $elements[$i];
                    $Foto = $element["foto"];
                    $foto_expandida = $element["foto_expandida"];
                    $Nome = $element["nome"];
                    try {
                        if ((isset($Foto))) {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/1440x900/" . $Foto)) {
                                ?>
                                <div class="carousel-item h-100 <?php if ($i == 0) echo "active" ?>">
                                    <a href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" class="d-flex w-100 height-parent" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $Foto; ?>" class="d-block w-100 modal_banner" alt="<?php echo $Nome; ?>"  fotoexpandida="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $foto_expandida; ?>"></a>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}
?>
<?php
if (!function_exists("banner_mobile")) {

    function banner_mobile($type_name) {
        ?>
        <div  class="carousel slide w-100 h-100" data-bs-ride="carousel" >
            <div class="carousel-inner h-100">
                <?php
                $Foto = "";
                $url = "";
                $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:type_name)";
                $result = DAOquery($sql, array("type_name" => $type_name), true, "");
                $elements = $result["elements"];
                for ($i = 0; $i < count($elements); $i++) {
                    $element = $elements[$i];
                    $Foto_mobile = $element["foto_mobile"];
                    $foto_mobile_expandida = $element["foto_mobile_expandida"];
                    $Nome = $element["nome"];
                    try {
                        if ((isset($Foto))) {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/1440x900/" . $Foto_mobile)) {
                                ?>
                                <div class="carousel-item h-100 <?php if ($i == 0) echo "active" ?>">
                                    <a href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" class="d-flex w-100 height-parent" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $Foto_mobile; ?>" class="d-block w-100 modal_banner" alt="<?php echo $Nome; ?>"  fotoexpandida="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $foto_mobile_expandida; ?>"></a>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}
?>
<?php
if (!function_exists("mini_banner")) {

    function mini_banner($type_name) {
        ?>
        <div  class="carousel slide w-100 h-100" data-bs-ride="carousel" >
            <div class="carousel-inner h-100">
                <?php
                $Foto = "";
                $url = "";
                $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:type_name)";
                $result = DAOquery($sql, array("type_name" => $type_name), true, "");
                $elements = $result["elements"];
                for ($i = 0; $i < count($elements); $i++) {
                    $element = $elements[$i];
                    $Foto = $element["foto"];
                    $foto_expandida = $element["foto_expandida"];
                    $Nome = $element["nome"];
                    try {
                        if ((isset($Foto))) {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/160x120/" . $Foto)) {
                                ?>
                                <div class="carousel-item h-100 <?php if ($i == 0) echo "active" ?>">
                                    <a href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>"  classname="d-flex w-100 height-parent" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/160x120/<?php echo $Foto; ?>" class="d-block w-100 modal_banner" alt="<?php echo $Nome; ?>" style="height:120px;" fotoexpandida="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $foto_expandida; ?>"></a>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}
?>
<?php
if (!function_exists("banner_scroll")) {

    function banner_scroll($tipo_nome) {
        ?>
        <div class="box_scroll" direct="left">
            <?php
            $Foto = "";
            $url = "";
            $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:tipo_nome)";
            $result = DAOquery($sql, array("tipo_nome" => $tipo_nome), true, "");
            $elements = $result["elements"];
            for ($w = 0; $w < 6; $w++) {
                for ($i = 0; $i < count($elements); $i++) {
                    $element = $elements[$i];
                    $Foto = $element["foto"];
                    $foto_expandida = $element["foto_expandida"];
                    $Nome = $element["nome"];
                    try {
                        if ((isset($Foto))) {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/160x120/" . $Foto)) {
                                ?>
                                <div class="item flex-wrap">
                                    <h6 class="w-100 text-dark" style="min-height:20px;"><?php echo $Nome; ?></h6>            
                                    <a  class="p-0" style="" href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/160x120/<?php echo $Foto; ?>" class="d-block w-100 modal_banner "  style="height:120px;overflow:hidden;" fotoexpandida="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1440x900/<?php echo $foto_expandida; ?>"></a>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
            ?>
        </div>
        <?php
    }

}
?>
<?php
if (!function_exists("banner_menu_scroll")) {

    function banner_menu_scroll($id_menu) {
        ?>
        <div class="box_scroll" direct="left">
            <?php
            $Foto = "";
            $url = "";
            $sql = "select ";
            $sql .= " sub_menu.id, ";
            $sql .= " sub_menu.nome as sub_mmenu_nome, ";
            $sql .= " sub_menu.tema, ";
            $sql .= " convertUrl(sub_menu.nome) as sub_menu_nome_url,";
            $sql .= " convertUrl(menu_pai.nome) as menu_nome_url,";
            $sql .= " sub_menu.icone ";
            $sql .= " from menus sub_menu ";
            $sql .= " left join menus  menu_pai on(menu_pai.id=sub_menu.id_menu)";
            $sql .= " where (menu_pai.nome = :id_menu)";
            $result = DAOquery($sql, array("id_menu" => $id_menu), true, "");
            $elements = $result["elements"];
            for ($w = 0; $w < 6; $w++) {
                for ($i = 0; $i < count($elements); $i++) {
                    $element = $elements[$i];
                    $Foto = $element["icone"];
                    $tema = $element["tema"];
                    $Nome = $element["sub_mmenu_nome"];
                    $menu_id = $element["id"];
                    $url = $element["menu_nome_url"] . "/" . $element["sub_menu_nome_url"];
                    try {
                        if ((isset($Foto))) {
                            if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/menu/160x120/" . $Foto)) {
                                $result_last_news = DAOquerySelect("noticias", ["titulo", "subtitulo"], "", ["noticias.ocultar" => 0, "noticias.id_menu" => $menu_id], null, null, ["noticias.id" => "desc"], ["page" => 1, "row_count" => 1]);
                                $elements_last_news = [];
                                $titulo = "";
                                $subtitulo = "";
                                if (isset($result_last_news["elements"]) && !empty($result_last_news["elements"])) {
                                    $elements_last_news = $result_last_news["elements"];
                                    if (count($elements_last_news) > 0) {
                                        $titulo = $elements_last_news[0]["titulo"];
                                        $subtitulo = $elements_last_news[0]["subtitulo"];
                                    }
                                }
                                ?>
                                <div class="item flex-wrap">
                                    <h6 class="w-100 text-dark" style="min-height:60px;"><?php echo $Nome; ?><br><b><?php echo $titulo; ?></b></h6>            
                                    <a  class="d-flex justify-content-center align-items-center" style="" href="https://tooeste.com.br/ler/<?php echo $url; ?>/" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/menu/160x120/<?php echo $Foto; ?>" class="d-block modal_banner "  style="height:120px;overflow:hidden;" ></a>
                                </div>
                                <?php
                            }
                        }
                    } catch (Exception $e) {
                        
                    }
                }
            }
            ?>
        </div>
        <?php
    }

}
?>
<?php
if (!function_exists("mini_banner_news")) {

    function mini_banner_news($tipo_nome, $size) {
        ?>
        <div class="p-2 mt-3 w-100 d-flex flex-wrap justify-content-center" style="background-color:#eee;">
            <?php
            $Foto = "";
            $url = "";
            $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:tipo_nome)";
            $result = DAOquery($sql, array("tipo_nome" => $tipo_nome), true, "");
            $elements = [];
            $i = 0;
            while ($i < min($size, count($result["elements"]))) {
                $index = random_int(0, count($result["elements"]) - 1);
                $element = $result["elements"][$index];
                if (!in_array($element, $elements)) {

                    array_push($elements, $element);
                    $i++;
                }
            }
            for ($i = 0; $i < count($elements); $i++) {
                $element = $elements[$i];
                $Foto = $element["foto"];
                $foto_expandida = $element["foto_expandida"];
                $Nome = $element["nome"];
                $intro = $element["introducao"];
                try {
                    if ((isset($Foto))) {
                        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/160x120/" . $Foto)) {
                            ?>

                            <a  class="m-2 p-0 " style="" href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" title="<?php echo $Nome; ?>"><img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/160x120/<?php echo $Foto; ?>" class="d-block rounded"  style="width:120px;height:90px;" ></a>

                            <?php
                        }
                    }
                } catch (Exception $e) {
                    
                }
            }
            ?>
        </div> 
        <?php
    }

}
?>

<?php
if (!function_exists("mini_banner_news_line")) {

    function mini_banner_news_line($tipo_nome, $size) {
        $Foto = "";
        $url = "";
        $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:tipo_nome)";
        $result = DAOquery($sql, array("tipo_nome" => $tipo_nome), true, "");
        $elements = [];
        $i = 0;
        while ($i < min($size, count($result["elements"]))) {
            $index = random_int(0, count($result["elements"]) - 1);
            $element = $result["elements"][$index];
            if (!in_array($element, $elements)) {
                array_push($elements, $element);
                $i++;
            }
        }
        for ($i = 0; $i < count($elements); $i++) {
            $element = $elements[$i];
            $Foto = $element["foto"];
            $descricao = $element["descricao"];
            $foto_expandida = $element["foto_expandida"];
            $Nome = $element["nome"];
            $intro = $element["introducao"];
            $intro2 = $element["introducao2"];
            try {
                if ((isset($Foto))) {
                    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/160x120/" . $Foto)) {
                        ?>
                        <a class="p-2 my-2 w-100 d-flex  " style="background-color:#eee;" href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" title="<?php echo $Nome; ?>">
                            <img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/160x120/<?php echo $Foto; ?>" class="m-2 p-0 d-block rounded"  style="width:120px;height:90px;" >
                            <div class="m-2">
                                <p style="text-decoration:none" class="text-secondary"><?php echo $intro2; ?></p>
                            </div>     
                        </a>
                        <?php
                    }
                }
            } catch (Exception $e) {
                
            }
        }
    }

}
?>
<?php
if (!function_exists("mini_banner_news_line_w100")) {

    function mini_banner_news_line_w100($tipo_nome, $size) {
        $Foto = "";
        $url = "";
        $sql = "SELECT anuncios.* FROM `anuncios` LEFT join tipos_anuncios on(id_tipo_anuncio=tipos_anuncios.id) where(tipos_anuncios.nome=:tipo_nome)";
        $result = DAOquery($sql, array("tipo_nome" => $tipo_nome), true, "");
        $elements = [];
        $i = 0;
        while ($i < min($size, count($result["elements"]))) {
            $index = random_int(0, count($result["elements"]) - 1);
            $element = $result["elements"][$index];
            if (!in_array($element, $elements)) {

                array_push($elements, $element);
                $i++;
            }
        }
        for ($i = 0; $i < count($elements); $i++) {
            $element = $elements[$i];
            $Foto = $element["foto"];
            $descricao = $element["descricao"];
            $foto_expandida = $element["foto_expandida"];
            $Nome = $element["nome"];
            $intro = $element["introducao"];
            try {
                if ((isset($Foto))) {
                    if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/uploads/anuncio/1024x768/" . $Foto)) {
                        ?>
                        <a class="p-2 my-2 w-100 d-flex flex-wrap " style="background-color:#eee;" href="https://tooeste.com.br/Patrocinador/<?php echo $Nome; ?>" title="<?php echo $Nome; ?>">
                            <img src="<?php echo "https://$_SERVER[HTTP_HOST]"; ?>/uploads/anuncio/1024x768/<?php echo $Foto; ?>" class="m-2 p-0 d-block w-100  proportion-5x1 rounded"   >
                        </a>
                        <?php
                    }
                }
            } catch (Exception $e) {
                
            }
        }
    }

}
?>