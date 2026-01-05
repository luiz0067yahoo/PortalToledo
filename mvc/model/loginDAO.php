<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class loginDAO extends model
{
        
	const table="login";
	const id_usuarios="id_usuarios";
	const hora_inicio="hora_inicio";
	const hora_fim="hora_fim";
	const data_inicio="data_inicio";
	const data_fim="data_fim";
	//id_usuarios,hora_inicio,hora_fim,data_inicio,data_fim
  
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_usuarios,self::hora_inicio,self::hora_fim,self::data_inicio,self::data_fim]);
    }
}
  
?>	