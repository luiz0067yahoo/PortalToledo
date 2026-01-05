<?php
$base_url_ = "https://$_SERVER[HTTP_HOST]";
require($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
verify();
include $_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/top.php';
?>
<img class="w2desktop" src="<?php echo $base_url_; ?>/assets/img/cms/inprolink_cms_system.png">
<div class="w2desktop">


    <div class="taskbar">
        <div class="start-menu" onclick="w2desktop.startMenuClick(this)">
            <a style="color: #FFFFFF">M</a>
            <a style="color: #FFFFFF">E</a>
            <a style="color: #FFFFFF">N</a>
            <a style="color: #FFFFFF">U</a>

        </div>
        <div class="tasks">

        </div>
        <div class="clock d-none"></div>
        <div class="session"></div>
        <div class="user">&nbsp;&nbsp;<?php //echo userActiveName(); ?>&nbsp;&nbsp;</div>
        <div class="user"><img
                src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/places/user-identity.png"></div>
    </div>
    <div id="menu" class="menu-container w2ui-popup">

        <?php {
            ?>
            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_tipos_anuncios.php" onclick="return false;">Tipo Anúncio</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_anuncios.php" onclick="return false;">Anúncio</a>
            </div>



            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_menus.php" onclick="return false;">menu</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_album_fotos.php" onclick="return false;">Albúm Fotos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_fotos.php" onclick="return false;">Fotos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_album_videos.php" onclick="return false;">Albúm Vídeos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_videos.php" onclick="return false;">Vídeos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_noticias.php" onclick="return false;">Notícias</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_config.php" onclick="return false;">Configurações</a>
            </div>


            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/adm/mvc/view/cadastro_usuarios.php" onclick="return false;">Usuários</a>
            </div>

        <?php } ?>

        <div class="menu-item" onclick="w2desktop.popup(this)">
            <img class="icon_32"
                src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-switch-user.png">
            <a href="/mvc/view/admin/trocar_senha.php" onclick="return false;" width="800" height="600">Trocar Senha</a>
        </div>
        <div class="menu-item">
            <img class="icon_32"
                src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-shutdown.png">
            <a href="/admin/logout">Sair</a>
        </div>
    </div>

</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/foot.php' ?>