<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php'; ?>

<div id="login-explorer-app" class="" style="background-repeat:no-repeat;background-image:url('/adm/assets/img/cms/LINK LINK.png');height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" @submit.prevent="doLogin">
				
				<h2 class="form-login-heading" style="background-color:white">Bem vindo (Explorer)</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="login" class="sr-only">Login</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="text" id="login" v-model="login" class="form-control" placeholder="Login" required autofocus>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group">
						<label for="senha" class="sr-only">Senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
						<input type="password" id="senha" v-model="senha" class="form-control" placeholder="Senha" required>
					</div>
				</div>

				<div class="form-group">
					<div class="input-group ">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Entrar" :disabled="loading">							
					</div>
				</div>
				<div class="form-group">					
					<div class="input-group ">
						<a href="/admin/esqueceu_a_senha" class="btn btn-link" style="background-color:white;">Esqueceu a senha</a>
					</div>
				</div>	
				
                <div v-if="message" :class="['alert', messageClass]" role="alert">{{ message }}</div>
			</form>
		</div> 
	</div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script>
    new Vue({
        el: '#login-explorer-app',
        data: {
            login: '',
            senha: '',
            message: '',
            messageClass: '',
            loading: false
        },
        methods: {
            async doLogin() {
                this.loading = true;
                this.message = '';
                
                const formData = new FormData();
                formData.append('login', this.login);
                formData.append('senha', this.senha);
                
                try {
                    const response = await axios.post('/server/usuarios/login', formData);
                    
                    if (response.data && response.data.token) {
                        localStorage.setItem('token', response.data.token);
                        // Redirect to Explorer
                         window.location.href = '/admin/apps/explorer';
                    } else {
                        this.message = response.data.mensagem_erro || 'Erro ao realizar login.';
                        this.messageClass = 'alert-danger';
                    }
                } catch (error) {
                    this.message = 'Erro de comunicação.';
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
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php';?>