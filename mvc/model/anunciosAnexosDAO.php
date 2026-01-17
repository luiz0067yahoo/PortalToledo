<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosAnexosDAO extends model
{
	const table="anuncios_anexos";
	const idAnuncio="id_anuncio";
	const fotoPrincipal="foto_principal";
	const titulo="titulo";
	const subtitulo="subtitulo";
	const conteudoAnuncio="conteudo_anuncio_anexo";
	const fonte="fonte";
	const acesso="acesso";
	const ocultar="ocultar";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idAnuncio,self::fotoPrincipal,self::titulo,self::subtitulo,self::conteudoAnuncio,self::fonte,self::ocultar]);
    }
}
  
?>