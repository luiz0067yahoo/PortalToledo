<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/tiposAnunciosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerTiposAnuncios
	extends controller
	{
		public function save(){
			echo json_encode(parent::save());		
		}
		public function create(){
			echo json_encode(parent::create());		
		}
		public function update($id){
			echo json_encode(parent::update($id));		
		}
		public function del($id){
			echo json_encode(parent::del($id));		
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
	        if(notEmptyParameter(tiposAnunciosDAO::id))$params[tiposAnunciosDAO::id]=getParameter(tiposAnunciosDAO::id);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::nome))$params[tiposAnunciosDAO::nome]=getParameter(tiposAnunciosDAO::nome);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::altura))$params[tiposAnunciosDAO::altura]=getParameter(tiposAnunciosDAO::altura);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::largura))$params[tiposAnunciosDAO::largura]=getParameter(tiposAnunciosDAO::largura);
	        if(issetParameter(tiposAnunciosDAO::ocultar))$params[tiposAnunciosDAO::ocultar]=getParameter(tiposAnunciosDAO::ocultar)=="true";
			parent::__construct(new tiposAnunciosDAO($params));
		}
	}
?>
