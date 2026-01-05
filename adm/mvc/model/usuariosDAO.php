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
	$nome=getParameter("nome");
	$e_mail=hash('sha512', getParameter("e_mail"));
	$login=hash('sha512', getParameter("login"));
	$senha=hash('sha512', getParameter("senha"));
	$senha_atual=hash('sha512', getParameter("senha_atual"));
	$nova_senha=hash('sha512', getParameter("nova_senha"));
	$repetir_nova_senha=hash('sha512', getParameter("repetir_nova_senha"));
	$select="select u.id, u.nome from usuarios u where (true) ";
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
		
	
	);
	if($acao=="salvar"){
		if($id==null){
			$sql="INSERT INTO usuarios (nome,login,senha,e_mail) VALUES (:nome, :login, :senha,:e_mail)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":e_mail", $e_mail);
				$my_Insert_Statement->bindParam(":login", $login);
				$my_Insert_Statement->bindParam(":senha", $senha);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$my_Insert_Statement->bind_result($resultado);
				//$resultado=$my_Insert_Statement->fetch();
				$sql="$select and (u.id=(select max(id) from usuarios)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					$lista=array();
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['nome']);
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
			$sql="update usuarios set nome=:nome,e_mail=:e_mail,login=:login,senha=:senha where(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":e_mail", $e_mail);
				$my_Insert_Statement->bindParam(":login", $login);
				$my_Insert_Statement->bindParam(":senha", $senha);
				$my_Insert_Statement->bindParam(":id", $id);
				if ($my_Insert_Statement->execute()) {
				  //echo "New record created successfully";
				} else {
				  //echo "Unable to create record";
				}
				//$resultado=$my_Insert_Statement->fetch();	
				
				$sql="$select AND (u.id=:id) ";
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
                        $linha = array($resultado['id'],$resultado['nome']);
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
		$sql="delete from usuarios  where (usuarios.id=:id)";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $id);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			//$resultado=$my_Insert_Statement->fetch();	
			$sql="$select AND (u.id=:id) ";
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
                    $linha = array($resultado['id'],$resultado['nome']);
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
	else if($acao=="trocar_senha"){
		$sql=$select." and (u.id=:id) and (u.senha=:senha_atual)";
		if($nova_senha!=$repetir_nova_senha){
		    $json_saida = json_encode(array("mensagem_erro"=>"A Nova senha e repetir nova senha não conhecidem"));
			echo $json_saida;
		}
		else
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $_SESSION["id"]);
			$my_Insert_Statement->bindParam(":senha_atual", $senha_atual);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}
			try { 
				if ($resultado=$my_Insert_Statement->fetch()) {
                   $sql="update usuarios set senha=:nova_senha where(id=:id)";
                   $my_Insert_Statement = $my_Db_Connection->prepare($sql);
			       $my_Insert_Statement->bindParam(":nova_senha", $nova_senha);
			       $my_Insert_Statement->bindParam(":id", $_SESSION["id"]);
                    if ($my_Insert_Statement->execute()) {
                      //echo "New record created successfully";
                    } else {
                      //echo "Unable to create record";
                    }
    				$json_saida = json_encode(
    					array(
    						"mensagem_sucesso"=>"Senha atualizada"
    						,"campos"=>$campos
    					)
    				);
    				echo $json_saida;				
				}
				else{
    		        $json_saida = json_encode(
    					array(
    						"mensagem_erro"=>"A senha atual não confere com este usuário"
    					)
    				);
    				echo $json_saida;
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
                $linha = array($resultado['id'],$resultado['nome']);
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
                $linha = array($resultado['id'],$resultado['nome']);
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
                $linha = array($resultado['id'],$resultado['nome']);
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
	$my_Insert_Statement=null;
	$my_Db_Connection=null;
?>