<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosFotosDAO extends model
{
	const table="anuncios_fotos";
	const idAnuncio="id_anuncio";
	const nome="nome";
	const foto="foto";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idAnuncio,self::nome,self::foto,self::ocultar]);
    }
}
  
?>