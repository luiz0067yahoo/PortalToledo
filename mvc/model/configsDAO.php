<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class configsDAO extends model
{
	const table="config";
	const logo="logo";
	const logoMobile="logo_mobile";
	const mensagemContato="mensagem_contato";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::logo,self::logoMobile,self::mensagemContato]);
    }
} 
  
?>