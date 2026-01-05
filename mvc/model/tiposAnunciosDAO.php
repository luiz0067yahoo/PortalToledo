<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class tiposAnunciosDAO extends model
{
	const table="tipo_anuncio";
	const nome="nome";
	const altura="altura";
	const largura="largura";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_menu,self::nome,self::altura,self::largura,self::ocultar]);
    }
}
  
?>