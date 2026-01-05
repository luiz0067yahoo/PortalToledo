<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class videosDAO extends model
{
	public static $table="videos";
	public static $id_album="id_album";
	public static $nome="nome";
	public static $video="video";
	public static $ocultar="ocultar";
	//public static $hora_coleta="left join album_videos a on (a.id=f.id_album)";

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::$table,[self::$id_album,self::$nome,self::$video,self::$ocultar],"",null,null,null,null);
    }
}
  
?>