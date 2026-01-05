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
	$nome=trim(isset($_POST["nome"])?$_POST["nome"]:(isset($_GET["nome"])?$_GET["nome"]:""));
	$ocultar=isset($_POST["ocultar"])?$_POST["ocultar"]:(isset($_GET["ocultar"])?$_GET["ocultar"]:false);
		$ocultar=($ocultar=="true");
	$select="select a.id,a.id_menu,m.nome as menu,a.nome,a.ocultar from album a left join menu m on (m.id=a.id_menu) where (m.nome!='HOME') AND (a.nome!='PRINCIPAL') AND (a.nome!='CARROSSEL1') AND (a.nome!='CARROSSEL2') AND (a.nome!='CARROSSEL3')";
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
		,"nome"=>array(
			"nome"=>"Nome"
			,"tipo"=>"varchar(50)"
		)
		,"ocultar"=>array(
			"nome"=>"Ocultar"
			,"tipo"=>"boolean"
		)
	);
	if($acao=="salvar"){
		if($id==null){
			$sql="INSERT INTO album (id_menu,nome,ocultar) VALUES (:id_menu, :nome, :ocultar )";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (a.id=(select max(id) from album)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'], $resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"album cadastrado com sucesso!"
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
			$sql="update album set id_menu=:id_menu,nome=:nome,ocultar=:ocultar where(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":id", $id);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$resultado=$my_Insert_Statement->fetch();	
				
				$sql="$select AND (a.id=:id) ";
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
						$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"album atualizado com sucesso!"
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
		$sql="delete from album  where (album.nome!='PRINCIPAL') AND (album.nome!='CARROSSEL1') AND (album.nome!='CARROSSEL2') AND (album.id=:id)";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $id);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			//$resultado=$my_Insert_Statement->fetch();	
			$sql="$select AND (a.id=:id) ";
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
					$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"album excluído com sucesso!"
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
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'],$resultado['ocultar']);
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
		$sql=$sql."((:id = '')or(a.id=:id))";
		$sql=$sql."and";
		$sql=$sql."((:nome ='')or(a.nome=:nome))";
		$sql=$sql."and";
		$sql=$sql."((:ocultar = '')or(a.ocultar=:ocultar))";
		$sql=$sql."and";
		$sql=$sql."((:id_menu = '')or(a.id_menu=:id_menu))";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'],$resultado['ocultar']);
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
				$linha = array($resultado['id'],$resultado['id_menu'],$resultado['menu'],$resultado['nome'],$resultado['ocultar']);
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