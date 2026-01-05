<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosAnexosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controlleranuncios 
	extends controller
	{
	    public function find(){
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
	    public function create(){
		    upload("parent::create");
		}
		public function update(){
		    upload("parent::update");
		}
		public function save(){
            upload("parent::save");
        }		
		public function del(){
		    $result=parent::findById(getParameter("id"));
		    foreach ($settingsImagesUpload as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$settingsImagesUpload[$key]["path"],$settingsImagesUpload[$key]["formats"]);
            }		
		    parent::del();
        }		

		public function __construct(){
		    $params=[];
	        if(emptyParameter(anunciosAnexosDAO::id))                           $params[anunciosAnexosDAO::id]=getParameter(anunciosAnexosDAO::id);
	        if(emptyParameter(anunciosAnexosDAO::id_anuncio))                   $params[anunciosAnexosDAO::id_anuncio]=getParameter(anunciosAnexosDAO::id_menu);
	        if(issetParameter(anunciosAnexosDAO::titulo))                       $params[anunciosAnexosDAO::titulo]=trim(getParameter(anunciosAnexosDAO::titulo));
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::subtitulo))           $params[anunciosAnexosDAO::subtitulo]=getParameter(anunciosAnexosDAO::subtitulo);
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::conteudo_anuncio))    $params[anunciosAnexosDAO::subtitulo]=getParameter(anunciosAnexosDAO::conteudo_anuncio);
	        if(arrayKeyExistsParameter(anunciosAnexosDAO::fonte))               $params[anunciosAnexosDAO::fonte]=getParameter(anunciosAnexosDAO::fonte);
            if(emptyParameter(anunciosAnexosDAO::acesso))                       $params[anunciosAnexosDAO::acesso]=getParameter(anunciosAnexosDAO::acesso);
	        if(issetParameter(anunciosAnexosDAO::slide_show))                   $params[anunciosAnexosDAO::slide_show]=getParameter(anunciosAnexosDAO::slide_show);
	        if(issetParameter(anunciosAnexosDAO::ocultar))                      $params[anunciosAnexosDAO::ocultar]=getParameter(anunciosAnexosDAO::ocultar);
			parent::__construct(new anunciosAnexosDAO($params));
			$this->settingsImagesUpload=[
            "foto_principal"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
        ];
		}
	}
?>