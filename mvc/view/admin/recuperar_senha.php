<?php include $_SERVER['DOCUMENT_ROOT'].'/library/functions.php'?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php'?>
<?php
	$acao=getParameter("acao");
	$repetir_nova_senha=hash('sha512', getParameter("repetir_nova_senha"));
	$nova_senha=hash('sha512', getParameter("nova_senha"));
	$code=getParameter("code");
	$disabled="";
	$nome="";
	$mensagem_erro="";
	$mensagem_successo="";
	$result=DAOquery("SELECT code,id,login,senha,nome,tentativas FROM usuarios where (code=:code) ",['code'=>$code],true,"");
	
?>
<div id="login" class="" style="background-repeat:no-repeat;height:850px;">
    <div class="row" style="height:135px;"></div>
    <div class="row" style="">

		<div class="col-md-4" style="margin-left:auto;margin-right:auto;width:320px;">
			<form class="form-login" method="POST">	
<?php   
if (count($result["data"])>0) {
    
    	if($acao=="Trocar senha"){
            if($repetir_nova_senha==$nova_senha)
    			if(DAOquery("update usuarios set senha=:nova_senha,code='' where(code=:code)",['code'=>$code,'nova_senha'=>$nova_senha],false,"")){
    			    $mensagem_successo="Senha alterada com sucesso!";
    			    $mensagem_successo.="<br><a href='".domainURL()."/admin/login'><< clique para voltar no login</a>";
    			    $disabled="disabled";
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
            $mensagem_erro=$mensagem_erro."<br><a href='".domainURL()."/admin/esqueceu_a_senha'><< clique para voltar em esqueceu a senha</a>";
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
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'?>