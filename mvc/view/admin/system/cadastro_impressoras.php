<?php 
	require ($_SERVER['DOCUMENT_ROOT'].'/printer/library/functions.php');
	verify();
	include($_SERVER['DOCUMENT_ROOT'].'/printer/mvc/view/templates/top.php');
?>
<div id="loader">
	<div class="loader"></div>
</div>
<br>
<div class="container">
	<h1>CADASTRO DE IMPRESSORAS</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_IMPRESSORAS" action="/mvc/controller/ControllerImpressoras.php" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="acao" value="">
				
				<div class="form-row align-items-center">
						<label  for="inlineFormInputGroupcodigo">Código</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text  h-100">
									<i class="fa fa-key" aria-hidden="true"></i>
								</div>
							</div>
							<input type="number" disabled data-bind="value:replyNumber" class="form-control" id="inlineFormInputGroupcodigo" placeholder="Código" name="id" style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarcodigo d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupnome">Nome</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupnome" placeholder="Nome" name="nome" style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none  h-100 "><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
							
						<label  for="inlineFormInputGroupsecretaria">Secretaria</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							
							<select class="form-control form-select bg-white " id="inlineFormInputGroupsecretaria" placeholder="Secretaria" name="secretaria" style="color(var(--bs-gray))">
								<option value="" selected disabled >Secretaria</option>
								<option value="GABINETE DO PREFEITO">GABINETE DO PREFEITO </option>
								<option value="GABINETE DO VICE-PREFEITO">GABINETE DO VICE-PREFEITO </option>
								<option value="SECRETARIA DE ADMINISTRAÇÃO">SECRETARIA DE ADMINISTRAÇÃO </option>
								<option value="SECRETARIA DE AGRICULTURA, PECUÁRIA E ABASTECIMENTO">SECRETARIA DE AGRICULTURA, PECUÁRIA E ABASTECIMENTO </option>
								<option value="SECRETARIA DE ASSISTÊNCIA SOCIAL E PROTEÇÃO À FAMÍLIA">SECRETARIA DE ASSISTÊNCIA SOCIAL E PROTEÇÃO À FAMÍLIA </option>
								<option value="SECRETARIA DE POLÍTICAS PARA MULHERES">SECRETARIA DE POLÍTICAS PARA MULHERES </option>
								<option value="SECRETARIA DE COMUNICAÇÃO">SECRETARIA DE COMUNICAÇÃO </option>
								<option value="SECRETARIA DA CULTURA">SECRETARIA DA CULTURA</option>
								<option value="SECRETARIA DA EDUCAÇÃO">SECRETARIA DA EDUCAÇÃO </option>
								<option value="SECRETARIA DE ESPORTES E LAZER">SECRETARIA DE ESPORTES E LAZER </option>
								<option value="SECRETARIA DA FAZENDA">SECRETARIA DA FAZENDA </option>
								<option value="SECRETARIA DO DESENVOLVIMENTO ECONÔMICO E TECNOLÓGICO, DE INOVAÇÃO E TURISMO">SECRETARIA DO DESENVOLVIMENTO ECONÔMICO E TECNOLÓGICO, DE INOVAÇÃO E TURISMO </option>
								<option value="SECRETARIA DE INFRA-ESTRUTURA RURAL">SECRETARIA DE INFRA-ESTRUTURA RURAL </option>
								<option value="SECRETARIA DA JUVENTUDE">SECRETARIA DA JUVENTUDE </option>
								<option value="SECRETARIA DO DESENVOLVIMENTO AMBIENTAL E SANEAMENTO">SECRETARIA DO DESENVOLVIMENTO AMBIENTAL E SANEAMENTO </option>
								<option value="SECRETARIA DA HABITAÇÃO, SERVIÇOS E OBRAS PÚBLICAS">SECRETARIA DA HABITAÇÃO, SERVIÇOS E OBRAS PÚBLICAS </option>
								<option value="SECRETARIA DO PLANEJAMENTO ESTRATÉGICO">SECRETARIA DO PLANEJAMENTO ESTRATÉGICO </option>
								<option value="SECRETARIA DE RECURSOS HUMANOS">SECRETARIA DE RECURSOS HUMANOS </option>
								<option value="SECRETARIA DA SAÚDE">SECRETARIA DA SAÚDE </option>
								<option value="SECRETARIA DE SEGURANÇA E TRÂNSITO">SECRETARIA DE SEGURANÇA E TRÂNSITO </option>
								<option value="CONTROLE INTERNO">CONTROLE INTERNO </option>
								<option value="OUVIDORIA GERAL">OUVIDORIA GERAL </option>
								<option value="ASSESSORIA JURÍDICA">ASSESSORIA JURÍDICA </option>
							</select>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarsecretaria d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						
						<label  for="inlineFormInputGrouplocal">Local</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGrouplocal" placeholder="Local" name="local" style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarlocal d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupsala">Sala</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupsala" placeholder="Sala" name="sala" style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarsala d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						
						<label  for="inlineFormInputGroupmarca">Marca</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<select class=" form-select"  id="inlineFormInputGroumarca" placeholder="Marca" name="marca">
								<option value="" selected disabled >Marca</option>
								<option value="BROTHER">BROTHER</option>
								<option value="CANON">CANON</option>
								<option value="EPSON">EPSON</option>
								<option value="KYOCERA">KYOCERA</option>
								<option value="LEXMARK">LEXMARK </option>
								<option value="HP">HP</option>
								<option value="OKI">OKI</option>
								<option value="RICOH">RICOH</option>
								<option value="SAMSUNG">SAMSUNG</option>								
								<option value="SHARP">SHARP</option>								
							</select>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarmarca d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
					
						<label  for="inlineFormInputGroupmodelo">Modelo</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupmodelo" placeholder="Modelo" name="modelo" style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarmodelo d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
					
						
												
					
						<label  for="inlineFormInputGroupip">IP</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupip" placeholder="IP" name="ip" required style=" text-transform: uppercase;">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarip d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>

						<label  for="inlineFormInputGrouptipo">Tipo</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<select class=" form-select" id="inlineFormInputGrouptipo" placeholder="Tipo" name="tipo">
								<option value="" selected disabled >Tipo</option>
								<option value="COLORIDA">COLORIDA</option>
								<option value="MONOCROMÁTICA">MONOCROMÁTICA</option>								
							</select>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscartipo d-none  h-100"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
					
						
						
						
								<button name="novo" type="button" class="btn btn-dark novo"><i class="fa fa-sticky-note" aria-hidden="true"></i> Novo</button>      
								
								<button name="buscar" type="button" class="btn btn-primary buscar d-none"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
							
								<button name="salvar" type="button" class="btn btn-success salvar"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill" viewBox="0 0 16 16">
								  <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2z"/>
								</svg> Salvar</button>    
															
								<button name="editar" type="button" class="btn btn-primary editar d-none"><i class="fa fa-edit " aria-hidden="true"></i> Editar</button>
								
								<button name="excluir" type="button" class="btn btn-danger excluir d-none"><i class="fa fa-times " aria-hidden="true"></i> Excluir</button>
								
								<button name="cancelar" type="button" class="btn btn-danger cancelar d-none"><i class="fa fa-ban " aria-hidden="true"></i> Cancelar</button>
								
								<button id="coleta" type="button" class="btn btn-dark "><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-diagram-3-fill" viewBox="0 0 16 16">
								  <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5v-1zm-6 8A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5v-1zm6 0A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5v-1zm6 0a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1z"/>
								</svg> Coleta</button> 
																
								
								
						
				</div>
			</form>
			<br>
		
			<br>
				<table class="table table-striped resultado_busca">
				<thead>
					<tr>
						<th>Código</th>
						<th>Secretaria</th>
						<th>Local</th>
						<th>Marca</th>
						<th>Modelo</th>
						<th>IP</th>
						<th>Serial</th>
						<th>Páginas</th>
						<th>Coleta</th>
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

<?php include($_SERVER['DOCUMENT_ROOT'].'/printer/mvc/view/templates/foot.php');?>
<script type="text/javascript" >
$(".buscar").click();
$("#coleta").click(function(event) {
    try{
	    for (var i in CKEDITOR.instances) {
            var editor_html=CKEDITOR.instances[i]
            $("[name="+editor_html.name+"]").val(editor_html.getData());
        }
	}
	catch(eeeeeee){}
	var form_atual=event.target.form;
    $(form_atual).find('.buscarcampo').addClass("d-none");
    $(form_atual).find('.novo').addClass("d-none");   
    $(form_atual).find('.buscar').addClass("d-none");    
    $(form_atual).find('.salvar').addClass("d-none");	
    $(form_atual).find('.cancelar').addClass("d-none");	
    $(form_atual).find('.excluir').addClass("d-none");	
    $(form_atual).find('.editar').addClass("d-none");		
    $(form_atual).find('.novo').removeClass("d-none"); 
    $(form_atual).find('.salvar').removeClass("d-none");
	$(form_atual).find("input[name='acao']").val("coleta");
	$("input[name='findParams']").val($(form_atual).serialize());
	$(".paginator").find("input[name=offset]").val(1);
	$.ajax(
		{
			url: $(form_atual).attr("action"),
			dataType: 'html',
            method: $(form_atual).attr("method"),			
			data:$(form_atual).serialize(),
			beforeSend: function() {
                $('#loader').show();
            },
            complete: function() {
                $('#loader').hide();
            },
            success: function(data, textStatus) {
                processa_resposta_servidor(data);
				$(".paginator").removeClass("d-none");
            },
            error: function(xhr,er) {
                //erro
            }
		}
	);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio, :hidden').val('');
    $(form_atual).find(':checkbox, :radio').prop('checked', false);
	$(form_atual).find(':input').not(':button, :submit, :reset, :checkbox, :radio').prop( "disabled", false );
    $(form_atual).find(':checkbox, :radio').prop( "disabled", false )	;
});

</script>
