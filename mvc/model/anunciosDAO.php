<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class anunciosDAO extends model
{
	const table="anuncios";
	const idMenu="id_menu";
	const idTipoAnuncio="id_tipo_anuncio";
	const nome="nome";

	const foto="foto";
	const fotoMobile="foto_mobile";
	const fotoExpandida="foto_expandida";
	const fotoMobileExpandida="foto_mobile_expandida";

	const introducao="introducao";
	const introducao2="introducao2";
	const descricao="descricao";
	const facebook="facebook";
	const youtube="youtube";
	const instagram="instagram";
	const whatsapp="whatsapp";
	const endereco="endereco";
	const telefone="telefone";
	const e_mail="e_mail";
	const website="website";
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
		parent::__construct($model_attributes,self::table,[
        self::idMenu,
        self::idTipoAnuncio,
        self::nome,
        self::foto,
        self::fotoMobile,
        self::fotoExpandida,
        self::fotoMobileExpandida,
        self::introducao,
        self::introducao2,
        self::descricao,
        self::facebook,
        self::youtube,
        self::instagram,
        self::whatsapp,
        self::endereco,
        self::telefone,
        self::e_mail,
        self::website,
        self::url,
        self::ocultar
      ]);
    }
}
?>