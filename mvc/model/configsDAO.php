<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class configsDAO extends model
{
	const table="config";
	const logo="logo";
	const logo_mobile="logo_mobile";
	const mensagem_contato="fomensagem_contatoto";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::logo,self::logo_mobile,self::mensagem_contato]);
    }
}
  
?>