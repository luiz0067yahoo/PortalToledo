<?php
session_start();
	if (!(isset($_SESSION["id"]))) exit();
error_reporting(0);
ini_set('display_errors', 0);
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
class configsDAO{
    private $table;
    private $table_alias;
    private $joins;
    private $fields;
    private $atributos;
    private $json_saida;
    public function __construct(){
        $this->table="config";
        $this->table_alias="config";
        $this->joins="   ";
        $this->fields=array(
    		"id"=>array("nome"=>"Código","tipo"=>"integer","nome_banco"=>"id")
    		,"logo"=>array("nome"=>"logo","tipo"=>"file image","nome_banco"=>"logo")
    		,"logo_mobile"=>array("nome"=>"logo_mobile","tipo"=>"file image","nome_banco"=>"logo_mobile")
    		,"mensagem_contato"=>array("nome"=>"mensagem_contato","tipo"=>"varchar(50)","nome_banco"=>"mensagem_contato")
    	);
    	$this->json_saida="";
    }
    public function getFieldsDatabase()
    {
        $fields_database=array();
        foreach ($this->fields as $field_key => $field_property){
            foreach ($field_property as $key => $value){
                if ($key=="nome_banco")
                    array_push($fields_database,$value);
            }
        }
        return $fields_database;
    }
    public function __set(string $atributo, $valor)
    {
        $this->atributos[$atributo] = $valor;
        return $this;
    }
    public function __unset(string $atributo)
    {
        $this->atributos=array_diff_key($this->atributos,array($atributo));
       
        return $this;
    }
    public function __get(string $atributo)
    {
        return $this->atributos[$atributo];
    }
    public function __isset($atributo)
    {
        return isset($this->atributos[$atributo]);
    }
    public  function save()
    {
        $lista=array();
        try
        {
            if(!$this->__isset("id")){
                if(strrpos(json_encode(DAOqueryInsert($this->table,$this->atributos)),"Erro")!=-1)
    		        $result=DAOquerySelectLast($this->table,$this->getFieldsDatabase(),$this->joins);
    	        if(isset($result["data"])){
                    $lista=$result["data"];
    			    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." cadastrada com sucesso!","campos"=>$this->fields,"registros"=>$lista));
    	        }
    			else
    			    $this->json_saida = json_encode(array("mensagem_erro"=>"Erro ao cadastrar","campos"=>$this->fields));
            }
            else {
                $conditions_params=array("id"=>$this->atributos["id"]);
                $params=array_diff_key($this->atributos,$conditions_params);
                if(strrpos(json_encode(DAOqueryUpdate($this->table,$params,$conditions_params)),"Erro")!=-1){
                    $result=DAOquerySelectById($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos["id"]);
                    if(isset($result["data"]))
                        $lista=$result["data"];
                }
    	        if(count($lista)>0)
    			    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." atualizado com sucesso!","campos"=>$this->fields,"registros"=>$lista));
    			else
    			    $this->json_saida = json_encode(array("mensagem_erro"=>"Erro ao atualizar","campos"=>$this->fields));
            } 
            return $this->json_saida;
        }
        catch(Exception $error){}
    }
    public  function find()
    {        
        try{
            $lista=array();
            $result=DAOquerySelect($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos);
            if(isset($result["data"]))
                $lista=$result["data"];
            $numero_registros=count($lista);
    	    $this->json_saida = json_encode(array("mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!","campos"=>$this->fields,"registros"=>$lista));
        	return $this->json_saida;
        }catch(Exception $error){}
    }
    public  function all()
    {  
        //echo "all";
        try{
            $lista=array();
            $result=DAOquerySelectLikeOR($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos);
            if(isset($result["data"]))
                $lista=$result["data"];
            $numero_registros=count($lista);
    	    $this->json_saida = json_encode(array("mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!","campos"=>$this->fields,"registros"=>$lista));
        	return $this->json_saida;
        }catch(Exception $error){}
    }   
    
   
   
    public  function destroy()
    {   
        $lista=array();
        try{
            $conditions_params=array("id"=>$this->atributos["id"]);
            DAOqueryDelete($this->table,$conditions_params);
    	    $lista=DAOquerySelectById($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos["id"]);
        }catch(Exception $erro){echo json_encode($erro);}
        if(count($lista)==0)
		    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." Excluído o com sucesso!","campos"=>$this->fields));
	    return $this->json_saida;
    }
}
?>