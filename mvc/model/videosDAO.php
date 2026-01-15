<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class videosDAO extends model
{
	public const table="videos";
	const idAlbum ="id_album";
	const nome="nome";
	const video="video";
	const ocultar="ocultar";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idAlbum,self::nome,self::video,self::ocultar],"",null,null,null,null);
    }
}
  
?>