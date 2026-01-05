<?php 
	require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	verify();
	include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');
?>
<div id="loader">
	<div class="loader"></div>
</div>
<br>
<div class="container">
	<h1>CADASTRO DE USUÁRIOS</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro"  action="/mvc/model/usuariosDAO.php" method="POST">
				<input type="hidden" name="acao" value="">
				<div class="form-row align-items-center">
						<label for="inlineFormInputGroupcodigo">codigo</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-key" aria-hidden="true"></i>
								</div>
							</div>
							<input type="number" disabled data-bind="value:replyNumber" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarcodigo d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label for="inlineFormInputGroupnome">Nome</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupnome" placeholder="Nome do Usuário" name="nome">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label for="inlineFormInputGroupE_mail">E-mail</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="email" class="form-control" id="inlineFormInputGroupEmail" placeholder="E-mail" name="e_mail">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscare_mail d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label for="inlineFormInputGrouplogin">Login</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGrouplogin" placeholder="Login do Usuário" name="login">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarlogin d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label for="inlineFormInputGroupsenha">Senha</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="password" class="form-control" id="inlineFormInputGroupsenha" placeholder="Senha" name="senha">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarsubsenha d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						

						
						
							
								<button name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
								
								<button name="buscar" type="button" class="btn btn-primary buscar d-none"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
							
								<button name="salvar" type="button" class="btn btn-success salvar "><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>    
							
								<button name="editar" type="button" class="btn btn-primary editar d-none"><i class="fa fa-edit " aria-hidden="true"></i> Editar</button>
								
								<button name="excluir" type="button" class="btn btn-danger excluir d-none"><i class="fa fa-times " aria-hidden="true"></i> Excluir</button>
								
								<button name="cancelar" type="button" class="btn btn-danger cancelar d-none"><i class="fa fa-ban " aria-hidden="true"></i> Cancelar</button>
								
						
				</div>
			</form>
			<br>
			<div class="alert alert-success mensagem_sucesso d-none" role="alert"></div>
			<div class="alert alert-danger mensagem_erro d-none" role="alert"></div>
			<div class="alert alert-info mensagem_informacao d-none" role="alert"></div>
			<br>
				<table class="table table-striped resultado_busca">
				<thead>
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>