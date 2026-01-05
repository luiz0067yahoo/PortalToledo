<?php 
session_start();
	if (!(isset($_SESSION["id"]))) exit();
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/configsDAO.php');
class ControllerConfigs{
    private $model;
    private	$acao;
    public function __construct(){
    	$this->acao=BlockSQLInjection(getParameter("acao"));
        $this->model = new configsDAO();
        if(getParameter("id")!="")              $this->model->__set("id",getParameter("id"));
        if(getParameter("id_menu")!="")         $this->model->__set("id_menu",getParameter("id_menu"));
        if(getParameter("mensagem_contato")!="")            $this->model->__set("mensagem_contato",getParameter("mensagem_contato"));
        if($this->acao=="salvar") {
            $result="";
            $files_uploads =upload(null);
			$count=0;
			if(isset($files_uploads)){
				$logos=array();
				if(isset($files_uploads["logo"]))
					$logos=$files_uploads["logo"];
				$count=count($logos);
			}
			if(isset($files_uploads)){
				$logos_mobile=array();
				if(isset($files_uploads["logo_mobile"]))
					$logos_mobile=$files_uploads["logo_mobile"];
				$count_mobile=count($logos_mobile);
			}
			if(($count>0)&&($count_mobile>0)){
				for ($i=0;$i<max($count,$count_mobile);$i++){
					try{
						if(($i<$count)&&(isset($logos[$i]))){
							$logo=$logos[$i];
							$this->model->__set("logo",$logo."");
						}
						else{
							$this->model->__unset("logo");
						}
					}
					catch (Exception $e) {
							//echo json_encode($e);
					}
					try{
						if(($i<$count_mobile)&&(isset($logos_mobile[$i]))){
							$logo_mobile=$logos_mobile[$i];
							$this->model->__set("logo_mobile",$logo_mobile."");
						}
						else{
							$this->model->__unset("logo_mobile");
						}
					}
					catch (Exception $e) {
							//echo json_encode($e);
					}

                    if($result=="") $result=$this->model->save();
        	        else {
        	            $result_json= json_decode($result);
            	        $result_json->registros=
            	        $result_json->registros+json_decode ($this->model->save())->registros;
            	        $result=json_encode($result_json);
        	        }
        	        if(getParameter("id")!="")
        	            break;

                }
            }
            else  $result=  $this->model->save();
            echo $result;
    	}
    	else if($this->acao=="excluir"){
    		echo $this->model->destroy();
    	}
    	else if($this->acao=="buscarcampo"){
            if(getParameter("campo")!="")      $this->model->__set(getParameter("campo"),getParameter("valor"));
    	    echo $this->model->find();
    	}
    	else if($this->acao=="buscar"){
			
        	echo $this->model->all();
    	}	
    	else if($this->acao=="buscartodos"){
    	    echo $this->model->all();
    	}	
       
    	//$my_Insert_Statement=null;
    	//$my_Db_Connection=null;
    }
   
}
session_start();
if (isset($_SESSION["id"])) $Controller = new ControllerConfigs();
?>