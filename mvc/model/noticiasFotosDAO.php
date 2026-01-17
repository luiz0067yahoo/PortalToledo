<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class noticiasFotosDAO extends model
{
	const table="noticias_fotos";
	const idNoticia="id_noticia";
	const nome="nome";
	const foto="foto";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idNoticia,self::nome,self::foto,self::ocultar]);
    }
}
  
?>