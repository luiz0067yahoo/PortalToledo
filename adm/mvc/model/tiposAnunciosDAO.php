<?php 
session_start();
if (!(isset($_SESSION["id"]))) exit();

include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
class tiposAnunciosDAO{
    private $fields;
    private $atributos;
    private $table;
    private $table_alias;
    private $json_saida;
    private $joins;
    private $orderby;
    public function __construct(){
        $this->table="tipos_anuncios";
        $this->table_alias="Tipo Anúncio";
        $this->joins="";
        $this->fields=array(
    		"id"=>array("nome"=>"Código","tipo"=>"integer","nome_banco"=>"id")
    		,"nome"=>array("nome"=>"nome","tipo"=>"varchar(50)","nome_banco"=>"nome")
    		,"largura"=>array("nome"=>"Largura","tipo"=>"integer","nome_banco"=>"largura")	
    		,"altura"=>array("nome"=>"Altura","tipo"=>"integer","nome_banco"=>"altura")
    		,"ocultar"=>array("nome"=>"Ocultar","tipo"=>"boolean","nome_banco"=>"ocultar")
    	);
        $this->json_saida="";
        $this->orderby=$this->table.".nome asc";
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
    		        $lista=DAOquerySelectLast($this->table,$this->getFieldsDatabase(),$this->joins)["data"];
    	        if(count($lista)>0)
    			    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." cadastrado com sucesso!","campos"=>$this->fields,"registros"=>$lista));
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
        try{
            $lista=array();

            $result=DAOquerySelectLikeOR($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos,$this->orderby);
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
        }catch(Exception $erro){}
        if(count($lista)==0)
		    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." Excluído o com sucesso!","campos"=>$this->fields));
	    return $this->json_saida;
    }
     public  function allByFields()
    {
        try{
            $lista=array();
            $result=DAOquerySelect($this->table,array_keys($this->atributos),$this->joins,"");
            if(isset($result["data"]))
                $lista=$result["data"];
            $numero_registros=count($lista);
    	    $this->json_saida = json_encode(array("registros"=>$lista));
        	return $this->json_saida;
        }catch(Exception $error){}
    }
}
?>
