<?php
    require_once ($GLOBALS["base_server_path_files"].'/library/functions.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/model/menusDAO.php');
	require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controller.php');
	class controllerMenus 
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
        public function findMainMenus(){
            echo json_encode($this->model->findMainMenus()["elements"]);		
		}
		public function findSubMenus($id_menu){
		    echo json_encode($this->model->findSubMenus($id_menu)["elements"]);		
		}

	

		public function __construct(){
			$params=[];
	        if(notEmptyParameter(menusDAO::id))$params[menusDAO::id]=getParameter(menusDAO::id);
	        if(notEmptyParameter(menusDAO::id_menu))$params[menusDAO::id_menu]=getParameter(menusDAO::id_menu);
	        if(arrayKeyExistsParameter(menusDAO::nome))$params[menusDAO::nome]=getParameter(menusDAO::nome);
	        if(issetParameter(menusDAO::ocultar))$params[menusDAO::ocultar]=getParameter(menusDAO::ocultar);
			parent::__construct(new menusDAO($params)); 
		}
	}
?>