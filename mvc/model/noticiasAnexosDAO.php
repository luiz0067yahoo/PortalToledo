<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class noticiasAnexos extends model
{
	const table="noticias_anexos";
	const id_noticia="id_noticia";
	const foto_principal="foto_principal";
	const titulo="titulo";
	const subtitulo="subtitulo";
	const conteudo_noticia="conteudo_noticia";
	const fonte="fonte";
	const slide_show="slide_show";
	const acesso="acesso";
	const ocultar="ocultar";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_noticia,self::foto_principal,self::titulo,self::subtitulo,self::conteudo_noticia,self::fonte,self::acesso,self::slide_show,self::ocultar]);
    }
}
  
?>