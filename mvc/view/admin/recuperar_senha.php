<?php include $_SERVER['DOCUMENT_ROOT'].'/library/functions.php'?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php'?>

<div id="reset-app" style="background-repeat:no-repeat;height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" @submit.prevent="doReset">	
				<h2 class="form-login-heading" style="background-color:white">Troque sua senha</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="login" class="sr-only">Nova senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="password" id="nova_senha" v-model="nova_senha" class="form-control" placeholder="Nova senha" required :disabled="success">
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group">
						<label for="senha" class="sr-only">Repetir nova senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
						<input type="password" id="senha" v-model="repetir_nova_senha" class="form-control" placeholder="Repetir nova senha" required :disabled="success">
					</div>
				</div>

				<div class="form-group">
					<div class="input-group ">
						<button class="btn btn-lg btn-primary btn-block" type="submit" :disabled="loading || success">Trocar senha</button>							
					</div>
				</div>
                
                <div v-if="message" :class="['alert', messageClass]" role="alert" v-html="message"></div>
			</form>
		</div> 
	</div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script>
    new Vue({
        el: '#reset-app',
        data: {
            nova_senha: '',
            repetir_nova_senha: '',
            code: '<?php echo getParameter("code"); ?>',
            message: '',
            messageClass: '',
            loading: false,
            success: false
        },
        methods: {
            async doReset() {
                 this.loading = true;
                 this.message = '';
                 
                 const formData = new FormData();
                 formData.append('code', this.code);
                 formData.append('nova_senha', this.nova_senha);
                 formData.append('repetir_nova_senha', this.repetir_nova_senha);
                 
                 try {
                     const response = await axios.post('/server/usuarios/resetPassword', formData);
                     
                     if (response.data && response.data.mensagem_sucesso) {
                         this.success = true;
                         this.message = response.data.mensagem_sucesso + '<br><a href="/admin/login">Clique aqui para ir para o login</a>';
                         this.messageClass = 'alert-success';
                     } else {
                         this.message = response.data.mensagem_erro || 'Erro ao trocar senha.';
                         this.messageClass = 'alert-danger';
                     }
                 } catch (error) {
                     this.message = 'Erro na comunicação.';
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
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'?>