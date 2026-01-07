<?php
	header ('Content-type: text/html; charset=UTF-8');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
	class controller{
		protected model $model;
		private	$action;
		private	$params;
		protected $settingsImagesUpload;
		protected $findParams;
		public function __construct(model $model){
			$this->model=$model;
			$this->action=BlockSQLInjection(getParameter("action"));
			$this->findParams=getParameter("findParams");
			if((getParameter("page")!="")&&(getParameter("row_count")!="")){    	
				$this->model->limit["page"]=getParameter("page");
				$this->model->limit["row_count"]=getParameter("row_count");
			}
			if(!isset($this->model->limit["page"]))
			    $this->model->limit["page"]=0;
			if(!isset($this->model->limit["row_count"]))
			    $this->model->limit["row_count"]=10;
			$this->settingsImagesUpload=[];
		}
	    public function upload($call_back_function){
		    $files_uploads=uploadImageRedimencion($this->settingsImagesUpload);
		    $biggerCountFiles=0;
		    $result=[];
		    foreach ($files_uploads as $key => $files){
		        if(($biggerCountFiles==0)||($bigger<count($files)))
		            $biggerCountFiles=count($files);
		    }
    		for($i=0;$i<$biggerCountFiles;$i++){
		        foreach ($files_uploads as $key => $files){
		            
    		        if (count($files)>$i){
    		            $this->model->setParam($key,$files[$i]);
    		            if(empty($files[$i]))
		                    $this->model->unParam($key);
    		        }
    		    }
    		    if(isset($result["data"]))
		            $result["data"]+=call_user_func($call_back_function)["data"];
		        else
		            $result=call_user_func($call_back_function);
		        if (!empty($this->model->getParam("id")));//is update only one file photo
		            break;
		    }
		    return $result;
	    }
		public function getModel(){
			return $this->model;
		}
		public function setModel($model){
			$this->model=$model;
		}
		public function create(){
			return($this->model->create());
		}
		public function createSQL(){
			return($this->model->createSQL());
		}
		public function update($id){
			return($this->model->update($id));
		}
		public function updateSQL($id){
			return($this->model->updateSQL($id));
		}
		public function save(){
			return($this->model->save());
		}
		public function saveSQL(){
			return($this->model->saveSQL());
		}
		public function del($id){
			return($this->model->destroy($id));
		}
		public function delSQL($id){
			return($this->model->destroySQL($id));
		}
		public function find(){
			return($this->model->find());
		}
		public function findSQL(){
			return($this->model->findSQL());
		}
    	public function all(){
			return($this->model->all());
		}
    	public function allSQL(){
			return($this->model->allSQL());
		}
		public function findById($id){
			return($this->model->findById($id));
		}
		public function findByIdSQL($id){
			return($this->model->findByIdSQL($id));
		}
	}
?>