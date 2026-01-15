<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/videosDAO.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');

    class controllerVideos extends controller
    {
        
        public function find()
        {
            $this->model->setOrders([$this->model::id => "DESC"]);
            echo json_encode(parent::find());
        }

        public function findById($id)
        {
            echo json_encode(parent::findById($id));
        }

        public function create()
        {
            echo json_encode(parent::create());
        }

        public function update($id)
        {
            echo json_encode(parent::update($id));
        }

        public function save()
        {
            echo json_encode(parent::save());
        }

        public function del($id)
        {
            echo json_encode(parent::del($id));
        }

        /* =========================
           CONSTRUTOR
        ========================= */

        public function __construct()
        {
            $params = [];

            if (notEmptyParameter(videosDAO::id))
                $params[videosDAO::id] = getParameter(videosDAO::id);

            if (notEmptyParameter(videosDAO::idAlbum))
                $params[videosDAO::idAlbum] = getParameter(videosDAO::idAlbum);

            if (arrayKeyExistsParameter(videosDAO::nome))
                $params[videosDAO::nome] = trim(getParameter(videosDAO::nome));

            if (arrayKeyExistsParameter(videosDAO::video))
                $params[videosDAO::video] = getParameter(videosDAO::video);

            if (issetParameter(videosDAO::ocultar))
                $params[videosDAO::ocultar] = getParameter(videosDAO::ocultar);

            parent::__construct(new videosDAO($params));
        }
    }
?>