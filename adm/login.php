<?php session_start(); ?>
<?php include 'functions.php'?>
<?php include 'conecta.php'?>
<?php include 'mvc/view/top.php'?>
<?php
	$acao=getParameter("acao");
	$login=hash('sha512', getParameter("login"));
	$senha=hash('sha512', getParameter("senha"));
	$mensagem_erro="";
	$mensagem_successo="";
	if($acao=="Entrar"){
		try { 
			$sql	= "SELECT id,login,senha,nome,tentativas FROM usuarios where (login=:login) ";
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":login", $login);
			
			if ($my_Insert_Statement->execute()) {
			} else {
		    
			}
			
			if ($resultado=$my_Insert_Statement->fetch()) {
			    $id		= $resultado["id"];
			    $tentativas		= $resultado["tentativas"];
				if (($login==$resultado["login"])&&($senha==$resultado["senha"])){
			        $_SESSION["id"]		= $id;
	            
				   
					$_SESSION["login"]	= $resultado["login"];
					$_SESSION["usuario"]	= $resultado["nome"];
					$_SESSION["time"]	= time();
					
					$sql="update usuarios set code='',code_time=null,tentativas=0 where(id=:id)";
        			try { 
        				$my_Insert_Statement = $my_Db_Connection->prepare($sql);
        				$my_Insert_Statement->bindParam(":id", $id);
        				if ($my_Insert_Statement->execute()) {
        				  //echo "New record created successfully";
        				} else {
        				  //echo "Unable to create record";
        				}
        			}
        			catch (PDOException $error) {
        			   //echo 'Connection error: ' . $error->getMessage();
        			}  
					
					$URL=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/adm/panel.php";
					exit(header("location:$URL"));
					exit;
				}
				else if($tentativas>=5)
        			$mensagem_erro="Usuário bloqueado já fora usadas 5 tentativas tente recuperar a conta com link <b>Esqueceu a senha</b> acima";
                else
				{
				    
				    $sql="update usuarios set tentativas=:tentativas where(id=:id)";
        			try { 
        				$my_Insert_Statement2 = $my_Db_Connection->prepare($sql);
        				$my_Insert_Statement2->bindParam(":id", $id);
        				$count_tentativas=$tentativas+1;
        				$my_Insert_Statement2->bindParam(":tentativas",$count_tentativas);
        				if ($my_Insert_Statement2->execute()) {
        				  //echo "New record created successfully";
        				} else {
        				  //echo "Unable to create record";
        				}
        			}
        			catch (PDOException $error) {
        			   //echo 'Connection error: ' . $error->getMessage();
        			}  
                    $mensagem_erro="Usuário ou senha inválido você possue ainda mais ".(5-$tentativas)." tentativas";
				
				}
			}
		}
		catch (PDOException $error) {
		}
	}
?>

<div id="login" class="" style="background-repeat:no-repeat;background-image:url('/adm/assets/img/cms/LINK LINK.png');height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" method="POST">
				
				<h2 class="form-login-heading" style="background-color:white">Bem vindo</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="login" class="sr-only">Login</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="text" id="login" name="login" class="form-control" placeholder="Login" required autofocus>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group">
						<label for="senha" class="sr-only">Senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
						<input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" required>
					</div>
				</div>

				<div class="form-group">
					<div class="input-group ">
						<input name="acao" class="btn btn-lg btn-primary btn-block" type="submit" value="Entrar">							
					</div>
				</div>
				<div class="form-group">					
					<div class="input-group ">
						<a href="/adm/esqueceu_a_senha.php" class="btn btn-link" style="background-color:white;">Esqueceu a senha</a>
					</div>
				</div>	
				<?php if (strlen($mensagem_erro)>0){?>
			    <label class="alert alert-danger"><?php echo $mensagem_erro?></label>
				<?php } ?>			
				<?php if (strlen($mensagem_successo)>0){?>
				<label class="alert alert-success"><?php echo $mensagem_successo ?></label>
				<?php } ?>	
			</form>
		</div> 
	</div> 
</div>
<?php include 'mvc/view/foot.php';?>