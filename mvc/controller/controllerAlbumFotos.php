<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/albumFotosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAlbumFotos
	
	extends controller
	{
	    public function save(){
			echo json_encode(parent::save());		
		}
		public function del(){
			echo json_encode(parent::del());		
		}
		public function find(){
            echo json_encode(parent::find());		
		}
			public function findById($id){
			echo json_encode(parent::findById($id));
		}
	    public function findSlideShow($menuSubMenu){
            echo json_encode($this->model->findSlideShow($menuSubMenu)["elements"]);
        }
	    public function findMenuAlbum($menuSubMenu){
            $page=getParameter("page");
            if (
                !(intval($page)>=0)
            )
                $page=0;  
            echo json_encode($this->model->findMenuAlbum($menuSubMenu,$page,27)["elements"]);
        }
		public function __construct(){
		     $params=[];
	        if(emptyParameter(albumFotosDAO::id))$params[albumFotosDAO::id]=getParameter(albumFotosDAO::id);
	        if(emptyParameter(albumFotosDAO::id_menu))$params[albumFotosDAO::id_menu]=getParameter(albumFotosDAO::id_menu);
	        if(arrayKeyExistsParameter(albumFotosDAO::nome))$params[albumFotosDAO::nome]=trim(getParameter(albumFotosDAO::nome));
	        if(issetParameter(albumFotosDAO::ocultar))$params[albumFotosDAO::ocultar]=getParameter(albumFotosDAO::ocultar);
			parent::__construct(new albumFotosDAO($params));
		}
	}
?>