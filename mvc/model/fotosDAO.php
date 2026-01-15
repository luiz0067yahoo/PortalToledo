<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class fotosDAO extends model
{
	const table="fotos";
	const idAlbum="id_album";
	const nome="nome";
	const foto="foto";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idAlbum,self::nome,self::foto,self::ocultar]);
    }
}
  
?>