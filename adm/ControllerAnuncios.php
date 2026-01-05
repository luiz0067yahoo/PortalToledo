<?php 
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/anunciosDAO.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/mvc/model/tiposAnunciosDAO.php');
class ControllerAnuncios{
    private $model;
    private	$acao;
    public function __construct(){
    	$this->acao=BlockSQLInjection(getParameter("acao"));
        $this->model = new anunciosDAO();
        if(getParameter("id")!="")              $this->model->__set("id",getParameter("id"));
        if(getParameter("id_menu")!="")         $this->model->__set("id_menu",getParameter("id_menu"));
        if(getParameter("id_tipo_anuncio")!="") $this->model->__set("id_tipo_anuncio",getParameter("id_tipo_anuncio"));
        if(getParameter("nome")!="")            $this->model->__set("nome",getParameter("nome"));
        if($this->acao=="salvar") {
            $result="";
            $files_uploads =upload(null);

			

            $fotos=array();
			if(isset($files_uploads["foto"]));
				$fotos=$files_uploads["foto"];
			$fotosexpandidas=array();
			if(isset($files_uploads["fotoexpandida"]));
				$fotosexpandidas=($files_uploads["fotoexpandida"]);
			$count=max(count($fotosexpandidas),count($fotos));
			
			if($count>0){
				for ($i=0;$i<$count;$i++){
					try{
						if(isset($fotosexpandidas[$i])){
							$fotoexpandida=$fotosexpandidas[$i];
							$this->model->__set("fotoexpandida",$fotoexpandida."");
						}
						else{
							$this->model->__unset("fotoexpandida");
						}
					}
					catch (Exception $e) {
						//echo json_encode($e);
					}

					try{
						if(isset($fotos[$i])){
							$foto=$fotos[$i];
							$this->model->__set("foto",$foto."");
						}
						else{
							$this->model->__unset("foto");
							
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
    	else if($this->acao=="selectAjax"){
            $modelAjax=new tiposAnunciosDAO();
            $modelAjax->__set(getParameter("chave"),"");
            $modelAjax->__set(getParameter("valor"),"");
    	    echo $modelAjax->allByFields();
    	}
    	//$my_Insert_Statement=null;
    	//$my_Db_Connection=null;
    }
}
$Controller = new ControllerAnuncios();
?>