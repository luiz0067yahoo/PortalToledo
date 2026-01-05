<?php 
    if (!function_exists("columnist")) {
        function columnist()
		{
?>
<div class=" mt-3 w-100 d-flex flex-wrap justify-content-center" style="background-color:transparent">
        <div class="row mt-3 w-100 d-flex justify-content-center">
			<div class="col-sm-<?php echo ($GLOBALS['ler_menu']!="Patrocinador")?12:9;  ?>">
				<h3><b>COLUNISTAS</b></h3>
			</div>
		</div>

<?php


    $icone="";
    $url="";

	
    $sql="SELECT menus.* FROM `menus` LEFT join menus pai on(pai.id=menus.id_menu) where(pai.nome=:nome_menu_pai)";
    $result_column=DAOquery($sql,array("nome_menu_pai"=>"Colunista"),true,"");
	$elements=$result_column["elements"];
    for($i=0;$i<count($elements);$i++){
		$element=$elements[$i];
        $menu_column_id=$element["id"];
        $icone=$element["icone"];
        $Nome=$element["nome"];
        $tema=$element["tema"];
        $descricao=$element["descricao"];
    

        try{
            {
            
            ?>
               
                <a  class="p-2 p-0 d-flex flex-wrap col-sm-3" style="text-decoration:none;color:#073763;" href="https://tooeste.com.br/ler/Colunista/<?php echo $Nome; ?>/" title="<?php echo $Nome; ?>">
                    <div class="w-25">
                        <?php if(isset($icone)){ ?><img src="<?php echo "https://$_SERVER[HTTP_HOST]";?>/uploads/menu/160x120/<?php echo $icone;?>" class="d-block rounded-circle w-100 square"  ><?php } ?>
                    </div>
                    <div class=" p-2 w-75">
                        <p class="w-100 h7 m-0"><?php echo $Nome;?></p>
                        <hr class="w-100 m-0">
                        <p class="w-100 h7"><b><?php echo $tema;?></b></p>
                        <?php
                            $result_last_news=DAOquerySelect("noticias",["titulo","subtitulo"],"",["noticias.ocultar"=>0,"noticias.id_menu"=>$menu_column_id],null,null,["noticias.id"=>"desc"],["page"=>1,"row_count"=>1]);
                            $elements_last_news=[];
                            $titulo="";
                            $subtitulo="";
                            if (isset($result_last_news["elements"])&& !empty($result_last_news["elements"])){
                        	    $elements_last_news=$result_last_news["elements"];
                        	    if(count($elements_last_news)>0){
                        	        $titulo=$elements_last_news[0]["titulo"];    
                        	        $subtitulo=$elements_last_news[0]["subtitulo"];    
                        	    }
                            }
                        ?>
                        <p class="w-100 h7"><?php echo $titulo ?></p>
                        <p class="w-100 h7"><?php echo $subtitulo ?></p>
                    </div>
                </a>
                  
               
            <?php             
            }
        }
        catch(Exception $e){

        }
    }
?>
 </div> 
<?php 
        }
}
?>