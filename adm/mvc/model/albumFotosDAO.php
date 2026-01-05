<?php 
session_start();
if (!(isset($_SESSION["id"]))) exit();
include('../../functions.php');
include('../../conecta.php');
include '../../verifica.php';

	$acao=getParameter("acao");

	$chave=BlockSQLInjection(getParameter("chave"));
	$campo=BlockSQLInjection(getParameter("campo"));
	$valor=BlockSQLInjection(getParameter("valor"));
	$modelo=BlockSQLInjection(getParameter("modelo"));
	$id=getParameter("id");
	$id_menu=getParameter("id_menu");
	$nome=trim(getParameter("nome"));
	$ocultar=(getParameter("ocultar")=="true");
	$select="select a.id,a.id_menu,m.nome as menu,a.nome,a.ocultar from album_fotos a left join menus m on (m.id=a.id_menu) where (true) ";
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
		if($id==""){
	
		    if($id_menu=="")
			    $sql="INSERT INTO album_fotos (id_menu,nome,ocultar) VALUES (null, :nome, :ocultar )";
			else
			    $sql="INSERT INTO album_fotos (id_menu,nome,ocultar) VALUES (:id_menu, :nome, :ocultar )";
			    
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				if($id_menu!="")
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
				$sql="$select and (a.id=(select max(id) from album_fotos)) ";
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
							"mensagem_sucesso"=>"albúm de fotos cadastrado com sucesso!"
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
		    if($id_menu=="")
			    $sql="update album_fotos set id_menu=null,nome=:nome,ocultar=:ocultar where(id=:id)";
			else
			    $sql="update album_fotos set id_menu=:id_menu,nome=:nome,ocultar=:ocultar where(id=:id)";
			
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				if($id_menu!="")
				    $my_Insert_Statement->bindParam(":id_menu", $id_menu);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
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
							"mensagem_sucesso"=>"albúm de fotos atualizado com sucesso!"
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
		$sql="delete from album_fotos  where (album_fotos.id=:id)";
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
						"mensagem_sucesso"=>"albúm de fotos excluído com sucesso!"
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
			$linha = array("","PRINCIPAL");
			array_push($lista,$linha);
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
	$my_Insert_Statement=null;
	$my_Db_Connection=null;
?>