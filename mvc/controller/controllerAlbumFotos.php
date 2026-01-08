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
		public function del($id){
		    $result=parent::findById($id);
		    foreach ($this->settingsImagesBase64 as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
            }		
		    echo json_encode(parent::del());
        }		
		public function find(){
			$this->model->setOrders([$this->model::id=>"DESC"]);
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
	        if(notEmptyParameter(albumFotosDAO::id))$params[albumFotosDAO::id]=getParameter(albumFotosDAO::id);
	        if(notEmptyParameter(albumFotosDAO::id_menu))$params[albumFotosDAO::id_menu]=getParameter(albumFotosDAO::id_menu);
	        if(arrayKeyExistsParameter(albumFotosDAO::nome))$params[albumFotosDAO::nome]=trim(getParameter(albumFotosDAO::nome));
	        if(issetParameter(albumFotosDAO::ocultar))$params[albumFotosDAO::ocultar]=getParameter(albumFotosDAO::ocultar);
			parent::__construct(new albumFotosDAO($params));
			$this->settingsImagesBase64=[
				"foto"=>["path"=>"album","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
			];
		}
	}
?>