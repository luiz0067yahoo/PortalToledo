<?
require_once($GLOBALS["base_server_path_files"].'/mvc/model/model.php');
class menusDAO extends model
{
	const table="menus";
	const id_menu="id_menu";
	const nome="nome";
	const ocultar="ocultar";
	public function findMainMenus(){
        $this->setFields([self::id,self::nome]);
	    $this->cleanParams();
        $this->setParam(self::id_menu,null);
        $this->setParam(self::nome,["!="=>"home"]);
        $this->setParam(self::ocultar,false);
        $this->setOrders([self::id=>"asc"]);
        return $this->find();		
    }
    public function findMainMenusOnlyName(){
        $this->setFields([self::nome]);
	    $this->cleanParams();
        $this->setParam(self::id_menu,null);
        $this->setParam(self::nome,["!="=>"home"]);
        $this->setParam(self::ocultar,false);
        $this->setOrders([self::id=>"asc"]);
        //$this->setPage(0);
        //$this->setRowCount(6);
        return $this->find();		
    }
	public function findSubMenus($id_menu){
        $this->setFields([self::nome]);
        $this->cleanParams();
        $this->setParam(self::id_menu,$id_menu);
        $this->setParam(self::ocultar,false);
        $this->setOrders([self::nome=>"asc"]);
        return $this->find();		
	}
    public function __construct($model_attributes){
		parent::__construct($model_attributes,self::table,[self::id_menu,self::nome,self::ocultar]);
    }
}
?>