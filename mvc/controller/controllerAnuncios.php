<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAnuncios
	extends controller
	{
	    
	    public function create(){
		    echo json_encode(upload("parent::create"));
		}
		public function update(){
		    echo json_encode(upload("parent::update"));
		}
		public function save(){
            echo json_encode(upload("parent::save"));
        }		
        
		public function del(){
		    $result=parent::findById(getParameter("id"));
		    foreach ($settingsImagesUpload as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$settingsImagesUpload[$key]["path"],$settingsImagesUpload[$key]["formats"]);
            }		
		    parent::del();
        }		
        public function find(){
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
        public function findbyType($nameType){
            echo json_encode($this->model->findbyType($nameType)["data"]);		
        }    
		public function __construct(){
		    $params=[];
	        if(emptyParameter(anunciosDAO::id))$params[anunciosDAO::id]=getParameter(anunciosDAO::id);
	        if(issetParameter(anunciosDAO::idTipoAnuncio))$params[anunciosDAO::idTipoAnuncio]=getParameter(anunciosDAO::idTipoAnuncio);
	        if(issetParameter(anunciosDAO::nome))$params[anunciosDAO::nome]=trim(getParameter(anunciosDAO::nome));
	        if(issetParameter(anunciosDAO::introducao))$params[anunciosDAO::introducao]=getParameter(anunciosDAO::introducao);
	        if(issetParameter(anunciosDAO::descricao))$params[anunciosDAO::descricao]=getParameter(anunciosDAO::descricao);
	        if(arrayKeyExistsParameter(anunciosDAO::ocultar))$params[anunciosDAO::ocultar]=getParameter(anunciosDAO::ocultar);
			parent::__construct(new anunciosDAO($params));
            $this->settingsImagesUpload=[
            "foto"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
            "fotoexpandida"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]
        ];
	
		}
	}
?>