<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosAnexos extends model
{
	const table="anuncios_anexos";
	const id_anuncio="id_anuncio";
	const foto_principal="foto_principal";
	const titulo="titulo";
	const subtitulo="subtitulo";
	const conteudo_anuncio="conteudo_anuncio";
	const fonte="fonte";
	const slide_show="slide_show";
	const acesso="acesso";
	const ocultar="ocultar";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_anuncio,self::foto_principal,self::titulo,self::subtitulo,self::conteudo_anuncio,self::fonte,self::acesso,self::slide_show,self::ocultar]);
    }
}
  
?>