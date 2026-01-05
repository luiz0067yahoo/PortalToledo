<?php
	header ('Content-type: text/html; charset=UTF-8');
	if(isset($_SESSION))
		session_start();
	include($base_server_path_files.'/library/functions.php');
	class controller{
		protected $model;
		private	$action;
		private	$params;
		protected $settingsImagesUpload;
		public function __construct($model){
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
		public function update(){
			return($this->model->update());
		}
		public function save(){
			return($this->model->save());
		}
		public function del($id){
			return($this->model->destroy($id));
		}
		public function find(){
			return($this->model->find());
		}
    	public function all(){
			return($this->model->all());
		}
		public function findById($id){
			return($this->model->findById($id));
		}
	}
?>