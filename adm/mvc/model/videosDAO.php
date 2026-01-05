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
	
	$id=getParameter("id");
	$id_album=getParameter("id_album");
	$nome=trim(getParameter("nome"));
	$video=getIDYouTube(getParameter("video"));
	$ocultar=(getParameter("ocultar")=="true");
	$select="select f.id,f.id_album,a.nome as album_videos,f.nome,f.video,f.ocultar from videos f left join album_videos a on (a.id=f.id_album) ";
	
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$linha=array();
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"id_album"=>array(
			"nome"=>"Código album_videos"
			,"tipo"=>"integer fk"
		)
		,"album_videos"=>array(
			"nome"=>"album vídeos"
			,"tipo"=>"varchar(50)"
		)
		,"nome"=>array(
			"nome"=>"Nome"
			,"tipo"=>"varchar(50)"
		)
		,"video"=>array(
			"nome"=>"video"
			,"tipo"=>"file video youtube"
		)
		,"ocultar"=>array(
			"nome"=>"Ocultar"
			,"tipo"=>"boolean"
		)
	);
	if($acao=="salvar"){
		$lista=array();
		
		if($id==null){
			$sql="INSERT INTO videos (id_album,nome,video,ocultar) VALUES (:id_album, :nome, :video, :ocultar )";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id_album", $id_album);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":video", $video);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				try {
					if ($my_Insert_Statement->execute()) {
					} else {
					}
				}
				catch (PDOException $error) {
				   //echo 'Connection error: ' . $error->getMessage();
				}
				$sql="$select where(f.id=(select max(id) from videos)) ";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					if ($my_Insert_Statement->execute()) {
					  //echo "New record created successfully";
					} else {
					  //echo "Unable to create record";
					}	
					
					while ($resultado=$my_Insert_Statement->fetch()) {
						$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'], $resultado['ocultar']);
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
			$sql="update videos set id_album=:id_album,nome=:nome, video=:video, ocultar=:ocultar where(id=:id)";
			try { 
				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":video", $video);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
				$my_Insert_Statement->bindParam(":id_album", $id_album);
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
						$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'],$resultado['ocultar']);
						array_push($lista,$linha); 
					}
				}
				catch (PDOException $error) {
				}
			}
			catch (PDOException $error) {
			}
		}
		
		$json_saida = json_encode(
			array(
				"mensagem_sucesso"=>"videos cadastrado com sucesso!"
				,"campos"=>$campos
				,"registros"=>$lista
			)
		);
		echo $json_saida;
		
	}
	else if($acao=="excluir"){
		$sql="delete from videos where(id=:id)";
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
					$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"videos excluído com sucesso!"
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
		$sql="$select where(f.$campo=:valor) order by f.id desc limit 0,100";
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
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'],$resultado['ocultar']);
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
		$sql=$sql."((:id_album = '')or(f.id_album=:id_album)) order by f.id desc limit 0,100";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
				$my_Insert_Statement->bindParam(":id", $id);
				$my_Insert_Statement->bindParam(":id_album", $id_album);
				$my_Insert_Statement->bindParam(":nome", $nome);
				$my_Insert_Statement->bindParam(":ocultar", $ocultar);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			$lista=array();
			while ($resultado=$my_Insert_Statement->fetch()) {
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'],$resultado['ocultar']);
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
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album_videos'],$resultado['nome'],$resultado['video'],$resultado['ocultar']);
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
		$modelo="album_videos";
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