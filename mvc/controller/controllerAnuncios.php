<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAnuncios
	extends controller
	{
	    public function create(){
		    echo json_encode(upload("parent::create"));
		}
		public function update($id){
			parent::update($id);
		    echo json_encode(upload("parent::update"));
		}
		public function save(){
			parent::save();
            echo json_encode(upload("parent::save"));
        }		
        
		public function del($id){
		    $result=parent::findById($id);
		    foreach ($settingsImagesUpload as $key => $value){
    		    $file_name=resultDataFieldByTitle($result,$key,0);
    		    deleteUpload($file_name,$settingsImagesUpload[$key]["path"],$settingsImagesUpload[$key]["formats"]);
            }		
		    parent::del();
        }		
        public function find(){
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::find());		
		}
		public function findById($id){
			echo json_encode(parent::findById($id));
		}
        public function findbyType($nameType){
            echo json_encode($this->model->findbyType($nameType)["data"]);		
        }    
		public function __construct(){
		    $params=[];
	        if(notEmptyParameter(anunciosDAO::id))$params[anunciosDAO::id]=getParameter(anunciosDAO::id);
	        if(issetParameter(anunciosDAO::idMenu))$params[anunciosDAO::idMenu]=getParameter(anunciosDAO::idMenu);
	        if(issetParameter(anunciosDAO::idTipoAnuncio))$params[anunciosDAO::idTipoAnuncio]=getParameter(anunciosDAO::idTipoAnuncio);
	        if(issetParameter(anunciosDAO::nome))$params[anunciosDAO::nome]=trim(getParameter(anunciosDAO::nome));
	        if(issetParameter(anunciosDAO::introducao))$params[anunciosDAO::introducao]=getParameter(anunciosDAO::introducao);
	        if(issetParameter(anunciosDAO::introducao2))$params[anunciosDAO::introducao2]=getParameter(anunciosDAO::introducao2);
	        if(issetParameter(anunciosDAO::descricao))$params[anunciosDAO::descricao]=getParameter(anunciosDAO::descricao);
	        if(issetParameter(anunciosDAO::facebook))$params[anunciosDAO::facebook]=getParameter(anunciosDAO::facebook);
	        if(issetParameter(anunciosDAO::youtube))$params[anunciosDAO::youtube]=getParameter(anunciosDAO::youtube);
	        if(issetParameter(anunciosDAO::instagram))$params[anunciosDAO::instagram]=getParameter(anunciosDAO::instagram);
	        if(issetParameter(anunciosDAO::whatsapp))$params[anunciosDAO::whatsapp]=getParameter(anunciosDAO::whatsapp);
	        if(issetParameter(anunciosDAO::endereco))$params[anunciosDAO::endereco]=getParameter(anunciosDAO::endereco);
	        if(issetParameter(anunciosDAO::telefone))$params[anunciosDAO::telefone]=getParameter(anunciosDAO::telefone);
	        if(issetParameter(anunciosDAO::e_mail))$params[anunciosDAO::e_mail]=getParameter(anunciosDAO::e_mail);
	        if(issetParameter(anunciosDAO::website))$params[anunciosDAO::website]=getParameter(anunciosDAO::website);
	        if(issetParameter(anunciosDAO::url))$params[anunciosDAO::url]=getParameter(anunciosDAO::url);
	        if(arrayKeyExistsParameter(anunciosDAO::ocultar))$params[anunciosDAO::ocultar]=getParameter(anunciosDAO::ocultar);
			parent::__construct(new anunciosDAO($params));
			$this->settingsImagesUpload=[
				"foto"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				"fotoMobile"=>["path"=>"anuncios","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				"fotoexpandida"=>["path"=>"anuncio_expandido","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				"fotoMobileExpandida"=>["path"=>"anuncio_expandido","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]
			];
		}
	}
?>