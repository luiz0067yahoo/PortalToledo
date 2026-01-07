<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class tiposAnunciosDAO extends model
{
	const table="tipos_anuncios";
	const nome="nome";
	const altura="altura";
	const largura="largura";
	const ocultar="ocultar";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::nome,self::altura,self::largura,self::ocultar]);
    }
}
  
?>