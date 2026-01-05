<?php 
    session_start();
	if (!(isset($_SESSION["id"]))) exit();
    include('../conecta.php');
    include '../verifica.php';
    
	$acao=getParamenter("acao");
	$chave=BlockSQLInjection(getParamenter("chave"));
	$campo=BlockSQLInjection(getParamenter("campo"));
	$valor=BlockSQLInjection(getParamenter("valor"));
	$modelo=BlockSQLInjection(getParamenter("modelo"));
	$id=getParamenter("id");
	$nome=getParamenter("nome");
	$login=getParamenter("login");
	$senha=getParamenter("senha");
	$select="select u.id, u.nome, u.login, u.senha from usuario u where (true) ";
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"nome"=>array(
			"nome"=>"Nome"
			,"tipo"=>"varchar(50)"
		)
		,"login"=>array(
			"nome"=>"Login"
			,"tipo"=>"varchar(50)"
		)
		,"senha"=>array(
			"nome"=>"Senha"
			,"tipo"=>"varchar(50)"
		)
	
	);
	if($acao=="salvar"){
		if($id==null){
			$sql="INSERT INTO usuario (nome,login,senha) VALUES (:nome, :login, :senha)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":login", $login);
				$my_Insert_Statement->bindParam(":senha", $senha);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (u.id=(select max(id) from usuario)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"Usuário cadastrado com sucesso!"
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
			$sql="update usuario set nome=:nome,login=:login,senha=:senhawhere(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":login", $login);
				$my_Insert_Statement->bindParam(":senha", $senha);
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
                        $linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
						array_push($lista,$linha); 
					}
					$json_saida = json_encode(
						array(
							"mensagem_sucesso"=>"Usuário atualizado com sucesso!"
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
		$sql="delete from usuario  where (usuario.id=:id)";
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
                    $linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"Usuário excluído com sucesso!"
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
		$sql="$select AND (u.$campo=:valor)";
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
                $linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
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
		$sql="$select and";
		$sql=$sql."((:id = '')or(u.id=:id))";
		$sql=$sql."and";
		$sql=$sql."((:nome ='')or(u.nome=:nome))";
		$sql=$sql."and";	
		$sql=$sql."((:login ='')or(u.login=:login))";
		$sql=$sql."and";
		$sql=$sql."((:senha ='')or(u.senha=:senha))";
		
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":login", $login);
				$my_Insert_Statement->bindParam(":senha", $senha);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
                $linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
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
                $linha = array($resultado['id'],$resultado['nome'],$resultado['login'],$resultado['senha']);
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