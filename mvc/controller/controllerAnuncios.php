<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerAnuncios extends controller
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
			$this->model->setParam(anunciosDAO::foto,"");
			$this->model->setParam(anunciosDAO::fotoMobile,"");
			$this->model->setParam(anunciosDAO::fotoExpandida,"");
			$this->model->setParam(anunciosDAO::fotoMobileExpandida,"");
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
			$this->model->addField(" menus.nome as menu ");
			$this->model->addField(" tipos_anuncios.nome as tipo ");
			$this->model->setJoins(
				" left join menus on(".anunciosDAO::table.".".anunciosDAO::idMenu."=menus.id) "
				.
				" left join tipos_anuncios on(".anunciosDAO::table.".".anunciosDAO::idTipoAnuncio."=tipos_anuncios.id) "
			);
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
			$this->settingsImagesBase64=[
				anunciosDAO::foto=>["path"=>"anuncio","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				anunciosDAO::fotoMobile=>["path"=>"anuncio","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				anunciosDAO::fotoExpandida=>["path"=>"anuncio_expandido","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"],
				anunciosDAO::fotoMobileExpandida=>["path"=>"anuncio_expandido","formats"=>"160x120,320x240,480x640,800x600,1024x768,1366x768"]
			];
		}
	}
?>