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
	$id_noticia=getParameter("id_noticia");
	$foto_principal ="";
	
	$titulo=trim(getParameter("titulo"));
	$subtitulo=getParameter("subtitulo");
	$conteudo_noticia_anexo=getParameter("conteudo_noticia_anexo");
	$fonte=getParameter("fonte");
	$ocultar=(getParameter("titulo")=="true");
	$select="select n.id,n.id_noticia,m.titulo as noticia,n.foto_principal,n.titulo,n.subtitulo,n.conteudo_noticia_anexo,n.fonte,n.ocultar from noticias_anexos n left join noticias m on (m.id=n.id_noticia) where (true)";
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"id_noticia"=>array(
			"nome"=>"Código noticia"
			,"tipo"=>"integer fk"
		)
		,"noticia"=>array(
			"nome"=>"noticia"
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
		,"conteudo_noticia_anexo"=>array(
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
			$sql="INSERT INTO noticias_anexos (id_noticia,foto_principal,titulo,subtitulo,conteudo_noticia_anexo,fonte,ocultar) VALUES (:id_noticia,:foto_principal, :titulo, :subtitulo, :conteudo_noticia_anexo, :fonte, :ocultar )";
		
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_noticia", $id_noticia);
				$my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia_anexo", $conteudo_noticia_anexo);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (n.id=(select max(id) from noticias_anexos)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticias_anexos cadastrado com sucesso!"
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
			$sql="update noticias_anexos set id_noticia=:id_noticia,foto_principal=:foto_principal,titulo=:titulo,subtitulo=:subtitulo,conteudo_noticia_anexo=:conteudo_noticia_anexo,fonte=:fonte,ocultar=:ocultar where(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_noticia", $id_noticia);
				$my_Insert_Statement->bindParam(":foto_principal", $foto_principal);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia_anexo", $conteudo_noticia_anexo);
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
							$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"noticias_anexos atualizado com sucesso!"
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
		$sql="delete from noticias_anexos  where (noticias_anexos.id=:id)";
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
						$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"noticias_anexos excluído com sucesso!"
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
				$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
		$sql=$sql."((:conteudo_noticia_anexo ='')or(n.conteudo_noticia_anexo=:conteudo_noticia_anexo))";
		$sql=$sql."and";		
		$sql=$sql."((:fonte ='')or(n.fonte=:fonte))";
		$sql=$sql."and";		
		$sql=$sql."((:ocultar = '')or(n.ocultar=:ocultar))";
		$sql=$sql."and";
		$sql=$sql."((:id_noticia = '')or(n.id_noticia=:id_noticia))  order by n.id desc limit 0,100 ";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_noticia", $id_noticia);
				$my_Insert_Statement->bindParam(":titulo", $titulo);
				$my_Insert_Statement->bindParam(":subtitulo", $subtitulo);
				$my_Insert_Statement->bindParam(":conteudo_noticia_anexo", $conteudo_noticia_anexo);
				$my_Insert_Statement->bindParam(":fonte", $fonte);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
					$linha = array($resultado['id'],$resultado['id_noticia'],$resultado['noticia'],$resultado['foto_principal'],$resultado['titulo'],$resultado['subtitulo'],$resultado['conteudo_noticia_anexo'], $resultado['fonte'],$resultado['ocultar']);
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
		$modelo="noticias";
		$sql="select $chave,$valor from $modelo where id_noticia is null ";
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
				    $sql_filho="select $chave,$valor from $modelo where id_noticia = 0".$resultado[$chave];
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