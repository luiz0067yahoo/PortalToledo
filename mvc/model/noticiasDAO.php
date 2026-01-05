<?
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/model.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/menusDAO.php');
class noticiasDAO extends model
{
	const table="noticias";
	const id_menu="id_menu";
	const foto_principal="foto_principal";
	const titulo="titulo";
	const subtitulo="subtitulo";
	const conteudo_noticia="conteudo_noticia";
	const fonte="fonte";
	const slide_show="slide_show";
	const acesso="acesso";
	const ocultar="ocultar";
	public function findSlideShow($menuSubMenu){
            $menu=explode("/",$menuSubMenu)[0];
            $subMenu=(count(explode("/",$menuSubMenu))>1)?explode("/",$menuSubMenu)[1]:"";
            $menu=urldecode($menu);
            $subMenu=urldecode($subMenu);
            $this->setFields([
                noticiasDAO::id
                ,noticiasDAO::titulo
                ,noticiasDAO::foto_principal
                ,"IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url"
                ,"IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal"
            ]);
            $this->setJoins(
                " left join menus filho on(filho.id=noticias.id_menu) "
                ." left join menus pai on (pai.id=filho.id_menu) "
            );
            $this->cleanParams();
            $this->setParams([
                "and"=>[
                    noticiasDAO::slide_show=>true
                    ,noticiasDAO::ocultar=>false
                    ,"pai.id_menu"=>null
                    ,"or"=>[
                        ["and"=>[
                            "or"=>[
                                "filho.nome"=>[":menu",$menu]
                                ,"pai.nome"=>[":menu",$menu]
                            ]
                            ,"('')"=>[":sub_menu",$subMenu]
                        ]]
                        ,["and"=>[
                            "pai.nome"=>[":menu",$menu]
                            ,"filho.nome"=>[":sub_menu",$subMenu]
                            ,"('')"=>["!="=>":menu",$menu]
                            ,"('')"=>["!="=>":sub_menu",$subMenu]
                        ]]
                        ,"(:sub_menu)"=>[":menu",$menu]
                    ]
                ]
            ]);
            $this->setOrders([noticiasDAO::id=>"desc"]);
            $this->setPage(0);
            $this->setRowCount(6);
            return(parent::find());		
        }
    public function findHome($page){
        $menusMain=new menusDAO([]);
        $menus=$menusMain->findMainMenusOnlyName()["data"];
        $news=[];
        for ($i=0;$i<6;$i++){
            $menuSubMenu=$menus[$i][0];
            $data=[];
            $result=$this->findMenu($menuSubMenu,$page,3);
            if(isset($result["data"])){
                $data=$result["data"];
            }
            $news[$i]=$data;
        }
        for ($i=0;$i<3;$i++){
            $menuSubMenu=$menus[$i][0];
            $data=[];
            $result=$this->findMenu($menuSubMenu,$page+1,3);
            if(isset($result["data"])){
                $data=$result["data"];
            }
            $news[count($news)]=$data;
        }
        $resultNews=$news;
        $resultNews=[];
        for ($i=0;$i<count($news);$i++)
        {
            for ($j=0;$j<3;$j++){
                if(isset($news[$i][$j]))
                    $resultNews[$j][$i]=$news[$i][$j];
            }
        }
        return($resultNews);
    }
    
    public function findMenu($menuSubMenu,$page,$rowCount){
        $tituloNoticias="";
        $menu=explode("/",$menuSubMenu)[0];
        $subMenu=(count(explode("/",$menuSubMenu))>1)?explode("/",$menuSubMenu)[1]:"";
        $titulo=(count(explode("/",$menuSubMenu))>2)?explode("/",$menuSubMenu)[2]:"";
        if(($titulo=="")&&(substr($menuSubMenu, -1)!="/"))
            $titulo=$subMenu;
        $menu=urldecode($menu);
        $subMenu=urldecode($subMenu);
        $this->setFields([
            noticiasDAO::id
            ,noticiasDAO::titulo
            ,noticiasDAO::subtitulo
            ,noticiasDAO::foto_principal
            ," concat(CAST(noticias.conteudo_noticia AS CHAR(20) CHARACTER SET utf8),' ...') as intro"
            ,"IF(filho.id_menu is null, concat(convertUrl(filho.nome),'/',convertUrl(noticias.titulo)), concat(convertUrl(pai.nome),'/',convertUrl(filho.nome),'/',convertUrl(noticias.titulo))) as url"
            ,"IF(filho.id_menu is null,filho.nome,pai.nome) as menu_principal"
        ]);
        if($titulo!=""){
            $this->addField(noticiasDAO::conteudo_noticia);
            $this->addField(noticiasDAO::fonte);
        }
        $this->setJoins(
            " left join menus filho on(filho.id=noticias.id_menu) "
            ." left join menus pai on (pai.id=filho.id_menu) "
        );
        $this->cleanParams();
        $this->setParams([
            "and"=>[
                noticiasDAO::ocultar=>false
                ,"pai.id_menu"=>null
                ,"or"=>[
                    ["and"=>[
                        "or"=>[
                            "filho.nome"=>[":menu",$menu]
                            ,"pai.nome"=>[":menu",$menu]
                        ]
                        ,"('')"=>[":sub_menu",$subMenu]
                    ]]
                    ,["and"=>[
                        "pai.nome"=>[":menu",$menu]
                        ,"filho.nome"=>[":sub_menu",$subMenu]
                        ,"('')"=>["!="=>":menu",$menu]
                        ,"('')"=>["!="=>":sub_menu",$subMenu]
                    ]]
                    ,["and"=>[
                        noticiasDAO::titulo=>$titulo
                        ,"('')"=>["!="=>":titulo",$titulo]
                    ]]
                ]
            ]
        ]);
        $this->setOrders([noticiasDAO::id=>"desc"]);
        $this->setPage($page);
        $this->setRowCount($rowCount);
        return $this->find();		
    }
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_menu,self::foto_principal,self::titulo,self::subtitulo,self::conteudo_noticia,self::fonte,self::acesso,self::slide_show,self::ocultar]);
    }
}
  
?>