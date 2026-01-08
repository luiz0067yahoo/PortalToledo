<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/fotosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerFotos 
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
		    foreach ($this->settingsImagesBase64 as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
            }		
		    echo json_encode(parent::del());
        }		
        
		public function __construct(){
			$params=[];
	        if(notEmptyParameter(fotosDAO::id))$params[fotosDAO::id]=getParameter(fotosDAO::id);
	        if(notEmptyParameter(fotosDAO::id_album))$params[fotosDAO::id_album]=getParameter(fotosDAO::id_album);
	        if(arrayKeyExistsParameter(fotosDAO::nome))$params[fotosDAO::nome]=trim(getParameter(fotosDAO::nome));
	        if(issetParameter(fotosDAO::ocultar))$params[fotosDAO::ocultar]=getParameter(fotosDAO::ocultar);
	        $this->settingsImagesUpload=["foto"=>["path"=>"album","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]];
			parent::__construct(new fotosDAO($params));
		}
	}
?>