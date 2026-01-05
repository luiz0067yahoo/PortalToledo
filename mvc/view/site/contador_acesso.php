<?php 
	require($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
    date_default_timezone_set("America/Sao_Paulo");
    $data_atual=date("Y-m-d");
    $hora_atual=date("h:i:s");
    $sql="SELECT count(c.url) as acessos,c.menu,c.submenu,c.categoria,c.titulo,c.url FROM contador_acesso_por_pagina c ";
    $sql.=" where (c.data_acesso between :data_inicio and :data_fim) ";
    $sql.=" group by c.url  order by count(c.url) desc";
    $params=array(
        'data_inicio'=>$data_atual,
        'data_fim'=>$data_atual
    );
    $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas por dia</h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	  
</table>
<?php }?>
  


<?php
    $day = date('w');
    $week_start = date('Y-m-d', strtotime('-'.$day.' days'));
    $week_end = date('Y-m-d', strtotime('+'.(6-$day).' days'));
    $params=array(
        'data_inicio'=>$week_start,
        'data_fim'=>$week_end
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
	$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas por semana</h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	  
</table>
<?php }?>
  


<?php
    $meses = array(
    1 => 'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
);
	$mes =intval( date("m"));
    $P_Dia = date("Y-m-01");
    $U_Dia = date("Y-m-t");
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	  
</table>
<?php }?>

<?php

    $P_Dia = date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-1 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-1 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>


<?php

    $P_Dia = date('Y-m-d', strtotime('-2 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-2 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-2 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-4 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-4 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-4 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-3 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  



<?php

    $P_Dia = date('Y-m-d', strtotime('-5 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-5 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-5 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-3 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-3 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  



<?php

    $P_Dia = date('Y-m-d', strtotime('-5 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-5 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-5 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-6 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-6 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-6 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-7 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-7 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-7 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  



<?php

    $P_Dia = date('Y-m-d', strtotime('-8 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-8 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-8 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  



<?php

    $P_Dia = date('Y-m-d', strtotime('-9 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-9 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-9 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-10 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-10 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-10 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-11 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-11 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-11 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  


<?php

    $P_Dia = date('Y-m-d', strtotime('-12 months', strtotime(date('Y-m-01'))));
    $U_Dia = date('Y-m-d', strtotime('-12 months', strtotime(date('Y-m-t'))));
    $mes = intval(date('m', strtotime('-12 months', strtotime(date('Y-m-t')))));
	
    $params=array(
        'data_inicio'=>$P_Dia,
        'data_fim'=>$U_Dia
    );
   $result_contador=DAOquery($sql,$params,true,"");
	$elements=$result_contador["elements"];
	$campos_contador=[];
	if (count($elements)>0){
		$campos_contador=array_keys($elements[0]);
		
?>
<h1>Relatorio de paginas mais acessadas pelo mês de  <?php echo $meses[$mes] ;?> </h1>
<table border=1>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <th><?php echo $campos_contador[$i]; ?></th>
        <?php } ?>
    </tr>
	<?php for ($j = 0; $j<count($elements); $j++) {
		$element=$elements[$j];
		
	?>
    <tr>
        <?php for ($i = 0; $i<count($campos_contador); $i++) { ?>
        <td><?php echo ($campos_contador[$i]=="url")?"<a href='".$element[$campos_contador[$i]]."'>Acessar</a>":$element[$campos_contador[$i]]; ?></td>
        <?php } ?>
    </tr>
    <?php } ?>	    
</table>
<?php }?>  