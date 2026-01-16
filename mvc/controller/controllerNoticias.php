<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/noticiasDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerNoticias extends controller
    {

        public function quillUpload(){
            header('Content-Type: application/json');
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No file or upload error']);
                exit;
            }

            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize = 5 * 1024 * 1024; // 5MB

            if (!in_array($file['type'], $allowedTypes)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, GIF, WebP allowed']);
                exit;
            }

            if ($file['size'] > $maxSize) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
                exit;
            }

            // Generate unique name
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('img_') . '_' . time() . '.' . $extension;

            // Choose your folder (make sure it's writable!)
            $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/explorer/';
            $uploadPath = $uploadDir . $filename;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Return public accessible URL
                $publicUrl = '/uploads/explorer/' . $filename;   // ← adjust according to your domain/structure

                echo json_encode([
                    'success' => true,
                    'url'     => $publicUrl
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to save file']);
            }

        }

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
            $this->model->setParam(noticiasDAO::fotoPrincipal,"");
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

            if(issetParameter(noticiasDAO::destaque))
                $params[noticiasDAO::destaque] = getParameter(noticiasDAO::destaque);

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