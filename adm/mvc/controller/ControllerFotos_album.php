<?php 
session_start();
	if (!(isset($_SESSION["id"]))) exit();
include('../conecta.php'); 
include '../verifica.php';
function redimensiona($origem,$destino,$maxlargura,$maxaltura,$qualidade){
	list($largura, $altura) = getimagesize($origem);
	if($altura>$largura){
		$diferenca=$altura/$maxaltura;
		$maxlargura=$largura/$diferenca;
	}
	else{
		$diferenca=$largura/$maxlargura;
		$maxaltura=$altura/$diferenca;
	}
	$image_p = ImageCreateTrueColor($maxlargura,$maxaltura)	or die("Cannot Initialize new GD image stream");	
	$origem = imagecreatefromjpeg($origem);
	imagecopyresampled($image_p, $origem, 0, 0, 0, 0,  $maxlargura, $maxaltura, $largura, $altura);
	imagejpeg($image_p, $destino, $qualidade);
	imagedestroy($image_p);
	imagedestroy($origem);
}

function upload(){
	$lista_arquivos=array();
	
	$pasta_upload='../../uploads/';
	if (!file_exists($pasta_upload))
		mkdir($pasta_upload, 0777, true); 

	$pasta_album=$pasta_upload.'album/';
	if (!file_exists($pasta_album))
		mkdir($pasta_album, 0777, true); 
	
	$pasta_upload_original=$pasta_upload.'album/original/';
	if (!file_exists($pasta_upload_original))
		mkdir($pasta_upload_original, 0777, true); 
	
	$quantidade_fotos = count($_FILES['foto']['name']);
	for($i=0;$i<$quantidade_fotos;$i++){
		$_SESSION["time"]=time();
		$nome_do_arquivo = $_FILES['foto']['name'][$i];
		$endereco_arquivo=$pasta_upload_original.$nome_do_arquivo;
		
		$nome = pathinfo($endereco_arquivo, PATHINFO_FILENAME);
		$extensao = pathinfo($endereco_arquivo, PATHINFO_EXTENSION);
		$contador=1;
		while(file_exists($endereco_arquivo)){
			$nome_do_arquivo=$nome."_".$contador.".".$extensao;
			$endereco_arquivo=$pasta_upload_original.$nome_do_arquivo;
			$contador++;
		}
		move_uploaded_file($_FILES['foto']['tmp_name'][$i],$endereco_arquivo);
		
		$largura=1366;
		$altura=768;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);


		$largura=1024;
		$altura=768;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);

		$largura=800;
		$altura=600;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);


		$largura=640;
		$altura=480;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);

		$largura=320;
		$altura=240;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);
		
		
		$largura=160;
		$altura=120;
		$nova_pasta=$pasta_upload."album/".$largura."x".$altura."/";
		if (!file_exists($nova_pasta))
			mkdir($nova_pasta, 0777, true); 
		redimensiona($pasta_upload_original.$nome_do_arquivo,$nova_pasta.$nome_do_arquivo,$largura,$altura,75);
		array_push($lista_arquivos,$nome_do_arquivo); 
	}
	return $lista_arquivos;
}


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
	$id_album=isset($_POST["id_album"])?$_POST["id_album"]:(isset($_GET["id_album"])?$_GET["id_album"]:"");
	$nome=trim(isset($_POST["nome"])?$_POST["nome"]:(isset($_GET["nome"])?$_GET["nome"]:""));
	$ocultar=isset($_POST["ocultar"])?$_POST["ocultar"]:(isset($_GET["ocultar"])?$_GET["ocultar"]:false);
		$ocultar=($ocultar=="true");
	$select="select f.id,f.id_album,a.nome as album,f.nome,f.foto,f.ocultar from fotos_album f left join album a on (a.id=f.id_album) ";
	$sql="";
	$resultado=null;
	$my_Insert_Statement=null;
	$campos=array(
		"id"=>array(
			"nome"=>"Código"
			,"tipo"=>"integer"
		)
		,"id_album"=>array(
			"nome"=>"Código album"
			,"tipo"=>"integer fk"
		)
		,"album"=>array(
			"nome"=>"album"
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
		$fotos=upload();
		$lista=array();
		foreach ($fotos as $foto){
			if($id==null){
				$sql="INSERT INTO fotos_album (id_album,nome,foto,ocultar) VALUES (:id_album, :nome, :foto, :ocultar )";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					$my_Insert_Statement->bindParam(":id_album", $id_album);
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
					$sql="$select where(f.id=(select max(id) from fotos_album)) ";
					try { 
						$my_Insert_Statement = $my_Db_Connection->prepare($sql);
						if ($my_Insert_Statement->execute()) {
						  //echo "New record created successfully";
						} else {
						  //echo "Unable to create record";
						}	
						
						while ($resultado=$my_Insert_Statement->fetch()) {
							$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'], $resultado['ocultar']);
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
				$sql="update fotos_album set id_album=:id_album,nome=:nome, foto=:foto, ocultar=:ocultar where(id=:id)";
				try { 
					$my_Insert_Statement = $my_Db_Connection->prepare($sql);
					$my_Insert_Statement->bindParam(":foto", $foto);
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
							$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
				"mensagem_sucesso"=>"fotos_album cadastrado com sucesso!"
				,"campos"=>$campos
				,"registros"=>$lista
			)
		);
		echo $json_saida;
		
	}
	else if($acao=="excluir"){
		$sql="delete from fotos_album where(id=:id)";
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
					$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
					array_push($lista,$linha); 
				}
				$json_saida = json_encode(
					array(
						"mensagem_sucesso"=>"fotos_album excluído com sucesso!"
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
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
		$sql=$sql."((:id_album = '')or(f.id_album=:id_album))";
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
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
				$linha = array($resultado['id'],$resultado['id_album'],$resultado['album'],$resultado['nome'],$resultado['foto'],$resultado['ocultar']);
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
		$modelo="album";
		$sql="select $chave,$valor from $modelo ";
		try { 
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement2 = $my_Db_Connection->prepare("SELECT id,nome,descricao,ocultar,tema,date_insert,date_update FROM `menus` where false UNION select null,null,null,null,null,null,null from menus;");
			$lista=array();
			if ($my_Insert_Statement->execute()) {
    			while ($resultado=$my_Insert_Statement2->fetch()) {
    				$linha = array($resultado[$chave],$resultado[$valor]);
    				array_push($lista,$linha);
    			}
			} else {
			  //echo "Unable to create record";
			}	

			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
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