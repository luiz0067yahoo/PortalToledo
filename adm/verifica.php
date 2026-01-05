<?php 
	include 'conecta.php';
	if(!$_SESSION) session_start();
	if (isset($_SESSION["id"])){
		try {
			$sql    = "SELECT id,nome FROM usuario where (id=:id)";
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":id", $_SESSION["id"]);
			if ($my_Insert_Statement->execute()) {
			  //echo "New record created successfully";
			} else {
			  //echo "Unable to create record";
			}	
			while ($resultado=$my_Insert_Statement->fetch()) {
				$_SESSION["id"]		= $resultado["id"];
				$_SESSION["usuario"]	= $resultado["nome"];	
			}
		}
		catch (PDOException $error) {
		   //echo 'Connection error: ' . $error->getMessage();
		}
		$agora= time();
		$minutos=$agora-$_SESSION["time"];
		$dez_minutos=10*60;
		$restantes=$dez_minutos-$minutos;
		if ($restantes<=0)
		{
			header("location:/adm/logout.php");
		}
		else{
			$_SESSION["time"]=time();
		}
	}
	else{
			//header("location:/adm/logout.php");
			//echo $_SESSION["codigo"];
	}
?>