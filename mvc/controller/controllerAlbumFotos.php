<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/mvc/model/albumFotosDAO.php');
	require_once ($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

	class controllerAlbumFotos extends controller
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
			$this->model->setOrders([$this->model::id => "DESC"]);
			echo json_encode(parent::find());
		}

		public function findById($id){
			echo json_encode(parent::findById($id));
		}

		public function findSlideShow($menuSubMenu){
			echo json_encode(
				$this->model->findSlideShow($menuSubMenu)["elements"]
			);
		}

		public function findMenuAlbum($menuSubMenu){
			$page = getParameter("page");

			if (!(intval($page) >= 0)){
				$page = 0;
			}

			echo json_encode(
				$this->model->findMenuAlbum($menuSubMenu, $page, 27)["elements"]
			);
		}

		public function __construct(){
			$params = [];

			if (notEmptyParameter(albumFotosDAO::id))
				$params[albumFotosDAO::id] = getParameter(albumFotosDAO::id);

			if (notEmptyParameter(albumFotosDAO::id_menu))
				$params[albumFotosDAO::id_menu] = getParameter(albumFotosDAO::id_menu);

			if (arrayKeyExistsParameter(albumFotosDAO::nome))
				$params[albumFotosDAO::nome] = trim(getParameter(albumFotosDAO::nome));

			if (issetParameter(albumFotosDAO::ocultar))
				$params[albumFotosDAO::ocultar] = getParameter(albumFotosDAO::ocultar) == "true";

			parent::__construct(new albumFotosDAO($params));

		}
	}
?>