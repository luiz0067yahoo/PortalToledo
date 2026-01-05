<?php



session_start();
	if (!(isset($_SESSION["id"]))) exit();

include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');

	$acao=getParameter("acao");
	$chave=BlockSQLInjection(getParameter("chave"));
	$campo=BlockSQLInjection(getParameter("campo"));
	$valor=BlockSQLInjection(getParameter("valor"));
	$modelo=BlockSQLInjection(getParameter("modelo"));
	
	$id=getParameter("id");
	$id_anuncio=getParameter("id_anuncio");
	$foto_principal ="";
	
	$titulo=trim(getParameter("titulo"));
	$subtitulo=getParameter("subtitulo");
	$conteudo_anuncio_anexo=getParameter("conteudo_anuncio_anexo");
	$fonte=getParameter("fonte");
	$ocultar=(getParameter("titulo")=="true");
	$select="select an.id,an.id_anuncio,a.nome as anuncio,an.foto_principal,an.titulo,an.subtitulo,an.conteudo_anuncio_anexo,an.fonte,an.ocultar from anuncios_anexos an left join anuncios a on (a.id=an.id_anuncio) where (true)";
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"id_anuncio"=>array(
			"nome"=>"Código anuncio"
			,"tipo"=>"integer fk"
		)
		,"anuncio"=>array(
			"nome"=>"anuncio"
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
		,"conteudo_anuncio_anexo"=>array(
			"nome"=>"Notícia"
			,"tipo"=>"blob"
		)
		,"fonte"=>array(
			"nome"=>"Fonte"
			,"tipo"=>"varchar(50)"
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
			$sql="INSERT INTO anuncios_anexos (id_anuncio,foto_principal,titulo,subtitulo,conteudo_anuncio_anexo,fonte,ocultar) VALUES (:id_anuncio,:foto_principal, :titulo, :subtitulo, :conteudo_anuncio_anexo, :fonte, :ocultar )";
		
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
				$my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_anuncio_anexo", $conteudo_anuncio_anexo);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (n.id=(select max(id) from anuncios_anexos)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"anuncios_anexos cadastrado com sucesso!"
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
			$sql="update anuncios_anexos set id_anuncio=:id_anuncio,foto_principal=:foto_principal,titulo=:titulo,subtitulo=:subtitulo,conteudo_anuncio_anexo=:conteudo_anuncio_anexo,fonte=:fonte,ocultar=:ocultar where(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
				$my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_anuncio_anexo", $conteudo_anuncio_anexo);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
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
							$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"anuncios_anexos atualizado com sucesso!"
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
		$sql="delete from anuncios_anexos  where (anuncios_anexos.id=:id)";
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
						$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"anuncios_anexos excluído com sucesso!"
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
		$sql="$select AND (n.$campo=:valor)  order by n.id desc limit 0,100";
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
				$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
		$sql=$sql."((:id = '')or(an.id=:id))";
		$sql=$sql."and";
		$sql=$sql."((:titulo ='')or(an.titulo=:titulo))";
		$sql=$sql."and";	
		$sql=$sql."((:subtitulo ='')or(an.subtitulo=:subtitulo))";
		$sql=$sql."and";
		$sql=$sql."((:conteudo_anuncio_anexo ='')or(an.conteudo_anuncio_anexo=:conteudo_anuncio_anexo))";
		$sql=$sql."and";		
		$sql=$sql."((:fonte ='')or(an.fonte=:fonte))";
		$sql=$sql."and";		
		$sql=$sql."((:ocultar = '')or(an.ocultar=:ocultar))";
		$sql=$sql."and";
		$sql=$sql."((:id_anuncio = '')or(an.id_anuncio=:id_anuncio))  order by an.id desc limit 0,100 ";
		try { 
    			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_anuncio_anexo", $conteudo_anuncio_anexo);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
					$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_anuncio_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
		$modelo="anuncios";
		$sql="select $chave,$valor from $modelo where id_anuncio is null ";
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
				    $sql_filho="select $chave,$valor from $modelo where id_anuncio = 0".$resultado[$chave];
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
	$my_Insert_Statement=null;
	$my_Db_Connection=null;
?>