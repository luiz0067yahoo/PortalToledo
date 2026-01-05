<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
class albumVideosDAO extends model
{
	const table="album_videos";
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
                "IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(album_videos.nome)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',album_videos.nome)) as url"
                ,"IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal"
            ]);
            if($album!=''){
                $this->addField("videos.video");
                $this->addField("videos.nome");
            }
            else{
                $this->addField(albumVideosDAO::nome);
                $this->addField(
                     " album_videos.nome "
                );
                $this->addField(
                     " (select videos.video from videos where(videos.id_album=album_videos.id) order by videos.id desc limit 0,1) as video "
                );
            }
            $join="";
            if($album!=''){
                $join=" left join videos on(videos.id_album=album_videos.id) ";
            }
            $this->setJoins(
                $join
                ." left join menus filho on(filho.id=album_videos.id_menu) "
                ." left join menus pai on (pai.id=filho.id_menu) "
            );
            $this->setParams($this->getParams()+[
                "and"=>[
                    albumVideosDAO::ocultar=>false
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
                            "album_videos.nome"=>[":categoria",$album]
                            ,"('')"=>["!="=>":categoria",$album]
                        ]]

                        ,"(:sub_menu)"=>[":menu",$menu]
                    ]
                    
                ]
            ]);
            $this->setOrders([noticiasDAO::id=>"desc"]);
            $this->setPage($page);
            $this->setRowCount($rowCount);
            return (parent::find());		
        }
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_menu,self::nome,self::ocultar]);
    }
}
?>