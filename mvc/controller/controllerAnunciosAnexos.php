<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosAnexosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAnunciosAnexos
	extends controller
	{
	    public function find(){
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
	    public function create(){
		    parent::create();
		    upload("parent::create");
		}
		public function update($id){
		    parent::update($id);
		    upload("parent::update");
		}
		public function save(){
		    parent::save();
            upload("parent::save");
        }		
		public function del($id){
		    $result=parent::findById($id);
		    foreach ($this->settingsImagesBase64 as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
            }		
		    echo json_encode(parent::del());
        }			
		public function __construct(){
		    $params=[];
	        if(notEmptyParameter(anunciosAnexosDAO::id))                           $params[anunciosAnexosDAO::id]=getParameter(anunciosAnexosDAO::id);
	        if(notEmptyParameter(anunciosAnexosDAO::id_anuncio))                   $params[anunciosAnexosDAO::id_anuncio]=getParameter(anunciosAnexosDAO::id_menu);
	        if(issetParameter(anunciosAnexosDAO::titulo))                       $params[anunciosAnexosDAO::titulo]=trim(getParameter(anunciosAnexosDAO::titulo));
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::subtitulo))           $params[anunciosAnexosDAO::subtitulo]=getParameter(anunciosAnexosDAO::subtitulo);
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::conteudo_anuncio))    $params[anunciosAnexosDAO::subtitulo]=getParameter(anunciosAnexosDAO::conteudo_anuncio);
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::fonte))               $params[anunciosAnexosDAO::fonte]=getParameter(anunciosAnexosDAO::fonte);
            if(notEmptyParameter(anunciosAnexosDAO::acesso))                       $params[anunciosAnexosDAO::acesso]=getParameter(anunciosAnexosDAO::acesso);
	        if(issetParameter(anunciosAnexosDAO::slide_show))                   $params[anunciosAnexosDAO::slide_show]=getParameter(anunciosAnexosDAO::slide_show);
	        if(issetParameter(anunciosAnexosDAO::ocultar))                      $params[anunciosAnexosDAO::ocultar]=getParameter(anunciosAnexosDAO::ocultar);
			parent::__construct(new anunciosAnexosDAO($params));
			$this->settingsImagesUpload=[
				"foto_principal"=>["path"=>"anuncios_anexos","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
			];
		}
	}
?>