<?php include('top.php');?>
<?php include '../../verifica.php'?>
<div id="loader">
	<div class="loader"></div>
	<div class="progress progress-item" >
	  <div class="progress-bar" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width:0%">
	  </div>
	</div>
	<div class="progress progress-all" >
	  <div class="progress-bar" role="progressbar"  aria-valuemin="0" aria-valuemax="100" style="width:0%">
	  </div>
	</div>
</div>
<br>
<div class="container">
	<h1>CADASTRO DE ANÚNCIOS</h1>
	<br>
	<br>
	<div class="row">
		<div class="sm-12">
			<form class="cadastro" id="cadastro_anuncio" action="/adm/mvc/controller/ControllerAnuncios.php" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="acao" value="">
				<div class="form-row align-items-center">
						
						<label for="inlineFormInputGroupcodigo">codigo</label>
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
						
						
						<label for="inlineFormInputGroupId_tipo_anuncio">Tipo Anúncio</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-link" aria-hidden="true"></i>
								</div>
							</div>
							<select chave="id" valor="nome" modelo="tipos_anuncios" class="form-control selectAjax" id="inlineFormInputGroupid_tipo_anuncio" placeholder="Tipo Anúncio" name="id_tipo_anuncio">
								<option value="">Principal</option>
							<select>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarId_tipo_anuncio d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupnome">Nome</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupnome" placeholder="Nome da foto" name="nome">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarnome d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						
						
						<label  for="inlineFormInputGroupintroducao">Introdução</label>
                    	<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupintroducao" placeholder="Introdução" name="introducao">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarintroducao d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label  for="inlineFormInputGroupintroducao2">Introdução2</label>
                    	<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupintroducao2" placeholder="Introdução2" name="introducao2">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarintroducao2 d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						
						<label for="descricao">Descrição</label>
                       	<div class="input-group mb-3">
							
							<textarea type="text" class="form-control ckeditor"   id="descricao" placeholder="Descrição" name="descricao"></textarea>
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscardescricao d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
				

	                   
						

						<label  for="inlineFormInputGroupfoto">foto</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGroupfoto" placeholder="Foto do Anuncio" name="foto[]" type="file" multiple accept="image/jpeg">
							<input type="hidden" name="foto_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768,1440x900">
							<input type="hidden" name="foto_path" value="anuncio">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfoto d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>




						<label  for="inlineFormInputGroupfoto_expandida">foto expandida</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGroupfoto_expandida" placeholder="Foto do Anuncio Expandido" name="foto_expandida[]" type="file" multiple accept="image/jpeg">
							<input type="hidden" name="foto_expandida_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768,1440x900">
							<input type="hidden" name="foto_expandida_path" value="anuncio">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfoto_expandida d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>
						<br>
						<br>


						<label  for="inlineFormInputGroupfoto_mobile">foto_mobile</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGroupfoto_mobile" placeholder="foto_mobile do Anuncio" name="foto_mobile[]" type="file" multiple accept="image/jpeg">
							<input type="hidden" name="foto_mobile_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768,1440x900">
							<input type="hidden" name="foto_mobile_path" value="anuncio">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfoto_mobile d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>

						<label  for="inlineFormInputGroupfoto_mobile_expandida">foto_mobile expandida</label>
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input class="form-control" id="inlineFormInputGroupfoto_mobile_expandida" placeholder="foto_mobile do Anuncio Expandido" name="foto_mobile_expandida[]" type="file" multiple accept="image/jpeg">
							<input type="hidden" name="foto_mobile_expandida_formats" value="160x120,320x240,640x480,480x640,800x600,1024x768,1366x768,1440x900">
							<input type="hidden" name="foto_mobile_expandida_path" value="anuncio">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfoto_mobile_expandida d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>



						<div class="input-group mb-3">
						    <input id="mais_fotos" type="button" value="mais fotos">
						</div>

						<div class="input-group mb-3">
						    <input id="anexo" type="button" value="mais conteúdo">
						</div>

						<labelfor="inlineFormInputGroupFacebook">Facebook</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupFacebook" placeholder="facebook" name="facebook">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscarfacebook d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>


						<label  for="inlineFormInputGroupTwitter">Twitter</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupTwitter" placeholder="Twitter" name="twitter">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscatwitter d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>


	                    <label  for="inlineFormInputGroupYoutube">Youtube</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupYoutube" placeholder="Youtube" name="youtube">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscayoutube d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>



                        <label for="inlineFormInputGroupInstagram">Instagram</label>
                        <div class="input-group mb-3">
							
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupInstagram" placeholder="Instagram" name="instagram">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscainstagram d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>


                        <label for="inlineFormInputGroupWhatsapp">Whatsapp</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupWhatsapp" placeholder="Whatsapp" name="whatsapp">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscawhatsapp d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>


                        <label for="inlineFormInputGroupEndereco">Endereco</label>
                        <div class="input-group mb-3">
							
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupEdereco" placeholder="Endereco" name="endereco">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscaendereco d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>



                        <label for="inlineFormInputGroupTelefone">Telefone</label>
                        <div class="input-group mb-3">
							
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupTelefone" placeholder="Telefone" name="telefone">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscatelefone d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>





                        <label for="inlineFormInputGroupE_mail">E-mail</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupE_mail" placeholder="E-mail" name="e_mail">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscae_mail d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>




                        <label  for="inlineFormInputGroupWebsite">website</label>
                        <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text">
									<i class="fa fa-bars" aria-hidden="true"></i>
								</div>
							</div>
							<input type="text" class="form-control" id="inlineFormInputGroupWebsite" placeholder="Website" name="website">
							<div class="input-group-append">								
								<button name="buscar" type="button" class="btn btn-primary buscarcampo buscawebsite d-none"><i class="fa fa-search" aria-hidden="true"></i></button	>
							</div>
						</div>


                        <label for="inlineFormInputGroupocultar">ocultar</label>
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
						<th>Código Menu</th>
						<th>Menu</th>
						<th>Nome</th>
						<th>Foto</th>
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
<script>
$("#mais_fotos").click(function(){
        url_="https://tooeste.com.br/adm/mvc/view/cadastro_anuncios_fotos.php?id_anuncio="+$("#inlineFormInputGroupcodigo").val();
        top.w2popup.open({
            icon          : 'https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png',
            title         : 'Mais Fotos de anuncios',
            body          : '',
            url           : url_,
            buttons       : '',
            width         : 400,
            height        : 400,                
            minwidth      : 300,
            minheight     : 200,
            overflow      : 'hidden',
            color         : '#333',
            speed         : '0.3',
            opacity       : '0.8',
            bottomFixed   : false,
            showClose     : true,
            showMax       : true,
            showMin       : true
        });
});
$("#anexo").click(function(){
        url_="https://tooeste.com.br/adm/mvc/view/cadastro_anuncios_anexo.php?id_anuncio="+$("#inlineFormInputGroupcodigo").val();
        top.w2popup.open({
            icon          : 'https://raw.githubusercontent.com/KDE/oxygen-icons/master/32x32/apps/accessories-text-editor.png',
            title         : 'Mais Conteudo de noticias',
            body          : '',
            url           : url_,
            buttons       : '',
            width         : 400,
            height        : 400,                
            minwidth      : 300,
            minheight     : 200,
            overflow      : 'hidden',
            color         : '#333',
            speed         : '0.3',
            opacity       : '0.8',
            bottomFixed   : false,
            showClose     : true,
            showMax       : true,
            showMin       : true
        });
});
</script>
