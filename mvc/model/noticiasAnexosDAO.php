<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class noticiasAnexosDAO extends model
{
	const table="noticias_anexos";
	const idNoticia="id_noticia";
	const fotoPrincipal="foto_principal";
	const titulo="titulo";
	const subtitulo="subtitulo";
	const conteudoNoticiaAnexo="conteudo_noticia_anexo";
	const fonte="fonte";
	const acesso="acesso";
	const ocultar="ocultar";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idNoticia,self::fotoPrincipal,self::titulo,self::subtitulo,self::conteudoNoticiaAnexo,self::fonte,self::ocultar]);
    }
}
  
?>