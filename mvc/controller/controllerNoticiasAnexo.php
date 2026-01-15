<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasAnexosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerNoticiasAnexos extends controller
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

            if(notEmptyParameter(noticiasAnexosDAO::id))
                $params[noticiasAnexosDAO::id] = getParameter(noticiasAnexosDAO::id);

            if(notEmptyParameter(noticiasAnexosDAO::id_noticia))
                $params[noticiasAnexosDAO::id_noticia] = getParameter(noticiasAnexosDAO::id_noticia);

            if(issetParameter(noticiasAnexosDAO::titulo))
                $params[noticiasAnexosDAO::titulo] = getParameter(noticiasAnexosDAO::titulo);

            if(arrayKeyExistsParameter(noticiasAnexosDAO::subtitulo))
                $params[noticiasAnexosDAO::subtitulo] = getParameter(noticiasAnexosDAO::subtitulo);

            if(arrayKeyExistsParameter(noticiasAnexosDAO::conteudo_noticia))
                $params[noticiasAnexosDAO::conteudo_noticia] = getParameter(noticiasAnexosDAO::conteudo_noticia);

            if(arrayKeyExistsParameter(noticiasAnexosDAO::fonte))
                $params[noticiasAnexosDAO::fonte] = getParameter(noticiasAnexosDAO::fonte);

            if(notEmptyParameter(noticiasAnexosDAO::acesso))
                $params[noticiasAnexosDAO::acesso] = getParameter(noticiasAnexosDAO::acesso);

            if(issetParameter(noticiasAnexosDAO::slide_show))
                $params[noticiasAnexosDAO::slide_show] = getParameter(noticiasAnexosDAO::slide_show);

            if(issetParameter(noticiasAnexosDAO::ocultar))
                $params[noticiasAnexosDAO::ocultar] = getParameter(noticiasAnexosDAO::ocultar);

            parent::__construct(new noticiasAnexosDAO($params));

            $this->settingsImagesBase64 = [
                noticiasAnexosDAO::foto_principal => [
                    "path"    => "noticias",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>