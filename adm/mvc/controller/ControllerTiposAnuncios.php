<?php 
session_start();
if (!(isset($_SESSION["id"]))) exit();
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/tiposAnunciosDAO.php');
class ControllerTiposAnuncios{
    private $model;
    private	$acao;
    public function __construct(){
    	$this->acao=BlockSQLInjection(getParameter("acao"));
        $this->model = new tiposAnunciosDAO();
        if(getParameter("id")!="")          $this->model->__set("id",getParameter("id"));
        if(getParameter("nome")!="")        $this->model->__set("nome",getParameter("nome"));
        if(getParameter("largura")!="")     $this->model->__set("largura",getParameter("largura"));
        if(getParameter("altura")!="")      $this->model->__set("altura",getParameter("altura"));
        if(getParameter("ocultar")!="")     $this->model->__set("ocultar",(getParameter("ocultar")=="true"));
    	if($this->acao=="salvar"){
    	    echo $this->model->save();
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
           
            $chave=BlockSQLInjection(getParameter("chave"));
            $campo=BlockSQLInjection(getParameter("campo"));
            $valor=BlockSQLInjection(getParameter("valor"));
            $modelo=BlockSQLInjection(getParameter("modelo"));
    	    echo $this->model->all();
    	}
    	$my_Insert_Statement=null;
    	$my_Db_Connection=null;
    }
}
$Controller = new ControllerTiposAnuncios();
?>