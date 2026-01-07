<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/configsDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class ControllerConfig 
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
		    echo json_encode($this->upload("parent::create"));
		}
		public function update($id){
		     echo json_encode($this->upload("parent::update"));
		}
		public function save(){
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
	        if(notEmptyParameter(configsDAO::id))$params[configsDAO::id]=getParameter(configsDAO::id);
	        if(issetParameter(configsDAO::mensagem_contato))$params[configsDAO::mensagem_contato]=getParameter(configsDAO::mensagem_contato);
	        $this->settingsImagesUpload=["logo"=>["path"=>"logo","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]];
	        $this->settingsImagesUpload=["logo_mobile"=>["logo_mobile"=>"album","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]];
			parent::__construct(new configsDAO($params));
		}
	}
?>