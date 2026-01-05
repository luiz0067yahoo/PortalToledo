<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/albumVideosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAlbumVideos
	
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
	        if(emptyParameter(albumVideosDAO::id))$params[albumVideosDAO::id]=getParameter(albumVideosDAO::id);
	        if(emptyParameter(albumVideosDAO::id_menu))$params[albumVideosDAO::id_menu]=getParameter(albumVideosDAO::id_menu);
	        if(arrayKeyExistsParameter(albumVideosDAO::nome))$params[albumVideosDAO::nome]=trim(getParameter(albumVideosDAO::nome));
	        if(issetParameter(albumVideosDAO::ocultar))$params[albumVideosDAO::ocultar]=getParameter(albumVideosDAO::ocultar);
			parent::__construct(new albumVideosDAO($params));
		}
	}
?>