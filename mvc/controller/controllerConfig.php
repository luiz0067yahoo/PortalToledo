<?php
    require_once ($GLOBALS["base_server_path_files"].'/library/functions.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/model/configsDAO.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controller.php');
	class controllerConfig 
	extends controller
	{

		public function save(){
			echo json_encode(
				parent::saveBase64(function() {
					return parent::save();
				})
			);	
		}

		public function del($id){
			$result=parent::findById($id);
		    foreach ($this->settingsImagesBase64 as $key => $value){
				if(count($result["elements"])>0){
					$file_name=$result["elements"][0][$key];
					if(isset($file_name)&&!empty($file_name)){
						deleteUploadRedimencion($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
					}
				}
			}
		    echo json_encode(parent::del($id));
        }	

		public function update($id){
			$this->model->setParam(configsDAO::logo,"");
			$this->model->setParam(configsDAO::logoMobile,"");
			$result=parent::findById($id);
		    foreach ($this->settingsImagesBase64 as $key => $value){
				if(count($result["elements"])>0){
					$file_name=$result["elements"][0][$key];
					if(isset($file_name)&&!empty($file_name)){
						deleteUploadRedimencion($file_name,$this->settingsImagesBase64[$key]["path"],$this->settingsImagesBase64[$key]["formats"]);
					}
				}
			}
			echo json_encode(
				parent::saveBase64(function() use ($id){
					return parent::update($id);
				})
			);			
		}

		public function create(){
			echo json_encode(
				parent::saveBase64(function(){
					return parent::create();
				})
			);		
		}

		public function find(){
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::find());		
		}

		public function findById($id){
			echo json_encode(parent::findById($id));
		}

		public function __construct(){
			$params=[];
            if(notEmptyParameter(configsDAO::id))
                $params[configsDAO::id] = getParameter(configsDAO::id);

            if(issetParameter(configsDAO::mensagemContato))
                $params[configsDAO::mensagemContato] = getParameter(configsDAO::mensagemContato);

			if(issetParameter(configsDAO::ocultar))$params[configsDAO::ocultar]=getParameter(configsDAO::ocultar);

			
            parent::__construct(new configsDAO($params));

            $this->settingsImagesBase64 = [
                configsDAO::logo => [
                    "path"    => "logo",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ],
                configsDAO::logoMobile => [
                    "path"    => "logo_mobile",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
		}
        
	}
?>