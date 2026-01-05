<?php include('top.php');?>
<?php include '../../verifica.php'?>
<div id="loader">
	<div class="loader"></div>
</div>
<br>
<div class="container">
	<h1>TROCAR SENHA DE <?php echo strtoupper($_SESSION["usuario"]);?></h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_usuario" action="/adm/mvc/model/usuariosDAO.php">
				<input type="hidden" name="acao" value="">
				<input type="hidden" disabled data-bind="value:replyNumber" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id" value="<?php echo $_SESSION["id"];?>">
			
				<div class="form-row align-items-center">
						
						
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupsenhaatual">Senha Atual</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="password" class="form-control" id="inlineFormInputGroupsenha" placeholder="Senha Atual" name="senha_atual">
							
						</div>
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupnovasenha">Nova Senha</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="password" class="form-control" id="inlineFormInputGroupnovasenha" placeholder="Nova Senha" name="nova_senha">
							
						</div>
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGrouprepetirnovasenha">Repetir Nova Senha</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="password" class="form-control" id="inlineFormInputGrouprepetirnovasenha" placeholder="Repetir Nova Senha" name="repetir_nova_senha">
							
						</div>
							
							
								<button name="trocar_senha" type="button" class="btn btn-primary acao "><i class="fa fa-key" aria-hidden="true"></i> Trocar Senha</button>    
							
							
				</div>
			</form>
			<br>
			<div class="alert alert-success mensagem_sucesso d-none" role="alert"></div>
			<div class="alert alert-danger mensagem_erro d-none" role="alert"></div>
			<div class="alert alert-info mensagem_informacao d-none" role="alert"></div>
			<br>
				
		</div>
	</div>
</div>
<?php include('foot.php');?>
