<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class loginDAO extends model
{
        
	const table="login";
	const idUsuario="id_usuario";
	const token="token";
	const dataInicio="data_inicio";
	const horaInicio="hora_inicio";
	const dataFim="data_fim";
	const horaFim="hora_fim";
	//id_usuarios,hora_inicio,hora_fim,data_inicio,data_fim
  
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idUsuario,self::horaInicio,self::horaFim,self::dataInicio,self::dataFim,self::token]);
    }

	public function renewToken($idUsuario,$token,$newToken){
	    $this->cleanParams();
        $this->setParam(self::token,$newToken);
        return DAOqueryUpdate(self::table, $this->getParams(), [self::idUsuario=>$idUsuario,self::token=>$token]);		
    }
	
	public function logout($idUsuario,$token){
	    $this->cleanParams();
        $this->setParam(self::dataFim,date("Y-m-d"));
        $this->setParam(self::horaFim,date("H:i:s"));
        return $this->update([self::idUsuario=>$idUsuario,self::token=>$token]);		
    }

	public function findUserIdToken($idUsuario,$token){
	    $this->cleanParams();
        $this->setParam(self::idUsuario,$idUsuario);
        $this->setParam(self::token,$token);
        $this->setOrders([self::id=>"desc"]);
		if(count($this->find()["elements"])>=1){
			return $this->find()["elements"][0];		
		}
		return null;
    }
}
  
?>