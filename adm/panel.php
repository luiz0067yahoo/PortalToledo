<?php include 'mvc/view/top.php'?>
<?php include './verifica.php'?>
<?php include './functions.php'?>
<?php  createAuthenticityToken();?>
        <img class="w2desktop" src="/adm/assets/img/wallpaper/azul.jpg">
        <div class="w2desktop">
            <div id="menu" class="menu-container w2ui-popup">
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/system-file-manager.png">
                    <a href="/admin/apps/explorer" onclick="return false;" width="500" height="500">Explorer</a>
                </div>
				<?php
				{
				?>
				<div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_tipos_anuncios.php" onclick="return false;" >Tipo Anúncio</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_anuncios.php" onclick="return false;" >Anúncio</a>
                </div>
				
				
				
				<div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_menus.php" onclick="return false;" >menu</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_album_fotos.php" onclick="return false;" >Albúm Fotos</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_fotos.php" onclick="return false;" >Fotos</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_album_videos.php" onclick="return false;" >Albúm Vídeos</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_videos.php" onclick="return false;" >Vídeos</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_noticias.php" onclick="return false;" >Notícias</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_noticias_fotos.php" onclick="return false;" >Notícias Fotos</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_noticias_anexo.php" onclick="return false;" >Notícias anexo</a>
                </div>
                
                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png">
                    <a href="/adm/mvc/view/cadastro_usuarios.php" onclick="return false;" >Usuários</a>
                </div>
                
				<?php }?>

                <div class="menu-item" onclick="w2desktop.popup(this)">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-switch-user.png">
                    <a href="/adm/mvc/view/trocar_senha.php" onclick="return false;" width="800" height="600">Trocar Senha</a>
                </div>                
                <div class="menu-item">
                    <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-shutdown.png">
                    <a href="/adm/">Sair</a>
                </div>                
            </div>
            <div class="taskbar">
                <div class="start-menu" onclick="w2desktop.startMenuClick(this)">
                    <a style="color: #4285F4">L</a>
                    <a style="color: #34A853">I</a>
                    <a style="color: #FBBC05">N</a>
                    <a style="color: #EA4335">K</a>
                </div>
                <div class="tasks">
                    
                </div>
                <div class="clock"></div>
                <div class="session"></div>
                <div class="user">&nbsp;&nbsp;<?php echo $_SESSION["usuario"];?>&nbsp;&nbsp;</div>
                <div class="user"><img src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/places/user-identity.png"></div>
            <div>
        </div>
<?php include 'mvc/view/foot.php'?>