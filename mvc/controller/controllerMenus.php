<?php
    require_once ($GLOBALS["base_server_path_files"].'/library/functions.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/model/menusDAO.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controller.php');
	class controllerMenus 
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
			$this->model->setParam(menusDAO::icone,"");
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
        public function findMainMenus(){
            echo json_encode($this->model->findMainMenus()["elements"]);		
		}
        public function findMenusHierarchy(){
			$this->model->setOrders([menusDAO::idMenu=>"ASC"]);
			//$this->model->setOrders([menusDAO::nome=>"ASC"]);
			$this->model->cleanFields();
			$this->model->addField(menusDAO::id);
			$this->model->addField(" 
				IF(
					menus_main.nome IS NULL,
					menus.nome,
					CONCAT(menus_main.nome, ' > ', menus.nome)
				) AS nome
			");
			$this->model->setJoins(
				" left join menus as menus_main on(".menusDAO::table.".".menusDAO::idMenu."=menus_main.id) "
			);
			$this->model->setRowCount(1000);
			echo json_encode($this->model->find());		
		}
		public function findSubMenus($id_menu){
		    echo json_encode($this->model->findSubMenus($id_menu)["elements"]);		
		}
		public function __construct(){
			$params=[];
	        if(notEmptyParameter(menusDAO::id))$params[menusDAO::id]=getParameter(menusDAO::id);
	        if(notEmptyParameter(menusDAO::idMenu))$params[menusDAO::idMenu]=getParameter(menusDAO::idMenu);
	        if(arrayKeyExistsParameter(menusDAO::nome))$params[menusDAO::nome]=getParameter(menusDAO::nome);
	        if(arrayKeyExistsParameter(menusDAO::tema))$params[menusDAO::tema]=getParameter(menusDAO::tema);
	        if(issetParameter(menusDAO::ocultar))$params[menusDAO::ocultar]=getParameter(menusDAO::ocultar);
			parent::__construct(new menusDAO($params)); 
			$this->settingsImagesBase64=[
				menusDAO::icone=>["path"=>"menu","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
			];
		}
	}
?>