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
	<h1>CADASTRO DE TIPOS DE ANÚCIOS</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_noticia" action="/mvc/controller/ControllerTiposAnuncios.php" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="acao" value="">
				<div class="form-row align-items-center">
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupcodigo">codigo</label>
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
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupnome">Nome</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupnome" placeholder="Nome do Usuário" name="nome">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupAltura">Altura</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="number" class="form-control" id="inlineFormInputGroupEmail" placeholder="Altura" name="altura">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscareAltura d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<div class="input-group mb-3">
							<label class="sr-only" for="inlineFormInputGroupLargura">Largura</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupLargura" placeholder="Largura" name="largura">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarLargura d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<div class="input-group mb-3">
							<label class="sr-only" for="ocultar">ocultar</label>
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
								<spam class="form-check-label form-control" for="ocultar">
    								<input class=" " type="checkbox" id="ocultar" name="ocultar" value="true" >
									Ocultar
								</spam>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfonte d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
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
						<th>Altura</th>
						<th>Largura</th>
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
