<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class albumFotosDAO extends model
{
	const table="album_fotos";
	const id_menu="id_menu";
	const nome="nome";
	const slide_show="slide_show";
	const ocultar="ocultar";
	public function findSlideShow($menuSubMenu){
	    $this->cleanParams();
	    $this->setParam(self::slide_show,true);
	    return $this->findMenuAlbum($menuSubMenu,0,6);
	}
	
	public function findMenuAlbum($menuSubMenu,$page,$rowCount){
            $menu=explode("/",$menuSubMenu)[0];
            $sub_menu=(count(explode("/",$menuSubMenu))>1)?explode("/",$menuSubMenu)[1]:"";
            $album=(count(explode("/",$menuSubMenu))>2)?explode("/",$menuSubMenu)[2]:"";
            $menu=urldecode($menu);
            $sub_menu=urldecode($sub_menu);
            if($album=="")
                $album=$sub_menu;
            $this->setFields([
                "IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(album_fotos.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',album_fotos.nome)) as url"
                ,"IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal"
            ]);
            if($album!=''){
                $this->addField("fotos.foto");
                $this->addField("fotos.nome");
            }
            else{
                $this->addField(albumFotosDAO::nome);
                $this->addField(
                     " album_fotos.nome "
                );
                $this->addField(
                     " (select fotos.foto from fotos where(fotos.id_album=album_fotos.id) order by fotos.id desc limit 0,1) as foto "
                );
            }
            $join="";
            if($album!=''){
                $join=" left join fotos on(fotos.id_album=album_fotos.id) ";
            }
            $this->setJoins(
                $join
                ." left join menus filho on(filho.id=album_fotos.id_menu) "
                ." left join menus pai on (pai.id=filho.id_menu) "
            );
            //$this->cleanParams();
            
            $this->setParams($this->getParams()+[
                "and"=>[
                    albumFotosDAO::ocultar=>false
                    ,"pai.id_menu"=>null
                    ,"or"=>[
                        ["and"=>[
                            "or"=>[
                                "filho.nome"=>[":menu",$menu]
                                ,"pai.nome"=>[":menu",$menu]
                            ]
                            ,"('')"=>[":sub_menu",$sub_menu]
                        ]]
                        ,["and"=>[
                            "pai.nome"=>[":menu",$menu]
                            ,"filho.nome"=>[":sub_menu",$sub_menu]
                            ,"('')"=>["!="=>":menu",$menu]
                            ,"('')"=>["!="=>":sub_menu",$sub_menu]
                        ]]
                        ,["and"=>[
                            "album_fotos.nome"=>[":categoria",$album]
                            ,"('')"=>["!="=>":categoria",$album]
                        ]]

                        ,"(:sub_menu)"=>[":menu",$menu]
                    ]
                    
                ]
            ]);
            
            $this->setOrders([self::id=>"desc"]);
            $this->setPage($page);
            $this->setRowCount($rowCount);
            return $this->find();		
    }
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_menu,self::nome,self::ocultar]);
    }
}
?>