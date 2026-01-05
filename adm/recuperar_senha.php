<?php session_start(); ?>
<?php include 'functions.php'?>
<?php include 'conecta.php'?>
<?php include 'mvc/view/top.php'?>
<?php
	$acao=getParameter("acao");
	$repetir_nova_senha=hash('sha512', getParameter("repetir_nova_senha"));
	$nova_senha=hash('sha512', getParameter("nova_senha"));
	$code=getParameter("code");
	$disabled="";
	$nome="";
	$mensagem_erro="";
	$mensagem_successo="";
	$sql	= "SELECT id,login,senha,nome,tentativas FROM usuarios where (code=:code) ";
	$my_Insert_Statement = $my_Db_Connection->prepare($sql);
	$my_Insert_Statement->bindParam(":code", $code);
	if ($my_Insert_Statement->execute()) {
	} else {}?>
<div id="login" class="" style="background-repeat:no-repeat;background-image:url('/adm/assets/img/cms/LINK LINK.png');height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" method="POST">	
<?php   if ($resultado=$my_Insert_Statement->fetch()) {
    	if($acao=="Trocar senha"){
            if($repetir_nova_senha==$nova_senha)
    		try { 
    		    
    			$sql="update usuarios set senha=:nova_senha,code='' where(code=:code)";
    			$my_Insert_Statement2 = $my_Db_Connection->prepare($sql);
    			$my_Insert_Statement2->bindParam(":code", $code);
    			$my_Insert_Statement2->bindParam(":nova_senha", $nova_senha);
    			if ($my_Insert_Statement2->execute()) {
    			    $mensagem_successo="Senha alterada com sucesso!";
    			    $mensagem_successo=$mensagem_successo."<br><a href='login.php'><< clique para voltar no login</a>";
    			    $disabled="disabled";
    			} else {}
    			
    		}
    		catch (PDOException $error) {
    		}
    	}
?>

				
				<h2 class="form-login-heading" style="background-color:white">Troque sua senha <?php echo $nome;?></h2>
				<div class="form-group">
					<div class="input-group">
						<label for="login" class="sr-only">Nova senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
						<input type="password" id="nova_senha" name="nova_senha" class="form-control" placeholder="Nova senha" required <?php echo $disabled;?>>
					</div>
				</div>			
				<div class="form-group">
					<div class="input-group">
						<label for="senha" class="sr-only">Repetir nova senha</label>
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
						<input type="password" id="senha" name="repetir_nova_senha" class="form-control" placeholder="Repetir nova senha" required <?php echo $disabled;?>>
					</div>
				</div>

				<div class="form-group">
					<div class="input-group ">
						<input name="acao" class="btn btn-lg btn-primary btn-block" type="submit" value="Trocar senha" >							
					</div>
				</div>
		
<?php 
        }
        else{ 
            $mensagem_erro="O código gerado para sua senha não é mais valído por vá em esqueceu sua senha";
            $mensagem_erro=$mensagem_erro."<br><a href='esqueceu_a_senha.php'><< clique para voltar em esqueceu a senha</a>";
        }
?>
               
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
<?php include 'mvc/view/foot.php'?>