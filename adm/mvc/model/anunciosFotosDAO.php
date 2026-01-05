<?php 
session_start();
if (!(isset($_SESSION["id"]))) exit();

include($_SERVER['DOCUMENT_ROOT'].'/adm/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/conecta.php');
include($_SERVER['DOCUMENT_ROOT'].'/adm/verifica.php');

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
	$id_anuncio=isset($_POST["id_anuncio"])?$_POST["id_anuncio"]:(isset($_GET["id_anuncio"])?$_GET["id_anuncio"]:"");
	$nome=isset($_POST["nome"])?$_POST["nome"]:(isset($_GET["nome"])?$_GET["nome"]:"");
	$ocultar=isset($_POST["ocultar"])?$_POST["ocultar"]:(isset($_GET["ocultar"])?$_GET["ocultar"]:false);
		$ocultar=($ocultar=="true");
	$select="select f.id,f.id_anuncio,a.nome as anuncio,f.nome,f.foto,f.ocultar from anuncios_fotos f left join anuncios a on (a.id=f.id_anuncio) ";
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
		,"nome"=>array(
			"nome"=>"Nome"
			,"tipo"=>"varchar(50)"
		)
		,"foto"=>array(
			"nome"=>"Foto"
			,"tipo"=>"file image"
		)
		,"ocultar"=>array(
			"nome"=>"Ocultar"
			,"tipo"=>"boolean"
		)
	);
	if($acao=="salvar"){
	    $files_uploads =upload(null);
    	$anuncios_fotos=$files_uploads["foto"];
		$lista=array();
		foreach ($anuncios_fotos as $foto){
			if($id==null){
				$sql="INSERT INTO anuncios_fotos (id_anuncio,nome,foto,ocultar) VALUES (:id_anuncio, :nome, :foto, :ocultar )";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
					$my_Insert_Statement->bindParam(":nome", $nome);
					$my_Insert_Statement->bindParam(":foto", $foto);
					$my_Insert_Statement->bindParam(":ocultar", $ocultar);
					try {
						if ($my_Insert_Statement->execute()) {
						} else {
						}
					}
					catch (PDOException $error) {
					   echo 'Connection error: ' . $error->getMessage();
					}
					$sql="$select where(f.id=(select max(id) from anuncios_fotos)) ";
					try { 
						$my_Insert_Statement = $my_Db_Connection->prepare($sql);
						if ($my_Insert_Statement->execute()) {
						  //echo "New record created successfully";
						} else {
						  //echo "Unable to create record";
						}	
						
						while ($resultado=$my_Insert_Statement->fetch()) {
							$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'], $resultado['ocultar']);
							array_push($lista,$linha); 
						}
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
				$sql="update anuncios_fotos set id_anuncio=:id_anuncio,nome=:nome, foto=:foto, ocultar=:ocultar where(id=:id)";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					$my_Insert_Statement->bindParam(":foto", $foto);
					$my_Insert_Statement->bindParam(":nome", $nome);
					$my_Insert_Statement->bindParam(":ocultar", $ocultar);
					$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
					$my_Insert_Statement->bindParam(":id", $id);
					if ($my_Insert_Statement->execute()) {
					} else {
					}
					$sql="$select where(f.id=:id) ";
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
							$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
							array_push($lista,$linha); 
						}
					}
					catch (PDOException $error) {
					}
				}
				catch (PDOException $error) {
				}
				break;
			}
		}
		$json_saida = json_encode(
			array(
				"mensagem_sucesso"=>"anuncios_fotos cadastrado com sucesso!"
				,"campos"=>$campos
				,"registros"=>$lista
			)
		);
		echo $json_saida;
		
	}
	else if($acao=="excluir"){
		$sql="delete from anuncios_fotos where(id=:id)";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $id);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			//$resultado=$my_Insert_Statement->fetch();	
			$sql="$select where(f.id=:id) ";
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
					$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"anuncios_fotos excluído com sucesso!"
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
		$sql="$select where(f.$campo=:valor)";
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
				$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
		$sql="$select where";
		$sql=$sql."((:id = '')or(f.id=:id))";
		$sql=$sql."and";
		$sql=$sql."((:nome ='')or(f.nome=:nome))";
		$sql=$sql."and";
		$sql=$sql."((:ocultar = '')or(f.ocultar=:ocultar))";
		$sql=$sql."and";
		$sql=$sql."((:id_anuncio = '')or(f.id_anuncio=:id_anuncio))";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_anuncio", $id_anuncio);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
				$linha = array($resultado['id'],$resultado['id_anuncio'],$resultado['anuncio'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
		$modelo="anuncio";
		$sql="select $chave,$valor from $modelo ";
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