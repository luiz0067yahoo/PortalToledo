<?php 

	require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	verify();


?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php');?>
<div id="loader">
	<div class="loader"></div>
</div>
<br>
<div class="container">
	<h1>COLETAS DE IMPRESSÃO</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_IMPRESSORAS" action="/mvc/controller/ControllerColetas.php" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="acao" value="">
				
				<div class="form-row align-items-center">
						<label  for="inlineFormInputGroupcodigo">codigo</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text  h-100">
									<i class="fa fa-key" aria-hidden="true"></i>
								</div>
							</div>
							<input type="number" disabled data-bind="value:replyNumber" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarcodigo d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupip">IP</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupip" placeholder="IP" name="ip">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupserial">Serial</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupserial" placeholder="Serial" name="serial">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
							
							
						<label  for="inlineFormInputGrouppaginas">Paginas</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGrouppaginas" placeholder="Paginas" name="paginas">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
										
						<label  for="inlineFormInputGroupdata_coleta">Data Coleta</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupdata_coleta" placeholder="Data Coleta" name="data_coleta">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
										
						<label  for="inlineFormInputGrouphora_coleta">Hora Coleta</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGrouphora_coleta" placeholder="Hora Coleta" name="hora_coleta">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
							
						
						
					
						
						
						
								<button name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
								
								<button name="buscar" type="button" class="btn btn-primary buscar d-none"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
							
															
								
								
								<button name="cancelar" type="button" class="btn btn-danger cancelar d-none"><i class="fa fa-ban " aria-hidden="true"></i> Cancelar</button>
								
																
						
				</div>
			</form>
			<br>
		
			<br>
				<table class="table table-striped resultado_busca">
				<thead>
					<tr>
						<th>Código</th>
						<th>IP</th>
						<th>Serial</th>
						<th>Páginas</th>
						<th>Data Coleta</th>
						<th>Hora Coleta</th>
						<th>Ação</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<br>
			<div class="alert alert-success mensagem_sucesso d-none" role="alert"></div>
			<div class="alert alert-danger mensagem_erro d-none" role="alert"></div>
			<div class="alert alert-info mensagem_informacao d-none" role="alert"></div>
		</div>
	</div>
	<div class="row justify-content-md-center">
		<div class="col-sm-6 ">
			<div class="input-group mb-3 paginator d-none">
				<input type="hidden" name="findParams" value="">
				<input type="hidden" name="numero_registros">
				<div class="input-group-prepend">
					
						<button type="button" class="rounded-pill btn btn-dark first  h-100">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-skip-backward-fill" viewBox="0 0 16 16">
							  <path d="M.5 3.5A.5.5 0 0 0 0 4v8a.5.5 0 0 0 1 0V8.753l6.267 3.636c.54.313 1.233-.066 1.233-.697v-2.94l6.267 3.636c.54.314 1.233-.065 1.233-.696V4.308c0-.63-.693-1.01-1.233-.696L8.5 7.248v-2.94c0-.63-.692-1.01-1.233-.696L1 7.248V4a.5.5 0 0 0-.5-.5z"/>
							</svg>
						</button>      
						
						<button  type="button" class="rounded-pill btn btn-dark prior  h-100">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-skip-start-fill" viewBox="0 0 16 16">
							  <path d="M4 4a.5.5 0 0 1 1 0v3.248l6.267-3.636c.54-.313 1.232.066 1.232.696v7.384c0 .63-.692 1.01-1.232.697L5 8.753V12a.5.5 0 0 1-1 0V4z"/>
							</svg>
						</button> 
					
				</div>
					
				<input type="text" class="form-control mx-1" id="offset" placeholder="Página" name="offset" value="1">
				<button type="button" class="rounded btn btn-dark offset" style="height:40px ">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lightning-charge-fill" viewBox="0 0 16 16">
					  <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"/>
					</svg>
				</button> 
				<select class="mx-1 "  style="height:40px" name="row_count">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select >
					
				<div class="input-group-append">
					
				
					<button type="button" class=" rounded-pill btn btn-dark next  h-100">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-skip-end-fill" viewBox="0 0 16 16">
							<path d="M12.5 4a.5.5 0 0 0-1 0v3.248L5.233 3.612C4.693 3.3 4 3.678 4 4.308v7.384c0 .63.692 1.01 1.233.697L11.5 8.753V12a.5.5 0 0 0 1 0V4z"/>
						</svg>
					</button>      
					<button  type="button" class="rounded-pill btn btn-dark last  h-100">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-skip-forward-fill" viewBox="0 0 16 16">
						  <path d="M15.5 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V8.753l-6.267 3.636c-.54.313-1.233-.066-1.233-.697v-2.94l-6.267 3.636C.693 12.703 0 12.324 0 11.693V4.308c0-.63.693-1.01 1.233-.696L7.5 7.248v-2.94c0-.63.693-1.01 1.233-.696L15 7.248V4a.5.5 0 0 1 .5-.5z"/>
						</svg>
					</button>      
				</div>
			</div>
		</div>
	</div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>
<script type="text/javascript">
$(".buscar").click();
</script>
