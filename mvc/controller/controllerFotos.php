<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/fotosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerFotos extends controller
    {
        public function save(){
            echo json_encode(
                parent::saveBase64(function(){
                    return parent::save();
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

        public function update($id){
            $this->model->setParam(fotosDAO::foto,"");

            $result = parent::findById($id);

            foreach ($this->settingsImagesBase64 as $key => $value){
                if(count($result["elements"]) > 0){
                    $file_name = $result["elements"][0][$key] ?? null;
                    if(!empty($file_name)){
                        deleteUploadRedimencion(
                            $file_name,
                            $this->settingsImagesBase64[$key]["path"],
                            $this->settingsImagesBase64[$key]["formats"]
                        );
                    }
                }
            }

            echo json_encode(
                parent::saveBase64(function() use ($id){
                    return parent::update($id);
                })
            );
        }

        public function del($id){
            $result = parent::findById($id);

            foreach ($this->settingsImagesBase64 as $key => $value){
                if(count($result["elements"]) > 0){
                    $file_name = $result["elements"][0][$key] ?? null;
                    if(!empty($file_name)){
                        deleteUploadRedimencion(
                            $file_name,
                            $this->settingsImagesBase64[$key]["path"],
                            $this->settingsImagesBase64[$key]["formats"]
                        );
                    }
                }
            }

            echo json_encode(parent::del($id));
        }

        public function find(){
			$this->model->addField(" album_fotos.nome as album  ");
			$this->model->setJoins(
				" left join album_fotos on(".fotosDAO::table.".".fotosDAO::idAlbum."=album_fotos.id) "
			);
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::find());
        }

        public function findById($id){
			$this->model->addField(" album_fotos.nome as album ");
			$this->model->setJoins(
				" left join album_fotos on(".fotosDAO::table.".".fotosDAO::idAlbum."=album_fotos.id) "
			);
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::findById($id));
        }

        public function __construct(){
            $params = [];

            if(notEmptyParameter(fotosDAO::id))
                $params[fotosDAO::id] = getParameter(fotosDAO::id);

            if(notEmptyParameter(fotosDAO::idAlbum))
                $params[fotosDAO::idAlbum] = getParameter(fotosDAO::idAlbum);

            if(arrayKeyExistsParameter(fotosDAO::nome))
                $params[fotosDAO::nome] = trim(getParameter(fotosDAO::nome)?? '');

            if(issetParameter(fotosDAO::ocultar))
                $params[fotosDAO::ocultar] = getParameter(fotosDAO::ocultar);

            parent::__construct(new fotosDAO($params));

            $this->settingsImagesBase64 = [
                fotosDAO::foto => [
                    "path"    => "album",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>