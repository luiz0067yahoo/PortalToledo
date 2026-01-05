<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosFotosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAnunciosFotos 
	extends controller
	{
	    public function find(){
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
         
	    public function create(){
		    echo json_encode($this->upload("parent::create"));
		}
		public function update(){
		     echo json_encode($this->upload("parent::update"));
		}
		public function save(){
             echo json_encode($this->upload("parent::save"));
        }		
		public function del(){
		    $result=parent::findById(getParameter("id"));
		    foreach ($settingsImagesUpload as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$settingsImagesUpload[$key]["path"],$settingsImagesUpload[$key]["formats"]);
            }		
		    echo json_encode(parent::del());
        }		
        
		public function __construct(){
	        if(emptyParameter(anunciosFotosDAO::id))$params[anunciosFotosDAO::id]=getParameter(anunciosFotosDAO::id);
	        if(emptyParameter(anunciosFotosDAO::id_anuncio))$params[anunciosFotosDAO::id_anuncio]=getParameter(anunciosFotosDAO::id_anuncio);
	        if(arrayKeyExistsParameter(anunciosFotosDAO::nome))$params[anunciosFotosDAO::nome]=getParameter(anunciosFotosDAO::nome);
	        if(issetParameter(anunciosFotosDAO::ocultar))$params[anunciosFotosDAO::ocultar]=getParameter(anunciosFotosDAO::ocultar);
	        $this->settingsImagesUpload=["foto"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]];
			parent::__construct(new anunciosFotosDAO($params));
		}
	}
?>