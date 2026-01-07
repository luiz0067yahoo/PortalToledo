<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosFotosDAO extends model
{
	const table="anuncios_fotos";
	const id_anuncio="id_anuncio";
	const nome="nome";
	const foto="foto";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_anuncio,self::nome,self::video,self::ocultar]);
    }
}
  
?>