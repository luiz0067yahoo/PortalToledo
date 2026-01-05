<?php
require_once($base_server_path_files.'/mvc/model/model.php');
class estadosDAO extends model
{
	const table="estados";
	const nome="nome";
	const sigla="sigla";
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::nome,self::sigla]);
    }
}
?>