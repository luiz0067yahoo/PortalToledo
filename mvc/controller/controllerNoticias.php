<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerNoticias extends controller
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

        /* =========================
           Métodos específicos
        ========================= */

        public function findSlideShow($menuSubMenu){
            echo json_encode(
                $this->model->findSlideShow($menuSubMenu)["elements"]
            );
        }

        public function findHome(){
            $page = getParameter("page");
            if(!(intval($page) >= 0)) $page = 0;

            echo json_encode(
                $this->model->findHome($page)
            );
        }

        public function findMenu($menuSubMenu){
            $page = getParameter("page");
            if(!(intval($page) >= 0)) $page = 0;

            echo json_encode(
                $this->model->findMenu($menuSubMenu, $page, 27)["elements"]
            );
        }

        public function __construct(){
            $params = [];

            if(notEmptyParameter(noticiasDAO::id))
                $params[noticiasDAO::id] = getParameter(noticiasDAO::id);

            if(notEmptyParameter(noticiasDAO::idMenu))
                $params[noticiasDAO::idMenu] = getParameter(noticiasDAO::idMenu);

            if(issetParameter(noticiasDAO::titulo))
                $params[noticiasDAO::titulo] = trim(getParameter(noticiasDAO::titulo));

            if(arrayKeyExistsParameter(noticiasDAO::subtitulo))
                $params[noticiasDAO::subtitulo] = getParameter(noticiasDAO::subtitulo);

            if(arrayKeyExistsParameter(noticiasDAO::conteudoNoticia))
                $params[noticiasDAO::conteudoNoticia] = getParameter(noticiasDAO::conteudoNoticia);

            if(arrayKeyExistsParameter(noticiasDAO::fonte))
                $params[noticiasDAO::fonte] = getParameter(noticiasDAO::fonte);

            if(notEmptyParameter(noticiasDAO::acesso))
                $params[noticiasDAO::acesso] = getParameter(noticiasDAO::acesso);

            if(issetParameter(noticiasDAO::slideShow))
                $params[noticiasDAO::slideShow] = getParameter(noticiasDAO::slideShow);

            if(issetParameter(noticiasDAO::ocultar))
                $params[noticiasDAO::ocultar] = getParameter(noticiasDAO::ocultar);

            parent::__construct(new noticiasDAO($params));

            $this->settingsImagesBase64 = [
                noticiasDAO::fotoPrincipal => [
                    "path"    => "noticias",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>