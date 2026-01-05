<?php include 'conecta.php'?>
<?php include 'functions.php'?>
<?php
    $code="";
	$acao=getParameter("acao");
	//$e_mail=getParameter("e_mail");
	$e_mail=hash('sha512', getParameter("e_mail"));
	$nome=getParameter("nome");
	if($acao=="recuperar_senha"){
		$sql	= "SELECT code FROM usuarios where (e_mail=:e_mail)";
			$my_Insert_Statement = $my_Db_Connection->prepare($sql);
			$my_Insert_Statement->bindParam(":e_mail", $e_mail);
			if ($my_Insert_Statement->execute()) {
			} else {
			}
			if ($resultado=$my_Insert_Statement->fetch()) {
    				    $code=$resultado['code'];
		    
	        
?>
<meta charset="utf-8">
<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
<center>

<h2 class="form-login-heading" style="background-color:white">Olá <b><?php echo $nome;?></b>! </h2>
<label for="recuperar_senha" >
    
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<BR>Nosso sistema recebeu sua solicitação de recuperar senha.
    &nbsp;<BR>Para fazer uma nova senha clique no link <b>Recuperar a senha</b> abaixo.
</label>
<br>
<br>
<a href="<?php echo domainURL();?>/adm/recuperar_senha.php?code=<?php echo $code;?>"  style="cursor:pointer">Recuperar a senha</a>
<br>
<br>
<img src='<?php echo domainURL();?>/adm/assets/img/cms/LINK_LINK_3.png' height="150px">
<br>
<label for="recuperar_senha" >
    Anteciosamente por nossa equipe da 
    <br><b>INPROLINK CMS SYSTEM</b>
</label>
<br>
<img src='<?php echo domainURL();?>/adm/assets/img/cms/cmsinprolink.jpg' >
</center>

<?php } ?>
<?php } ?>

