<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosFotosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerAnunciosFotos extends controller
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
            $this->model->setOrders([$this->model::id => "DESC"]);
            echo json_encode(parent::find());
        }

        public function findById($id){
            echo json_encode(parent::findById($id));
        }

        public function __construct(){
            $params = [];

            if(notEmptyParameter(anunciosFotosDAO::id))
                $params[anunciosFotosDAO::id] = getParameter(anunciosFotosDAO::id);

            if(notEmptyParameter(anunciosFotosDAO::id_anuncio))
                $params[anunciosFotosDAO::id_anuncio] = getParameter(anunciosFotosDAO::id_anuncio);

            if(arrayKeyExistsParameter(anunciosFotosDAO::nome))
                $params[anunciosFotosDAO::nome] = getParameter(anunciosFotosDAO::nome);

            if(issetParameter(anunciosFotosDAO::ocultar))
                $params[anunciosFotosDAO::ocultar] = getParameter(anunciosFotosDAO::ocultar);

            parent::__construct(new anunciosFotosDAO($params));

            $this->settingsImagesBase64 = [
                anunciosFotosDAO::foto => [
                    "path"    => "anuncios",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>