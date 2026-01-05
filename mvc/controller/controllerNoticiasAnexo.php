<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasAnexosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerNoticias 
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
	        if(emptyParameter(noticiasAnexosDAO::id))                         $params[noticiasAnexosDAO::id]=getParameter(noticiasAnexosDAO::id);
	        if(emptyParameter(noticiasAnexosDAO::id_noticia))                    $params[noticiasAnexosDAO::id_noticia]=getParameter(noticiasAnexosDAO::id_menu);
	        if(issetParameter(noticiasAnexosDAO::titulo))                     $params[noticiasAnexosDAO::titulo]=getParameter(noticiasAnexosDAO::titulo);
	        if(arrayKeyExistsParameter(noticiasAnexosDAO::subtitulo))         $params[noticiasAnexosDAO::subtitulo]=getParameter(noticiasAnexosDAO::subtitulo);
	        if(arrayKeyExistsParameter(noticiasAnexosDAO::conteudo_noticia))  $params[noticiasAnexosDAO::subtitulo]=getParameter(noticiasAnexosDAO::conteudo_noticia);
	        if(arrayKeyExistsParameter(noticiasAnexosDAO::fonte))             $params[noticiasAnexosDAO::fonte]=getParameter(noticiasAnexosDAO::fonte);
            if(emptyParameter(noticiasAnexosDAO::acesso))                     $params[noticiasAnexosDAO::acesso]=getParameter(noticiasAnexosDAO::acesso);
	        if(issetParameter(noticiasAnexosDAO::slide_show))                 $params[noticiasAnexosDAO::slide_show]=getParameter(noticiasAnexosDAO::slide_show);
	        if(issetParameter(noticiasAnexosDAO::ocultar))                    $params[noticiasAnexosDAO::ocultar]=getParameter(noticiasAnexosDAO::ocultar);
			parent::__construct(new noticiasAnexosDAO($params));
			$this->settingsImagesUpload=[
            "foto_principal"=>["path"=>"noticias","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
        ];
		}
	}
?>