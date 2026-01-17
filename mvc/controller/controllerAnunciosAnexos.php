<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/anunciosAnexosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerAnunciosAnexos extends controller
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
            $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/explorer/anuncios_anexos/';
            $uploadPath = $uploadDir . $filename;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // Return public accessible URL
                $publicUrl = '/uploads/explorer/anuncios_anexos/' . $filename;   // ← adjust according to your domain/structure

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
            $this->model->setParam(anunciosAnexosDAO::fotoPrincipal,"");
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

            if(notEmptyParameter(anunciosAnexosDAO::id))
                $params[anunciosAnexosDAO::id] = getParameter(anunciosAnexosDAO::id);

            if(notEmptyParameter(anunciosAnexosDAO::idAnuncio))
                $params[anunciosAnexosDAO::idAnuncio] = getParameter(anunciosAnexosDAO::idAnuncio);

            if(issetParameter(anunciosAnexosDAO::titulo))
                $params[anunciosAnexosDAO::titulo] = trim(getParameter(anunciosAnexosDAO::titulo));

            if(arrayKeyExistsParameter(anunciosAnexosDAO::subtitulo))
                $params[anunciosAnexosDAO::subtitulo] = getParameter(anunciosAnexosDAO::subtitulo);

            if(arrayKeyExistsParameter(anunciosAnexosDAO::conteudoAnuncioAnexo))
                $params[anunciosAnexosDAO::conteudoAnuncioAnexo] = getParameter(anunciosAnexosDAO::conteudoAnuncioAnexo);

            if(arrayKeyExistsParameter(anunciosAnexosDAO::fonte))
                $params[anunciosAnexosDAO::fonte] = getParameter(anunciosAnexosDAO::fonte);

            if(notEmptyParameter(anunciosAnexosDAO::acesso))
                $params[anunciosAnexosDAO::acesso] = getParameter(anunciosAnexosDAO::acesso);

            if(issetParameter(anunciosAnexosDAO::ocultar))
                $params[anunciosAnexosDAO::ocultar] = getParameter(anunciosAnexosDAO::ocultar);


            parent::__construct(new anunciosAnexosDAO($params));
            
            $this->settingsImagesBase64 = [
                anunciosAnexosDAO::fotoPrincipal => [
                    "path"    => "anuncios_anexos",
                    "formats" => "160x120,320x240,480x640,800x600,1024x768,1366x768"
                ]
            ];
        }
    }
?>