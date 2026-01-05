<?php
$url_full=$_SERVER['SCRIPT_URI'];
$last_bar=strrpos( $_SERVER['SCRIPT_URI'],'/');
echo substr($_SERVER['SCRIPT_URI'],0,$last_bar+1);

$url = explode($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_URI'])[0];
//echo $url;


$token = md5(time() . rand(1,100));
$_SESSION['token'] = $token;


?>
<input type='hidden' name='token' value='<?=$token;?>'/>
<?php
if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['token']){
  //exit("Bad token!");
}
unset($_SESSION['token']);
?>