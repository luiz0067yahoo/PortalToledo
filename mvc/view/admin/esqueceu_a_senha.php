<?php
// Removed direct DAO access and session deps. Now Vue App.
require_once $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php';
?>
<div id="recovery-app" style="background-repeat:no-repeat;height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" @submit.prevent="doRecovery">
				
				<h2 class="form-login-heading" style="background-color:white">Esqueceu a senha</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="e_mail" class="sr-only">E-mail</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="text" id="e_mail" v-model="email" class="form-control" placeholder="E-mail" required autofocus>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group ">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Recuperar a senha" :disabled="loading">
					</div>
				</div>
				<div class="form-group">					
					<div class="input-group ">
						<a href="/admin/login" class="btn btn-link" style="background-color:white;">< voltar para login</a>
					</div>
				</div>	
				
                <div v-if="message" :class="['alert', 'text-center', messageClass]" role="alert" v-html="message"></div>
			</form>
		</div> 
	</div> 
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script>
    new Vue({
        el: '#recovery-app',
        data: {
            email: '',
            message: '',
            messageClass: '',
            loading: false
        },
        methods: {
            async doRecovery() {
                this.loading = true;
                this.message = '';
                
                const formData = new FormData();
                formData.append('e_mail', this.email);
                
                try {
                    const response = await axios.post('/server/usuarios/recovery', formData);
                    
                    if (response.data && response.data.mensagem_successo) {
                        this.message = response.data.mensagem_successo;
                        this.messageClass = 'alert-success';
                    } else if (response.data && response.data.mensagem_erro) {
                        this.message = response.data.mensagem_erro;
                         this.messageClass = 'alert-danger';
                    } else {
                         this.message = "Erro ao enviar email.";
                         this.messageClass = 'alert-danger';
                    }
                } catch (error) {
                    console.error(error);
                    this.message = "Erro de comunicação.";
                    this.messageClass = 'alert-danger';
                } finally {
                    this.loading = false;
                }
            }
        }
    });
</script>
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'?>