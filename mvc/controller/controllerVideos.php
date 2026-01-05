<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/videosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerVideos extends controller{
        public function find(){
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
         
	    public function create(){
		    echo json_encode(parent::create());
		}
		public function update(){
		     echo json_encode(parent::update());
		}
		public function save(){
             echo json_encode(parent::save());
        }		
		public function del(){
		    echo json_encode(parent::del());
        }

		public function __construct(){
	        if(emptyParameter(videosDAO::id))$params[videosDAO::id]=getParameter(videosDAO::id);
	        if(emptyParameter(videosDAO::id_album))$params[videosDAO::id_album]=getParameter(videosDAO::id_album);
	        if(arrayKeyExistsParameter(videosDAO::nome))$params[videosDAO::nome]=trim(getParameter(videosDAO::nome));
	        if(arrayKeyExistsParameter(videosDAO::video))$params[videosDAO::video]=getParameter(videosDAO::video);
	        if(issetParameter(videosDAO::ocultar))$params[videosDAO::ocultar]=getParameter(videosDAO::ocultar);
			parent::__construct(new videoDAO($params));
		}
	}
?>