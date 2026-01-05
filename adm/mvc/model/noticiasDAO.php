<?php
include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
 include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
 include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');  

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    date_default_timezone_set('America/Sao_Paulo');
    

	$acao=getParameter("acao");
	$chave=BlockSQLInjection(getParameter("chave"));
	$campo=BlockSQLInjection(getParameter("campo"));
	$valor=BlockSQLInjection(getParameter("valor"));
	$modelo=BlockSQLInjection(getParameter("modelo"));
	
	$id=getParameter("id");
	$id_menu=getParameter("id_menu");
	$foto_principal ="";
	
	$titulo=trim(getParameter("titulo"));
	$subtitulo=getParameter("subtitulo");
	$conteudo_noticia=getParameter("conteudo_noticia");
	$fonte=getParameter("fonte");
	$slide_show=(getParameter("slide_show")=="true");
	$ocultar=(getParameter("ocultar")=="true");
	$destaque=(getParameter("destaque")=="true");
	$select="select n.id,n.id_menu,m.nome as menu,n.foto_principal,n.titulo,n.subtitulo,n.conteudo_noticia,n.fonte,n.slide_show,n.destaque,n.ocultar from noticias n left join menus m on (m.id=n.id_menu) where (true)";
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"id_menu"=>array(
			"nome"=>"Código Menu"
			,"tipo"=>"integer fk"
		)
		,"menu"=>array(
			"nome"=>"Menu"
			,"tipo"=>"varchar(50)"
		)
		,"foto_principal"=>array(
			"nome"=>"Foto Principal"
			,"tipo"=>"file image"
		)
		,"titulo"=>array(
			"nome"=>"Título"
			,"tipo"=>"varchar(50)"
		)
		,"subtitulo"=>array(
			"nome"=>"Subitulo"
			,"tipo"=>"varchar(50)"
		)
		,"conteudo_noticia"=>array(
			"nome"=>"Notícia"
			,"tipo"=>"blob"
		)
		,"fonte"=>array(
			"nome"=>"Fonte"
			,"tipo"=>"varchar(50)"
		)
		,"slide_show"=>array(
			"nome"=>"Slide Show"
			,"tipo"=>"boolean"
		)
		,"destaque"=>array(
			"nome"=>"destaque"
			,"tipo"=>"boolean"
		)
		,"ocultar"=>array(
			"nome"=>"Ocultar"
			,"tipo"=>"boolean"
		)
	);
	if($acao=="salvar"){
    	
    	$foto_principal="";
    	
    	
     
    	$files_uploads =upload(null);
    	if(isset($files_uploads)){
    	    if(count($files_uploads["foto_principal"])>0){
    	        $foto_principal=$files_uploads["foto_principal"][0];
    	    }
    	}
    	
		if($id==""){
		    if($foto_principal==""){
			    $sql="INSERT INTO noticias (id_menu,titulo,subtitulo,conteudo_noticia,fonte,slide_show,destaque,ocultar,date_insert) VALUES (:id_menu,:titulo, :subtitulo, :conteudo_noticia, :fonte,:slide_show ,:destaque , :ocultar,:date_insert)";
		    }
		    else{
			    $sql="INSERT INTO noticias (id_menu,foto_principal,titulo,subtitulo,conteudo_noticia,fonte,slide_show,destaque,ocultar,date_insert) VALUES (:id_menu,:foto_principal, :titulo, :subtitulo, :conteudo_noticia, :fonte,:slide_show,:destaque, :ocultar,:date_insert )";
		    }
			try { 
		        $date_insert=date("Y-m-d H:i:s");
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				if($foto_principal!="")
				    $my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":slide_show", $slide_show);
				$my_Insert_Statement->bindParam(":destaque", $destaque);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":date_insert", $date_insert);

				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (n.id=(select max(id) from noticias)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticias cadastrado com sucesso!"
							,"campos"=>$campos
							,"registros"=>$lista
						)
					);
					echo $json_saida;
				}
				catch (PDOException $error) {
				   //echo 'Connection error: ' . $error->getMessage();
				}
			}
			catch (PDOException $error) {
			   //echo 'Connection error: ' . $error->getMessage();
			}
		}
		else{
		    if($foto_principal==""){
			    $sql="update noticias set id_menu=:id_menu,titulo=:titulo,subtitulo=:subtitulo,conteudo_noticia=:conteudo_noticia,fonte=:fonte,slide_show=:slide_show,destaque=:destaque,ocultar=:ocultar,date_update=:date_update where(id=:id)";
		    }
		    else{
			    $sql="update noticias set id_menu=:id_menu,foto_principal=:foto_principal,titulo=:titulo,subtitulo=:subtitulo,conteudo_noticia=:conteudo_noticia,fonte=:fonte,slide_show=:slide_show,destaque=:destaque,ocultar=:ocultar,date_update=:date_update where(id=:id)";
		    }
			try { 
			    $date_update=date("Y-m-d H:i:s");

				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				if($foto_principal!="")
				    $my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":slide_show", $slide_show);
				$my_Insert_Statement->bindParam(":destaque", $destaque);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":date_update", $date_update);
				
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$resultado=$my_Insert_Statement->fetch();	
				
				$sql="$select AND (n.id=:id) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					$my_Insert_Statement->bindParam(":id", $id);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticias atualizado com sucesso!"
							,"campos"=>$campos
							,"registros"=>$lista
						)
					);
					echo $json_saida;				
				}
				catch (PDOException $error) {
				   //echo 'Connection error: ' . $error->getMessage();
				}
			}
			catch (PDOException $error) {
			   //echo 'Connection error: ' . $error->getMessage();
			}
		}
	}
	else if($acao=="excluir"){
		$sql="delete from noticias  where (noticias.id=:id)";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $id);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			//$resultado=$my_Insert_Statement->fetch();	
			$sql="$select AND (n.id=:id) ";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}	
				$lista=array();
				while ($resultado=$my_Insert_Statement->fetch()) {
					$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"noticias excluído com sucesso!"
						,"campos"=>$campos
						,"registros"=>$lista
					)
				);
				echo $json_saida;				
			}
			catch (PDOException $error) {
			   //echo 'Connection error: ' . $error->getMessage();
			}			
		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
	}
	else if($acao=="buscarcampo"){
		$sql="$select AND (n.$campo=:valor)";
		$sql=$sql."  order by n.id desc   limit 0,100";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":valor", $valor);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}			
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
				array_push($lista,$linha); 
			}
			$numero_registros=count($lista);
			$json_saida = json_encode(
				array(
					"mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!"
					,"campos"=>$campos
					,"registros"=>$lista
				)
			);
			echo $json_saida;				


		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
	}
	else if($acao=="buscar"){
		$sql="$select AND";
		$sql=$sql."((:id = '')or(n.id=:id))";
		$sql=$sql."and";
		$sql=$sql."((:titulo ='')or(n.titulo=:titulo))";
		$sql=$sql."and";	
		$sql=$sql."((:subtitulo ='')or(n.subtitulo=:subtitulo))";
		$sql=$sql."and";
		$sql=$sql."((:conteudo_noticia ='')or(n.conteudo_noticia=:conteudo_noticia))";
		$sql=$sql."and";		
		$sql=$sql."((:fonte ='')or(n.fonte=:fonte))";
		$sql=$sql."and";		
		$sql=$sql."((:ocultar = '')or(n.ocultar=:ocultar))";
		$sql=$sql."and";		
		$sql=$sql."((:slide_show = '')or(n.slide_show=:slide_show))";
		$sql=$sql."and";
		$sql=$sql."((:id_menu = '')or(n.id_menu=:id_menu)) ";
		$sql=$sql."     order by n.id desc limit 0,100";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":slide_show", $slide_show);
				$my_Insert_Statement->bindParam(":destaque", $destaque);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
				array_push($lista,$linha); 
			}
			$numero_registros=count($lista);
			$json_saida = json_encode(
				array(
					"mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!"
					,"campos"=>$campos
					,"registros"=>$lista
				)
			);
			echo $json_saida;				
		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
	}	
	else if($acao=="buscartodos"){
		$sql="$select ";
		$sql=$sql." order by n.id desc";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
				array_push($lista,$linha); 
			}
			$numero_registros=count($linha);
			$json_saida = json_encode(
				array(
					"mensagem_sucesso"=>"foram encontrado $numero_registros registros na sua busca!"
					,"campos"=>$campos
					,"registros"=>$lista
				)
			);
			echo $json_saida;				
		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
	}	
	else if($acao=="selectAjax"){
		$modelo="menus";
		$sql="select $chave,$valor from $modelo where id_menu is null ";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			$linha =array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado[$chave],$resultado[$valor]);
				array_push($lista,$linha); 
				    $sql_filho="select $chave,$valor from $modelo where id_menu = 0".$resultado[$chave];
			        $my_Insert_Statement_filho = $my_Db_Connection->prepare($sql_filho);
		   			if ($my_Insert_Statement_filho->execute()) {
        			  //echo "New record created successfully";
        			} else {
        			  //echo "Unable to create record";
        			}	
                    while ($resultado_filho=$my_Insert_Statement_filho->fetch()){
                        $linha_filho = array($resultado_filho[$chave],$resultado[$valor]."->".$resultado_filho[$valor]);
				        array_push($lista,$linha_filho); 
                    } 
			}
			$json_saida = json_encode(
				array(
					"registros"=>$lista
				)
			);
			echo $json_saida;				
		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
	}
	else	var_dump($_GET);
	$my_Insert_Statement=null;
	$my_Db_Connection=null;
?>