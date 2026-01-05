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
        if(getParameter("nome")!="")            $this->model->__set("nome",trim(getParameter("nome")));
        if(getParameter("introducao")!="")      $this->model->__set("introducao",getParameter("introducao"));
        if(getParameter("introducao2")!="")     $this->model->__set("introducao2",getParameter("introducao2"));
        if(getParameter("descricao")!="")       $this->model->__set("descricao",getParameter("descricao"));
        if(getParameter("facebook")!="")        $this->model->__set("facebook",getParameter("facebook"));
        if(getParameter("twitter")!="")         $this->model->__set("twitter",getParameter("twitter"));
        if(getParameter("youtube")!="")         $this->model->__set("youtube",getParameter("youtube"));
        if(getParameter("instagram")!="")       $this->model->__set("instagram",getParameter("instagram"));
        if(getParameter("whatsapp")!="")        $this->model->__set("whatsapp",getParameter("whatsapp"));
        if(getParameter("endereco")!="")        $this->model->__set("endereco",getParameter("endereco"));
        if(getParameter("telefone")!="")        $this->model->__set("telefone",getParameter("telefone"));
        if(getParameter("e_mail")!="")          $this->model->__set("e_mail",getParameter("e_mail"));
        if(getParameter("website")!="")          $this->model->__set("website",getParameter("website"));
        if(getParameter("ocultar")!="")         $this->model->__set("ocultar",getParameter("ocultar"));
        if($this->acao=="salvar") {
            $result="";
            $files_uploads =upload(null);
			$count=0;
			if(isset($files_uploads)){

				$fotos=array();
				if(isset($files_uploads["foto"]))
					$fotos=$files_uploads["foto"];

				$fotosexpandidas=array();
				if(isset($files_uploads["foto_expandida"]))
					$fotosexpandidas=($files_uploads["foto_expandida"]);

				$fotosmobile=array();
				if(isset($files_uploads["foto_mobile"]))
					$fotosmobile=$files_uploads["foto_mobile"];

				$fotosmobileexpandidas=array();
				if(isset($files_uploads["foto_mobile_expandida"]))
					$fotosmobileexpandidas=($files_uploads["foto_mobile_expandida"]);

				$count=max(count($fotosexpandidas),count($fotos),count($fotosmobile),count($fotosmobileexpandidas));
			}
			if($count>0){
				for ($i=0;$i<$count;$i++){
					try{
						if(($i<count($fotosexpandidas))&&(isset($fotosexpandidas[$i]))){
							$foto_expandida=$fotosexpandidas[$i];
							$this->model->__set("foto_expandida",$foto_expandida."");
						}
						else{
							$this->model->__unset("foto_expandida");
						}
					}
					catch (Exception $e) {
						//echo json_encode($e);
					}

					try{
						if(($i<count($fotos))&&(isset($fotos[$i]))){
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

					try{
						if(($i<count($fotosmobileexpandidas))&&(isset($fotosmobileexpandidas[$i]))){
							$foto_mobile_expandida=$fotosmobileexpandidas[$i];
							$this->model->__set("foto_mobile_expandida",$foto_mobile_expandida."");
						}
						else{
							$this->model->__unset("foto_mobile_expandida");
						}
					}
					catch (Exception $e) {
						//echo json_encode($e);
					}

					try{
						if(($i<count($fotosmobile))&&(isset($fotosmobile[$i]))){
							$foto_mobile=$fotosmobile[$i];
							$this->model->__set("foto_mobile",$foto_mobile."");
						}
						else{
							$this->model->__unset("foto_mobile");
							
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