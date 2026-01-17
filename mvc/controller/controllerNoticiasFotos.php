<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasFotosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerNoticiasFotos extends controller
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
            $this->model->setParam(noticiasFotosDAO::foto,"");

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
			$this->model->addField(" noticias.titulo as noticia  ");
			$this->model->setJoins(
				" left join noticias on(".noticiasFotosDAO::table.".".noticiasFotosDAO::idNoticia."=noticias.id) "
			);
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::find());
        }

        public function findById($id){
			$this->model->addField(" noticias.nome as anuncio ");
			$this->model->setJoins(
				" left join noticias on(".noticiasFotosDAO::table.".".noticiasFotosDAO::idNoticia."=noticias.id) "
			);
			$this->model->setOrders([$this->model::id=>"DESC"]);
            echo json_encode(parent::findById($id));
        }

        public function __construct(){
            $params = [];

            if(notEmptyParameter(noticiasFotosDAO::id))
                $params[noticiasFotosDAO::id] = getParameter(noticiasFotosDAO::id);

            if(notEmptyParameter(noticiasFotosDAO::idNoticia))
                $params[noticiasFotosDAO::idNoticia] = getParameter(noticiasFotosDAO::idNoticia);

            if(arrayKeyExistsParameter(noticiasFotosDAO::nome))
                $params[noticiasFotosDAO::nome] = trim(getParameter(noticiasFotosDAO::nome)?? '');

            if(issetParameter(noticiasFotosDAO::ocultar))
                $params[noticiasFotosDAO::ocultar] = getParameter(noticiasFotosDAO::ocultar);

            parent::__construct(new noticiasFotosDAO($params));

            $this->settingsImagesBase64 = [
                noticiasFotosDAO::foto => [
                    "path"    => "noticias_fotos",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>