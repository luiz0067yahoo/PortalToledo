<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	require($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require($_SERVER['DOCUMENT_ROOT'].'/mvc/model/usuariosDAO.php');
	$acao=getParameter("acao");
	$login=getParameter("login");
	$senha=getParameter("senha");
	$mensagem_erro="";
	$mensagem_successo="";
	
	if($acao=="Entrar"){
		unset($acao);
		$mensagem_erro=login($login,$senha)["mensagem_erro"];
    	unset($login);
    	unset($senha);
		if($mensagem_erro==""){
    		userActiveName();
    		$_URL=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/admin/panel";    
    		header("location:$_URL");
		}
	}
    include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/top.php';
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
						<a href="<?php echo domainURL();?>/admin/esqueceu_a_senha" class="btn btn-link" style="background-color:white;">Esqueceu a senha</a>
					</div>
				</div>	
				<?php if ($mensagem_erro!=""){?>
			    <label class="alert alert-danger"><?php echo $mensagem_erro?></label>
				<?php } ?>			
				<?php if ($mensagem_successo!=""){?>
				<label class="alert alert-success"><?php echo $mensagem_successo ?></label>
				<?php } ?>	
			</form>
		</div> 
	</div> 
</div>
<?php include $_SERVER['DOCUMENT_ROOT'].'/mvc/view/admin/templates/foot.php';?>