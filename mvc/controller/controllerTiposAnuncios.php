<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/tiposAnunciosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerTiposAnuncios
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

	

		public function __construct(){
	        $params=[];
	        if(emptyParameter(tiposAnunciosDAO::id))$params[tiposAnunciosDAO::id]=getParameter(tiposAnunciosDAO::id);
	        if(emptyParameter(tiposAnunciosDAO::id_menu))$params[tiposAnunciosDAO::id_menu]=getParameter(tiposAnunciosDAO::id_menu);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::nome))$params[tiposAnunciosDAO::nome]=getParameter(tiposAnunciosDAO::nome);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::altura))$params[tiposAnunciosDAO::altura]=getParameter(tiposAnunciosDAO::altura);
	        if(arrayKeyExistsParameter(tiposAnunciosDAO::largura))$params[tiposAnunciosDAO::largura]=getParameter(tiposAnunciosDAO::largura);
	        if(issetParameter(tiposAnunciosDAO::ocultar))$params[tiposAnunciosDAO::ocultar]=getParameter(tiposAnunciosDAO::ocultar);
			parent::__construct(new menusDAO($params));
		}
	}
?>
