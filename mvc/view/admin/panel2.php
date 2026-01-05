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
                <a href="/admin/usuarios" onclick="return false;">Usuários</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/menus" onclick="return false;">Menu</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/noticias" onclick="return false;">Notícias</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/anuncios" onclick="return false;">Anúncios</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/albumFotos" onclick="return false;">Albúm Fotos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/fotos" onclick="return false;">Fotos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/albumVideos" onclick="return false;">Albúm Vídeos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/videos" onclick="return false;">Vídeos</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/tiposAnuncios" onclick="return false;">Tipos Anúncios</a>
            </div>

            <div class="menu-item" onclick="w2desktop.popup(this)">
                <img class="icon_32"
                    src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                <a href="/admin/Anuncios" onclick="return false;">Anúncios</a>
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