<?php

            	ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
                error_reporting(0);

?>
<?php require $_SERVER['DOCUMENT_ROOT'].'/library/functions.php'?>
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php';	?>
<?php
	$mensagem_erro="";
	$mensagem_successo="";
	$acao=getParameter("acao");
	$e_mail=hash('sha512', getParameter("e_mail"));
	$nome="";
	if($acao=="Recuperar a senha"){
	    
		try { 
			$result=DAOquery("SELECT id,nome,e_mail FROM usuarios where (e_mail=:e_mail)",['e_mail'=>$e_mail],true,"");
			if(count($result["data"])>0){
    			$id=resultDataFieldByTitle($result,"id",0);
    			$url=resultDataFieldByTitle($result,"nome",0);
    			$url=resultDataFieldByTitle($result,"nome",0);
                $code=hash('sha512', time());
                $code_time=date('Y-m-d H:i:s', time());
                DAOquery("update usuarios set code=:code,code_time=:code_time where(id=:id)",['id'=>$id,'code'=>$code,'code_time'=>$code_time],false,"");
            	
            	//sendEmail($host,$username,$password,$subject,$email,$name,$urlBody);
            	
    			sendEmailUrl(
    			    "smtp.hostinger.com.br",
    			    "naoresponda@tooeste.com.br",
    			    "Tooeste Nao Responda",
    			    "]!xY/>Lv3",
    			    "Recuperar senha de $nome do site ".$_SERVER['HTTP_HOST'],
    			    getParameter("e_mail"),
    			    $nome,
    			    domainURL()."/admin/email_recuperar_senha?acao=recuperar_senha&nome=$nome&e_mail=".getParameter("e_mail")
    			);
                $mensagem_successo= "<b>$nome</b> foi enviado uma mensagem para recuperar sua senha para o e-mail <b>".getParameter("e_mail")."</b>.".
                "<br>".
                "<br>Por favor verifique a caixa de entrada do e-mail <b>".getParameter("e_mail")."</b>."
                ;
			}
			else $mensagem_erro="O e-mail ".getParameter("e_mail")." não foi encontrado em nosso sistema.";
		}
		catch (PDOException $error) {
		     //$mensagem_erro=$error->getMessage( );
		}
	}
	
?>

<div id="login" class="" style="background-repeat:no-repeat;height:850px;">
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
						<a href="<?php echo domainURL();?>/admin/login" class="btn btn-link" style="background-color:white;">< voltar para login</a>
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
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php'?>