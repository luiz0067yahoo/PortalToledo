<?php
// Removed verify() and sessionCount() as they are server-side session/token checks inappropriate for initial view load in SPA-like architecture.
require_once $_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/top.php';
?>
    <style>
        body {
            overflow: hidden;
            background-color: #f8f9fa;
        }

        #sidebar {
            min-width: 280px;
            max-width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        /* Desktop: Default visible. Active class hides it. */
        #sidebar.active {
            margin-left: -280px;
        }

        .sidebar-header {
            background: #FFF;
            text-align: center;
            border-bottom: 1px solid #495057;
        }

        .sidebar-header img {
            max-width: 260px;
            margin-left:auto;
            margin-right:auto;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: #cfd8dc;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background: #495057;
            color: #fff;
            border-left-color: #0d6efd;
        }

        .menu-item i {
            width: 30px;
            font-size: 1.2rem;
            text-align: center;
        }

        .menu-item img.icon_32 {
            width: 32px;
            height: 32px;
            margin-right: 12px;
        }

        .menu-item a {
            color: inherit;
            text-decoration: none;
            display: block;
            width: 100%;
            cursor: pointer;
        }

        #content {
            transition: all 0.3s;
            margin-left: 280px;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #content.expanded {
            margin-left: 0;
        }

        #main-frame {
            flex: 1;
            border: none;
            width: 100%;
        }

        .topbar {
            background: #fff;
            padding: 12px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 900;
        }

        .toggle-btn {
            font-size: 1.5rem;
            cursor: pointer;
            color: #495057;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .clock {
            font-weight: bold;
            color: #495057;
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -280px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                margin-left: 0;
            }
        }
    </style>

    <div id="app">
        <!-- Sidebar -->
        <div id="sidebar" class="shadow-lg" :class="{ active: isToggled }">
            <div class="sidebar-header">
                <div class="close-sidebar d-flex justify-content-end" @click="closeSidebar">
                    <i class="fa fa-times close-sidebar text-secondary" style="cursor:pointer; padding: 10px;"></i>
                </div>
                <img src="https://<?php echo $_SERVER['HTTP_HOST']; ?>/assets/img/cms/inprolink_cms_system.png" alt="Logo">
            </div>

            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/tiposAnuncios" target="main">Tipo Anúncio</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/anuncios" target="main">Anúncio</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/menus" target="main">Menu</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/albumFotos" target="main">Álbum Fotos</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/fotos" target="main">Fotos</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/albumVideos" target="main">Álbum Vídeos</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/videos" target="main">Vídeos</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/noticias" target="main">Notícias</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/config" target="main">Configurações</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png" alt="">
                <a href="/admin/usuarios" target="main">Usuários</a>
            </div>

            <hr style="border-color: #495057; margin: 15px 20px;">

            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-switch-user.png" alt="">
                <a href="/admin/trocar_senha" target="main">Trocar Senha</a>
            </div>
            <div class="menu-item">
                <img class="icon_32" src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/actions/system-shutdown.png" alt="">
                <a href="#" @click.prevent="logout">Sair</a>
            </div>
        </div>

        <!-- Conteúdo Principal -->
        <div id="content" :class="{ expanded: isToggled }">
            <div class="topbar">
                <div class="toggle-btn" id="sidebarToggle" @click="toggleSidebar" v-show="showHamburger">
                    <i class="fas fa-bars"></i>
                </div>
                <!-- Placeholder if hamburger hidden to keep alignment? -->
                <div v-show="!showHamburger" style="width: 24px;"></div>

                <div class="user-info">
                    <img src="https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/places/user-identity.png" width="32" alt="Usuário">
                    <span class="text-muted">{{ userName }}</span>
                    <div id="cronometro" class="clock session">{{ currentTime }}</div>
                </div>
            </div>

            <iframe id="main" name="main" src="/admin/noticias" frameborder="0" style="height:100%"></iframe>
        </div>
    </div>

    <!-- Vue and Axios are already included via default.php (foot) but we need Vue available BEFORE foot if possible or rely on DOMContentLoaded? 
         Wait, default.php uses foot() which is typically called AT THE END.
         My previous view of default.php showed foot() definitions.
         The panel.php includes foot.php at the END.
         So scripts are available after this block.
         BUT I am writing the Vue script block AFTER the include foot.php in keeping with structure?
         No, panel.php Line 305 was `include ... foot.php`.
         So I should put my script AFTER foot.php include, OR rely on foot.php including the libs.
    -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/foot.php' ?>
    
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                isToggled: false, 
                windowWidth: window.innerWidth,
                currentTime: '00:00',
                totalSeconds: 0,
                timerInterval: null,
                userName: 'Admin'
            },
            computed: {
                isMobile() {
                    return this.windowWidth <= 768;
                },
                isSidebarVisible() {
                    if (this.isMobile) {
                        return this.isToggled;
                    } else {
                        return !this.isToggled;
                    }
                },
                showHamburger() {
                    return !this.isSidebarVisible;
                }
            },
            mounted() {
                window.addEventListener('resize', this.onResize);
                // Listen for Token Refresh events from jwt-auth.js
                window.addEventListener('token-refreshed', this.onTokenRefreshed);
                
                this.checkAuth();
            },
            beforeDestroy() {
                window.removeEventListener('resize', this.onResize);
                window.removeEventListener('token-refreshed', this.onTokenRefreshed);
            },
            methods: {
                checkAuth() {
                     const token = localStorage.getItem('token');
                     if (!token) {
                         window.location.href = '/admin/login';
                         return;
                     }
                     this.parseToken(token);
                },
                parseToken(token) {
                    try {
                       const base64Url = token.split('.')[1];
                       const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                       const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                           return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                       }).join(''));
                       const payload = JSON.parse(jsonPayload);
                       
                       if (payload.nome) this.userName = payload.nome;
                       if (payload.exp) {
                           this.startTimer(payload.exp);
                       }
                   } catch (e) {
                       console.error('Invalid token', e);
                       this.logout();
                   }
                },
                onTokenRefreshed(e) {
                    const newToken = e.detail;
                    console.log("Panel updated with new token info");
                    this.parseToken(newToken);
                },
                startTimer(expTimestamp) {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    
                    const now = Math.floor(Date.now() / 1000);
                    this.totalSeconds = expTimestamp - now;
                    
                    if (this.totalSeconds <= 0) {
                        this.logout();
                        return;
                    }
                    
                    this.updateDisplayTime();
                    this.timerInterval = setInterval(() => {
                        this.totalSeconds--;
                        if (this.totalSeconds <= 0) {
                            clearInterval(this.timerInterval);
                            this.logout();
                        } else {
                            this.updateDisplayTime();
                        }
                    }, 1000);
                },
                updateDisplayTime() {
                    let m = Math.floor(this.totalSeconds / 60).toString().padStart(2, '0');
                    let s = (this.totalSeconds % 60).toString().padStart(2, '0');
                    this.currentTime = `${m}:${s}`;
                },
                onResize() {
                    this.windowWidth = window.innerWidth;
                },
                toggleSidebar() {
                    this.isToggled = !this.isToggled;
                },
                closeSidebar() {
                    if (this.isMobile) {
                        this.isToggled = false;
                    } else {
                        this.isToggled = true;
                    }
                },
                logout() {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                }
            }
        });
    </script>