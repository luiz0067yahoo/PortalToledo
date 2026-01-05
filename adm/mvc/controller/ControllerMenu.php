<?php 
session_start();
	if (!(isset($_SESSION["id"]))) exit();
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/menusDAO.php');
class ControllerMenus{
    private $model;
    private	$acao;
    public function __construct(){
    	$this->acao=BlockSQLInjection(getParameter("acao"));
        $this->model = new menusDAO();
        if(getParameter("id")!="")              $this->model->__set("id",getParameter("id"));
        if(
            (getParameter("id_menu")!="")
        )     {
            $this->model->__set("id_menu",getParameter("id_menu"));
        }
        else if($this->acao=="salvar"){    
            $this->model->__set("id_menu",null);
        }
        if(getParameter("nome")!="")            $this->model->__set("nome",trim(getParameter("nome")));
        if(getParameter("tema")!="")            $this->model->__set("tema",getParameter("tema"));
        if(getParameter("descricao")!="")       $this->model->__set("descricao",getParameter("descricao"));
        if($this->acao=="salvar") {
			$this->model->__set("ocultar",(getParameter("ocultar")==1?1:0));
            $result="";
            $files_uploads =upload(null);
			$count=0;
			if(isset($files_uploads)){
				$icones=array();
				if(isset($files_uploads["icone"]))
					$icones=$files_uploads["icone"];
				$count=count($icones);
			}
			
			if(($count>0)&&(isset($icones[0]))){
				$icone=$icones[0];
				$this->model->__set("icone",$icone."");
			}
            $result=  $this->model->save();
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
        else if($this->acao=="selectAjax"){
    	    echo $this->model->selectAjax();
    	}
    	//$my_Insert_Statement=null;
    	//$my_Db_Connection=null;
    }
   
}
session_start();
if (isset($_SESSION["id"])) $Controller = new ControllerMenus();
?>