<?php 

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if(!$_SESSION) session_start();
if (!(isset($_SESSION["id"]))) exit();
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/noticiasFotosDAO.php');
class ControllerNoticiasFotos{
    private $model;
    private	$acao;
    public function __construct(){
    	$this->acao=BlockSQLInjection(getParameter("acao"));
        $this->model = new noticiasFotosDAO();
        if(getParameter("id")!="")  $this->model->__set("id",getParameter("id"));
        if(getParameter("id_noticia")!="")     {
            $this->model->__set("id_noticia",getParameter("id_noticia"));
        }
        else if($this->acao=="salvar"){    
            $this->model->__set("id_noticia",null);
        }
        if(getParameter("nome")!="")            $this->model->__set("nome",trim(getParameter("nome")));
        if($this->acao=="salvar") {
			
			$this->model->__set("ocultar",(getParameter("ocultar")==1?1:0));
            $result="";
            $files_uploads =upload(null);
			$count=0;
			$fotos=array();
			if(isset($files_uploads)){
				if(isset($files_uploads["foto"]))
					$fotos=$files_uploads["foto"];
				$count=count($fotos);
			}
			$data=[];
			if(($count>0)&&(isset($fotos[0]))){
				foreach ($fotos as $foto){
					$this->model->__set("foto",$foto."");
					$result=$this->model->save();
					$result=json_decode($result);
					array_push($data,$result->registros[0]);
					if($this->model->__isset("id")){
						break;
					}
				}
			}
			$result->registros=$data;
            echo json_encode($result);
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
if (isset($_SESSION["id"])) $Controller = new ControllerNoticiasFotos();
?>