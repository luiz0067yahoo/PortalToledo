<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosDAO extends model
{
	const table="anuncios";
	const idTipoAnuncio="id_tipo_anuncio";
	const nome="nome";
	const foto="foto";
	const fotoExpandida="foto_expandida";
	const introducao="introducao";
	const descricao="descricao";
	const url="url";
	const ocultar="ocultar";
    public function findbyType($nameType){
        $this->setFields([anunciosDAO::nome,anunciosDAO::foto,anunciosDAO::fotoExpandida,anunciosDAO::introducao,anunciosDAO::descricao,anunciosDAO::url]);
        $this->setJoins(" LEFT join tipos_anuncios on(anuncios.id_tipo_anuncio=tipos_anuncios.id)");
        $this->cleanParams();
        $this->setParams("tipos_anuncios.nome",$nameType);
        $this->setParams(anunciosDAO::ocultar,"false");
        $this->setOrders([anunciosDAO::id=>"asc"]);
        return parent::find();		
    }    

    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::idTipoAnuncio,self::nome,self::foto,self::fotoExpandida,self::introducao,self::descricao,self::url,self::ocultar]);
    }
}
?>