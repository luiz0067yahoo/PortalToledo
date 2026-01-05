<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasDAO.php');
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
        public function findSlideShow($menuSubMenu){
            echo json_encode($this->model->findSlideShow($menuSubMenu)["elements"]);		
        }
        public function findHome(){
            $page=getParameter("page");
            if (
                !(intval($page)>=0)
            )
                $page=0;  
            echo $page;
            echo json_encode($this->model->findHome($page));
        }
        
        public function findMenu($menuSubMenu){
            $page=getParameter("page");
            if (
                !(intval($page)>=0)
            )
                $page=0;  
            echo json_encode($this->model->findMenu($menuSubMenu,$page,27)["elements"]);
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
	        if(emptyParameter(noticiasDAO::id))                         $params[noticiasDAO::id]=getParameter(noticiasDAO::id);
	        if(emptyParameter(noticiasDAO::id_menu))                    $params[noticiasDAO::id_menu]=getParameter(noticiasDAO::id_menu);
	        if(issetParameter(noticiasDAO::titulo))                     $params[noticiasDAO::titulo]=trim(getParameter(noticiasDAO::titulo));
	        if(arrayKeyExistsParameter(noticiasDAO::subtitulo))         $params[noticiasDAO::subtitulo]=getParameter(noticiasDAO::subtitulo);
	        if(arrayKeyExistsParameter(noticiasDAO::conteudo_noticia))  $params[noticiasDAO::subtitulo]=getParameter(noticiasDAO::conteudo_noticia);
	        if(arrayKeyExistsParameter(noticiasDAO::fonte))             $params[noticiasDAO::fonte]=getParameter(noticiasDAO::fonte);
            if(emptyParameter(noticiasDAO::acesso))                     $params[noticiasDAO::acesso]=getParameter(noticiasDAO::acesso);
	        if(issetParameter(noticiasDAO::slide_show))                 $params[noticiasDAO::slide_show]=getParameter(noticiasDAO::slide_show);
	        if(issetParameter(noticiasDAO::ocultar))                    $params[noticiasDAO::ocultar]=getParameter(noticiasDAO::ocultar);
			parent::__construct(new noticiasDAO($params));
			$this->settingsImagesUpload=[
            "foto_principal"=>["path"=>"noticias","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
        ];
		}
	}
?>