<?php
session_start();
	if (!(isset($_SESSION["id"]))) exit();
//error_reporting(1);
//ini_set('display_errors', 1);
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
class menusDAO{
    private $table;
    private $table_alias;
    private $joins;
    private $fields;
    private $atributos;
    private $json_saida;
    public function __construct(){
        $this->table="menus";
        $this->table_alias="Menus";
        $this->joins="  left join menus pai on (pai.id=menus.id_menu) ";
        $this->fields=array(
    		"id"=>array("nome"=>"Código","tipo"=>"integer","nome_banco"=>"id")
    		,"id_menu"=>array("nome"=>"Código Menu","tipo"=>"integer fk","nome_banco"=>"id_menu")	
    		,"menu_pai"=>array("nome"=>"Menu Pai","tipo"=>"varchar(50)","nome_banco"=>"pai.nome as menu_pai")
    		,"icone"=>array("nome"=>"Icone","tipo"=>"file image","nome_banco"=>"icone")
    		,"nome"=>array("nome"=>"Nome","tipo"=>"varchar(50)","nome_banco"=>"nome")
    		,"tema"=>array("nome"=>"tema","tipo"=>"varchar(50)","nome_banco"=>"tema")
    		,"descricao"=>array("nome"=>"Descrição","tipo"=>"varchar(50)","nome_banco"=>"descricao")
    		,"ocultar"=>array("nome"=>"Ocultar","tipo"=>"boolean","nome_banco"=>"ocultar")
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
            //echo json_encode($this->atributos);
            if(!$this->__isset("id")){
                if(strrpos(json_encode(DAOqueryInsert($this->table,$this->atributos)),"Erro")!=-1)
    		        $result=DAOquerySelectLast($this->table,$this->getFieldsDatabase(),$this->joins);
    	        if(isset($result["data"])){
                    $lista=$result["data"];
    			    $this->json_saida = json_encode(array("mensagem_sucesso"=>$this->table_alias." cadastrado com sucesso!","campos"=>$this->fields,"registros"=>$lista));
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
            $result=DAOquerySelectLikeOR($this->table,$this->getFieldsDatabase(),$this->joins,$this->atributos,"");
            if(isset($result["data"]))
                $lista=$result["data"];
            $numero_registros=count($lista);
    	    $this->json_saida = json_encode(array("mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!","campos"=>$this->fields,"registros"=>$lista));
        	return $this->json_saida;
        }catch(Exception $error){}
    }   
    
    public  function selectAjax()
    {  
        $id_position=0;
        $id_nome=1;
        try{
            
            
            
            
            /*$sql="SELECT id,nome FROM `menus` where(nome!='home') and  (id_menu is null) and (ocultar=false)";
            $sql.=" union ";
            $sql.="SELECT null,null FROM `menus` limit 1,1";
            $result=DAOquery($sql,[]);*/
            
            $lista=array();
            array_push($lista,[null,""]);
            $result=DAOquerySelect($this->table,["id","nome"],$this->joins,
                [
                    "id"=>["!="=>"home"]
                    ,"id_menu"=>null
                    ,"ocultar"=>false
                ]);
            if(isset($result["data"])){
                $lista_main_menu=$result["data"];
                foreach($lista_main_menu as $menu_data){
                    array_push($lista,$menu_data);
                    $result_childrens=DAOquerySelect($this->table,["id","nome"],$this->joins,
                        [
                            "id_menu"=>$menu_data[$id_position]
                            ,"ocultar"=>false
                        ]);
                        
                    
                    if(isset($result_childrens["data"])){
                       
                         $lista_childrens=$result_childrens["data"];
                        foreach($lista_childrens as $childrens){
                            $childrens[$id_nome]=$menu_data[$id_nome]."/".$childrens[$id_nome];
                            array_push($lista,$childrens);
                        }
                    }
                }
            }    
               
            $numero_registros=count($lista);
    	    $this->json_saida = json_encode(array("mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!","campos"=>$this->fields,"registros"=>$lista));
        	return $this->json_saida;
        }catch(Exception $error){ echo json_encode($error);}
    }
    
    /*
    	else if($acao=="selectAjax"){
    		$modelo="menus";
    		$chave=getParameter("id");
    		$valor=getParameter("valor");
    		$sql="select $chave,$valor from $modelo where id_menu is null ";
    		try { 
    			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
    			if ($my_Insert_Statement->execute()) {
    			  //echo "New record created successfully";
    			} else {
    			  //echo "Unable to create record";
    			}	
    			$lista=array();
    			$linha = array("","PRINCIPAL");
    			array_push($lista,$linha);
    			while ($resultado=$my_Insert_Statement->fetch()) {
    				$linha = array($resultado[$chave],$resultado[$valor]);
    				array_push($lista,$linha); 
    				    $sql_filho="select $chave,$valor from $modelo where id_menu = 0".$resultado[$chave];
    			        $my_Insert_Statement_filho = $my_Db_Connection->prepare($sql_filho);
    		   			if ($my_Insert_Statement_filho->execute()) {
            			  //echo "New record created successfully";
            			} else {
            			  //echo "Unable to create record";
            			}	
                        while ($resultado_filho=$my_Insert_Statement_filho->fetch()){
                            $linha_filho = array($resultado_filho[$chave],$resultado[$valor]."->".$resultado_filho[$valor]);
    				        array_push($lista,$linha_filho); 
                        } 
    			}
    			$json_saida = json_encode(
    				array(
    					"registros"=>$lista
    				)
    			);
    			echo $json_saida;				
    		}
    		catch (PDOException $error) {
    		   //echo 'Connection error: ' . $error->getMessage();
    		}
    	}		
    
    */
   
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