<?php include('top.php');?>
<?php include '../../verifica.php'?>
<div id="loader">
	<div class="loader"></div>
</div>

<br>
<div class="container">
	<h1>CADASTRO DE configurações</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_menu" action="/adm/mvc/controller/ControllerConfig.php" method="POST">
				<input type="hidden" name="acao" value="">
				<div class="form-row align-items-center">
					<label  for="inlineFormInputGroupcodigo">Código</label>
					<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-key" aria-hidden="true"></i>
								</div>
							</div>
							<input type="number" disabled data-bind="value:replyNumber" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarcodigo d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
					
						
						<label  for="inlineFormInputGrouplogo">Logo</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGroupLogo" placeholder="Foto do Logo" name="logo" type="file"  accept="image/jpeg">
							<input type="hidden" name="logo_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768">
							<input type="hidden" name="logo_path" value="logo">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarlogo d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>

						
						<label  for="inlineFormInputGrouplogo_mobile">Logo Mobile</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGrouplogo_mobile" placeholder="Foto do Logo Mobile" name="logo_mobile" type="file"  accept="image/jpeg">
							<input type="hidden" name="logo_mobile_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768">
							<input type="hidden" name="logo_mobile_path" value="logo_mobile">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarlogo_mobile d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
					
						
						<label for="inlineFormInputGroupmensagem_contato">Mensagem Contato</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupmensagem_contato" placeholder="Mensagem Contato" name="mensagem_contato">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarmensagem_contato d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						
					
						
						<label class="sr-only" for="inlineFormInputGroupocultar">ocultar</label>
						<div class="input-group mb-3">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="ocultar" name="ocultar" value="true">
								<label class="form-check-label" for="ocultar">
									ocultar
								</label>
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
						<th>Logo</th>
						<th>Logo Mobile</th>
						<th>Nome</th>
						<th>Ocultar</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include('foot.php');?>
