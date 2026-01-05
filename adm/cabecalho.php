<?php	
			include('verifica.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type" />
		<script type="text/javascript">		
			function relogio(){
				var min, seg;				
				if (document.getElementById("cronometro").innerHTML==""){
					min = "<?php echo $minutos;?>";
					seg = 0;
				}
				else{
					var acc=document.getElementById("cronometro").innerHTML;
					min = acc.split(':')[0];
					seg = acc.split(':')[1];
				}
				if((min > 0) || (seg > 0)){
					if(seg == 0){
						seg = 59;
						min--;
					}
					else{
						seg--;
					}
					if(min.toString().length == 1){
						min = "0" + min;
					}
					if(seg.toString().length == 1){
						seg = "0" + seg;
					}
					document.getElementById("cronometro").innerHTML = min + ":" + seg;
					setTimeout('relogio()', 1000);
				}
				else{
				 parent.window.location="logout.php";
				}
			}
			
		</script>
	</head>
	<body onload="relogio();">
		<a href="principal.php">voltar</a>&nbsp;		
		&nbsp;<a href="logout.php">sair</a><br>
		Sessao encerra em:&nbsp;<label id="cronometro"></label><br>