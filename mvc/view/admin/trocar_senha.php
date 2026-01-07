<?php
// Removed verify() php call, now client side check.
require($_SERVER['DOCUMENT_ROOT'] . '/library/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/top.php'); 
?>
<div id="app-trocar-senha">
    <div id="loader" v-if="loading" style="display:block">
        <div class="loader"></div>
    </div>
    <br>
    <div class="container" v-if="!loading">
        <h1>TROCAR SENHA DE {{ userName.toUpperCase() }}</h1>
        <br>
        <br>
        <div class="row">
            <div class="col-sm-12"> <!-- Fixed typo sm-12 to col-sm-12 -->
                <form class="cadastro" @submit.prevent="doChangePassword">
                    <!-- ID is managed by Token, no hidden input needed -->
                    
                    <div class="form-row align-items-center">
                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupsenhaatual">Senha Atual</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGroupsenhaatual"
                                placeholder="Senha Atual" v-model="senhaAtual" required>
                        </div>

                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGroupnovasenha">Nova Senha</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGroupnovasenha"
                                placeholder="Nova Senha" v-model="novaSenha" required>
                        </div>

                        <div class="input-group mb-3">
                            <label class="sr-only" for="inlineFormInputGrouprepetirnovasenha">Repetir Nova Senha</label>
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="password" class="form-control" id="inlineFormInputGrouprepetirnovasenha"
                                placeholder="Repetir Nova Senha" v-model="repetirNovaSenha" required>
                        </div>

                        <button type="submit" class="btn btn-primary acao"><i class="fa fa-key" aria-hidden="true"></i> Trocar Senha</button>
                    </div>
                </form>
                <br>
                <div v-if="message" :class="['alert', messageClass]" role="alert">{{ message }}</div>
                <br>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<!-- Axios included by default.php/foot.php but we are inside Body before foot ? No, top.php -> default -> foot. 
     Wait, top.php calls top(). foot.php calls foot().
     trocar_senha.php calls top.php at top.
     And foot.php at bottom.
     So scripts are at bottom. We need to put our script AFTER foot.php include or rely on window.onload?
     I'll put it after foot.php include.
-->
<?php include($_SERVER['DOCUMENT_ROOT'] . '/mvc/view/admin/templates/foot.php'); ?>

<script>
    new Vue({
        el: '#app-trocar-senha',
        data: {
            senhaAtual: '',
            novaSenha: '',
            repetirNovaSenha: '',
            message: '',
            messageClass: '',
            loading: false,
            userName: ''
        },
        mounted() {
             const token = localStorage.getItem('token');
             if (!token) {
                 window.location.href = '/admin/login';
                 return;
             }
             // Decode for name
             try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                const payload = JSON.parse(jsonPayload);
                this.userName = payload.nome || 'Usuário';
            } catch (e) {
                this.userName = 'Usuário';
            }
        },
        methods: {
            async doChangePassword() {
                this.loading = true;
                this.message = '';
                
                const formData = new FormData();
                formData.append('senha_atual', this.senhaAtual);
                formData.append('nova_senha', this.novaSenha);
                formData.append('repetir_nova_senha', this.repetirNovaSenha);
                
                try {
                    // Using the new Controller route which gets ID from Token
                    const response = await axios.post('/server/usuarios/trocarSenha', formData);
                    
                    if (response.data && response.data.mensagem_sucesso) {
                         this.message = response.data.mensagem_sucesso;
                         this.messageClass = 'alert-success';
                         // Clear fields
                         this.senhaAtual = '';
                         this.novaSenha = '';
                         this.repetirNovaSenha = '';
                    } else {
                        this.message = response.data.mensagem_erro || 'Erro ao trocar senha.';
                        this.messageClass = 'alert-danger';
                    }
                } catch (error) {
                    console.error(error);
                    this.message = 'Erro na requisição.';
                    if (error.response && error.response.data && error.response.data.mensagem_erro) {
                        this.message = error.response.data.mensagem_erro;
                    }
                     this.messageClass = 'alert-danger';
                } finally {
                    this.loading = false;
                }
            }
        }
    });
</script>