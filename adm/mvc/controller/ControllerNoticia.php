<?php
    session_start();
	if (!(isset($_SESSION["id"]))) exit();
    include('../conecta.php');
    include '../verifica.php';
	$acao=isset($_POST["acao"])?$_POST["acao"]:(isset($_GET["acao"])?$_GET["acao"]:"");
	$chave=isset($_POST["chave"])?$_POST["chave"]:(isset($_GET["chave"])?$_GET["chave"]:"");
	$chave=BlockSQLInjection($chave);
	$campo=isset($_POST["campo"])?$_POST["campo"]:(isset($_GET["campo"])?$_GET["campo"]:"");
	$campo=BlockSQLInjection($campo);
	$valor=isset($_POST["valor"])?$_POST["valor"]:(isset($_GET["valor"])?$_GET["valor"]:"");
	$valor=BlockSQLInjection($valor);
	$modelo=isset($_POST["modelo"])?$_POST["modelo"]:(isset($_GET["modelo"])?$_GET["modelo"]:"");
	$modelo=BlockSQLInjection($modelo);
	$id=isset($_POST["id"])?$_POST["id"]:(isset($_GET["id"])?$_GET["id"]:"");
	$id_menu=isset($_POST["id_menu"])?$_POST["id_menu"]:(isset($_GET["id_menu"])?$_GET["id_menu"]:"");
	$titulo=trim(isset($_POST["titulo"])?$_POST["titulo"]:(isset($_GET["titulo"])?$_GET["titulo"]:""));
	$subtitulo=isset($_POST["subtitulo"])?$_POST["subtitulo"]:(isset($_GET["subtitulo"])?$_GET["subtitulo"]:"");
	$conteudo_noticia=isset($_POST["conteudo_noticia"])?$_POST["conteudo_noticia"]:(isset($_GET["conteudo_noticia"])?$_GET["conteudo_noticia"]:"");
	$fonte=isset($_POST["fonte"])?$_POST["fonte"]:(isset($_GET["fonte"])?$_GET["fonte"]:"");
	$ocultar=isset($_POST["ocultar"])?$_POST["ocultar"]:(isset($_GET["ocultar"])?$_GET["ocultar"]:false);
		$ocultar=($ocultar=="true");
	$slide_show=isset($_POST["slide_show"])?$_POST["slide_show"]:(isset($_GET["slide_show"])?$_GET["slide_show"]:false);
		$slide_show=($slide_show=="true");
	$destaque=isset($_POST["destaque"])?$_POST["destaque"]:(isset($_GET["destaque"])?$_GET["destaque"]:false);
		$destaque=($destaque=="true");
	$select="select n.id,n.id_menu,m.nome as menu,n.titulo,n.subtitulo,n.conteudo_noticia,n.fonte,n.ocultar from noticia n left join menu m on (m.id=n.id_menu) where (true)";
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
		,"titulo"=>array(
			"nome"=>"TÍtulo"
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
		if($id==null){
			$sql="INSERT INTO noticia (id_menu,titulo,subtitulo,conteudo_noticia,fonte,ocultar,date_insert) VALUES (:id_menu, :titulo, :subtitulo, :conteudo_noticia, :fonte, :ocultar,:date_insert )";
			try { 
			    //(new DateTime($this->Data))->format('Y-m-d h');
			    $date_insert=date("Y-m-d h:i:s");
			    $date_update=date("Y-m-d h:i:s");
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":slide_show", $slide_show);
				$my_Insert_Statement->bindParam(":destaque", $destaque);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":date_insert", $date_insert);
				$my_Insert_Statement->bindParam(":date_update", $date_update);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (n.id=(select max(id) from noticia)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticia cadastrado com sucesso!"
							,"campos"=>$campos
							,"registros"=>$lista
						)
					);
					echo $json_saida;
				}
				catch (PDOException $error) {
				   echo 'Connection error: ' . $error->getMessage();
				}
			}
			catch (PDOException $error) {
			   //echo 'Connection error: ' . $error->getMessage();
			}
		}
		else{
			$sql="update noticia set id_menu=:id_menu,titulo=:titulo,subtitulo=:subtitulo,conteudo_noticia=:conteudo_noticia,fonte=:fonte,ocultar=:ocultar,date_update=:date_update where(id=:id)";
			try { 
			    $date_update=date("Y-m-d h:i:s");
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":slide_show", $slide_show);
				$my_Insert_Statement->bindParam(":destaque", $destaque);
				$my_Insert_Statement->bindParam(":date_insert", $date_insert);
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
						$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticia atualizado com sucesso!"
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
		$sql="delete from noticia  where (noticia.id=:id)";
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
					$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"noticia excluído com sucesso!"
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
		$sql="$select AND (a.$campo=:valor)";
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
				$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
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
		$sql=$sql."((:id_menu = '')or(n.id_menu=:id_menu))";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia", $conteudo_noticia);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
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
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha =  array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia'], $resultado['fonte'],$resultado['slide_show'],$resultado['destaque'],$resultado['ocultar']);
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
		$modelo="menu";
		$sql="select $chave,$valor from $modelo where $modelo.nome!='HOME'";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado[$chave],$resultado[$valor]);
				array_push($lista,$linha); 
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
	$my_Insert_Statement=null;
	$my_Db_Connection=null;
?>