<?php session_start(); ?>
<?php include 'functions.php'?>
<?php include 'conecta.php'?>
<?php include 'mvc/view/top.php'?>
<?php
	$mensagem_erro="";
	$mensagem_successo="";
	$acao=getParameter("acao");
	$e_mail=hash('sha512', getParameter("e_mail"));
	$nome="";
	if($acao=="Recuperar a senha"){
	    
		try { 
			$sql	= "SELECT id,nome,e_mail FROM usuarios where (e_mail=:e_mail)";
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":e_mail", $e_mail);
			if ($my_Insert_Statement->execute()) {
			} else {
			}
			if ($resultado=$my_Insert_Statement->fetch()) {
				    $id=$resultado['id'];
                    $nome=$resultado["nome"];
                    $code=hash('sha512', time());
                    $code_time=date('Y-m-d H:i:s', time());

        			$sql="update usuarios set code=:code,code_time=:code_time where(id=:id)";
        			try { 
        				$my_Insert_Statement2 = $my_Db_Connection->prepare($sql);
        				$my_Insert_Statement2->bindParam(":id", $id);
        				$my_Insert_Statement2->bindParam(":code", $code);
        				$my_Insert_Statement2->bindParam(":code_time", $code_time);
        				if ($my_Insert_Statement2->execute()) {
        				  //echo "New record created successfully";
        				} else {
        				  //echo "Unable to create record";
        				}
        			}
        			catch (PDOException $error) {
        			   echo 'Connection error: ' . $error->getMessage();
        			}
        			
        			//sendEmail($host,$username,$password,$subject,$email,$name,$urlBody);
        			sendEmail(
        			    "smtp.hostinger.com.br",
        			    "naoresponda@tooeste.com.br",
        			    "]!xY/>Lv3",
        			    "Recuperar senha de $nome do site ".$_SERVER['HTTP_HOST'],
        			    getParameter("e_mail"),
        			    $nome,
        			    domainURL()."/adm/email_recuperar_senha.php?acao=recuperar_senha&nome=$nome&e_mail=".getParameter("e_mail")
        			);
                    $mensagem_successo= "<b>$nome</b> foi enviado uma mensagem para recuperar sua senha para o e-mail <b>".getParameter("e_mail")."</b>.".
                    "<br>".
                    "<br>Por favor verifique a caixa de entrada do e-mail <b>".getParameter("e_mail")."</b>."
                    ;
                    
			}
			else $mensagem_erro="O e-mail ".getParameter("e_mail")." não foi encontrado em nosso sistema.";
			 /*$mensagem_erro=domainURL()."/adm/email_recuperar_senha.php?acao=recuperar_senha&nome=$nome&e_mail=".getParameter("e_mail")
			 ."<BR>$code"
			 ."<BR>$id"
			 ;*/
				
		}
		catch (PDOException $error) {
		     //$mensagem_erro=$error->getMessage( );
		}
	}
?>

<div id="login" class="" style="background-repeat:no-repeat;background-image:url('/adm/assets/img/cms/LINK LINK.png');height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" method="POST">
				
				<h2 class="form-login-heading" style="background-color:white">Esqueceu a senha</h2>
				<div class="form-group">
					<div class="input-group">
						<label for="e_mail" class="sr-only">E-mail</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="text" id="e_mail" name="e_mail" class="form-control" placeholder="E-mail" required autofocus>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group ">
						<input name="acao" class="btn btn-lg btn-primary btn-block" type="submit" value="Recuperar a senha">
					</div>
				</div>
				<div class="form-group">					
					<div class="input-group ">
						<a href="/adm/login.php" class="btn btn-link" style="background-color:white;">< voltar para login</a>
					</div>
				</div>	
				<?php if (strlen($mensagem_erro)>0){?>
			    <label class="alert alert-danger text-center"><?php echo $mensagem_erro?></label>
				<?php } ?>			
				<?php if (strlen($mensagem_successo)>0){?>
				<label class="alert alert-success text-center"><?php echo $mensagem_successo ?></label>
				<?php } ?>	
			
			</form>
		</div> 
	</div> 
</div>
<?php include 'mvc/foot.php'?>