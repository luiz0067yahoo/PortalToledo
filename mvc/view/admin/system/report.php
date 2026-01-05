<?php 

	require ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	verify();


?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/templates/admin/top.php'	);?>
<link rel="stylesheet" type="text/css" href="https://www.shieldui.com/shared/components/latest/css/light/all.min.css" />
<div id="loader">
	<div class="loader"></div>
</div>
<br>
<div class="container">
	<div class="row">
		<div class="sm-12">
			<div class="input-group mb-3 float-end d-print-none">
					<div class="input-group-append" >								
						<button id="showHide" type="button" style=";"class="btn btn-sm   rounded-circle  h-100" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-minus" aria-hidden="true"></i></button	>
					</div>
			</div>
			<form id="collapseExample" action="/mvc/controller/ControllerReports.php?report=reportColetasSecretariaDate" method="POST" class="form-ajax needs-validation border border-secondary rounded p-3 my-2 d-print-none"  novalidate>
				<div class="form-row align-items-center" >
						
						<label for="inlineFormInputGroupdata_inicio">Data início:</label>
						<div class="input-group mb-3 ">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="date"  class="form-control input-append" id="inlineFormInputGroupdata_inicio" placeholder="Data início yyyy-mm-dd" name="params[data_inicio]"  required >
							<div class="invalid-feedback">
								Por favor ecolha a data Início.
							 </div>
						</div>
						<label for="inlineFormInputGroupdata_fim">Data fim:</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="date"  class="form-control" id="inlineFormInputGroupdata_fim" placeholder="Data fim yyyy-mm-dd" name="params[data_fim]"  required >
							<div class="invalid-feedback">
								Por favor ecolha a data Início.
							 </div>
						</div>
						
						<label  for="inlineFormInputGroupsecretaria">Secretaria</label>
						<div class="input-group  mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text h-100">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							
							<select class="form-control form-select bg-white " id="inlineFormInputGroupsecretaria" placeholder="Secretaria" name="params[secretaria]" style="color(var(--bs-gray))">
								<option value="TODAS">TODAS</option>
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
							
						</div>
						
						
					
						
						
						
								
						<button id="buscar" type="submit" class="btn btn-primary "><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
						
							  
								
						
				</div>
			</form>
				<a id="excel" class="d-none d-print-none"><button  type="button" class="btn btn-success ">
				
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-excel-fill" viewBox="0 0 16 16">
					  <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64z"/>
					</svg>
					Planilha
				</button></a>
				<a id="PDF" class=" d-none d-print-none"><button  type="button" class="btn btn-danger">
				
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-pdf-fill" viewBox="0 0 16 16">
					  <path d="M5.523 12.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.148 21.148 0 0 0 .5-1.05 12.045 12.045 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.881 3.881 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 6.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/>
					  <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.651 11.651 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.697 19.697 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/>
					</svg>
					PDF
				</button></a>
				<table class="table table-striped result">
					<caption style="caption-side:top"><h1>Relatório de páginas impressas por secretaria entre datas</h1></caption>
				</table>
				
			<br>
		</div>
	</div>
	<label class='erro alert alert-danger d-none'></label>	
</div>

<?php include($_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php');?>

<script type="text/javascript" >

$('#showHide').click(function(event	){
	if($(this).find(".fa-minus").length >0 ){
		$(this).find(".fa").removeClass("fa-minus");
		$(this).find(".fa").addClass("fa-plus" );
		$($(this).attr("data-target")).addClass("collapse");
	}
	else{
		$(this).find(".fa").addClass("fa-minus");
		$(this).find(".fa").removeClass("fa-plus" );
		$($(this).attr("data-target")).removeClass("collapse");
	}
});

 
</script>
