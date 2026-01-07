<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasFotosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerNoticiasFotos 
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
		    echo json_encode($this->upload("parent::create"));
		}
		public function update($id){
			parent::update($id);
		    echo json_encode($this->upload("parent::update"));
		}
		public function save(){
			parent::save();
            echo json_encode($this->upload("parent::save"));
        }		
		public function del($id){
		    $result=parent::findById($id);
		    foreach ($settingsImagesUpload as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$settingsImagesUpload[$key]["path"],$settingsImagesUpload[$key]["formats"]);
            }		
		    echo json_encode(parent::del());
        }		
        
		public function __construct(){
			$params=[];
	        if(notEmptyParameter(noticiasFotosDAO::id))$params[noticiasFotosDAO::id]=getParameter(noticiasFotosDAO::id);
	        if(notEmptyParameter(noticiasFotosDAO::id_noticias))$params[noticiasFotosDAO::id_noticias]=getParameter(noticiasFotosDAO::id_noticias);
	        if(arrayKeyExistsParameter(noticiasFotosDAO::nome))$params[noticiasFotosDAO::nome]=trim(getParameter(noticiasFotosDAO::nome));
	        if(issetParameter(noticiasFotosDAO::ocultar))$params[noticiasFotosDAO::ocultar]=getParameter(noticiasFotosDAO::ocultar);
	        $this->settingsImagesUpload=["foto"=>["path"=>"noticias","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]];
			parent::__construct(new noticiasFotosDAO($params));
		}
	}
?>