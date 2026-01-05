<?php 
$base_server_path_files=$_SERVER['DOCUMENT_ROOT'];
$base_url="https://$_SERVER[HTTP_HOST]";  	
require_once($GLOBALS["base_server_path_files"].'/route.php');
require_once($GLOBALS["base_server_path_files"].'/library/functions.php');
Route::add('/contador_acesso',function(){  
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_titulo_noticia"]="";
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/contador_acesso.php');
});
Route::add('/index.php',function(){
	$GLOBALS["ler_menu"]="";
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_titulo_noticia"]="";
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/home.php');
});
Route::add('/',function(){
	$GLOBALS["ler_menu"]="";
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_titulo_noticia"]="";
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/home.php');
});
   
Route::add('/missao_visao_e_valores',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/term_missai_vision_values.php');
},'get');
Route::add('/politica_de_privacidade',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/term_policy_privacy.php');
},'get');
Route::add('/termo_de_uso_e_responsabilidade',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/term_use_responsibility.php');
},'get');

Route::add('/contato',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/contact.php');
},'get');
Route::add('/contato',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/contact.php');
},'post');

Route::add('/buscar/',function(){
	$GLOBALS["ler_menu"]="";
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_titulo_noticia"]="";
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/search.php');
});
Route::add('/ler/(.*)/(.*)/(.*)',function($ler_menu,$ler_sub_menu,$ler_titulo_noticia){
	$GLOBALS["ler_menu"]=myUrlDecode($ler_menu);
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_titulo_noticia"]="";
	if (isset($ler_titulo_noticia)&& !empty($ler_titulo_noticia))
		$GLOBALS["ler_titulo_noticia"]=myUrlDecode($ler_titulo_noticia);
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/ler/(.*)/(.*)/',function($ler_menu,$ler_sub_menu){
	$GLOBALS["ler_menu"]=myUrlDecode($ler_menu);
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_titulo_noticia"]="";
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/ler/(.*)/(.*)',function($ler_menu,$ler_titulo_noticia){
	$GLOBALS["ler_menu"]=myUrlDecode($ler_menu);
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_titulo_noticia"]="";
	if (isset($ler_titulo_noticia)&& !empty($ler_titulo_noticia))
		$GLOBALS["ler_titulo_noticia"]=myUrlDecode($ler_titulo_noticia);
	$GLOBALS["ler_categoria"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/ler/(.*)/',function($ler_menu){
	$GLOBALS["ler_menu"]=myUrlDecode($ler_menu);
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});

Route::add('/'.urlencode("Patrocinador").'/(.*)/(.*)',function($ler_sub_menu,$ler_titulo_noticia){
	$GLOBALS["ler_menu"]=myUrlDecode("Patrocinador");
	$GLOBALS["ler_sub_menu"]=$ler_sub_menu;
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]=$ler_titulo_noticia;
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});

Route::add('/'.urlencode("Patrocinador").'/(.*)',function($ler_sub_menu){
	$GLOBALS["ler_menu"]=myUrlDecode("Patrocinador");
	$GLOBALS["ler_sub_menu"]=$ler_sub_menu;
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});

Route::add('/'.urlencode("contato"),function(){
	$GLOBALS["ler_menu"]=myUrlDecode("contato");
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
},'get');

Route::add('/'.urlencode("contato"),function(){
	$GLOBALS["ler_menu"]=myUrlDecode("contato");
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
},'post');

Route::add('/'.urlencode("Fotos").'/(.*)/(.*)',function($ler_sub_menu,$ler_categoria){
	$GLOBALS["ler_menu"]=myUrlDecode("Fotos");
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_categoria"]="";
	if (isset($ler_categoria)&& !empty($ler_categoria))
		$GLOBALS["ler_categoria"]=myUrlDecode($ler_categoria);
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Fotos").'/(.*)/',function($ler_sub_menu){
	$GLOBALS["ler_menu"]=myUrlDecode("Fotos");
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Fotos").'/(.*)',function($ler_categoria){
	$GLOBALS["ler_menu"]=myUrlDecode("Fotos");
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_categoria"]="";
	if (isset($ler_categoria)&& !empty($ler_categoria))
		$GLOBALS["ler_categoria"]=myUrlDecode($ler_categoria);
	$GLOBALS["ler_titulo_noticia"]="";	
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Fotos").'/',function($params){
	$GLOBALS["ler_menu"]=myUrlDecode("Fotos");	
	$GLOBALS["ler_sub_menu"]="";	
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});



Route::add('/'.urlencode("Vídeos").'/(.*)/(.*)',function($ler_sub_menu,$ler_categoria){
	$GLOBALS["ler_menu"]=myUrlDecode("Vídeos");
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_categoria"]="";
	if (isset($ler_categoria)&& !empty($ler_categoria))
		$GLOBALS["ler_categoria"]=myUrlDecode($ler_categoria);
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Vídeos").'/(.*)/',function($ler_sub_menu){
	$GLOBALS["ler_menu"]=myUrlDecode("Vídeos");
	$GLOBALS["ler_sub_menu"]=myUrlDecode($ler_sub_menu);
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Vídeos").'/(.*)',function($ler_categoria){
	$GLOBALS["ler_menu"]=myUrlDecode("Vídeos");
	$GLOBALS["ler_sub_menu"]="";
	$GLOBALS["ler_categoria"]="";
	if (isset($ler_categoria)&& !empty($ler_categoria))
		$GLOBALS["ler_categoria"]=myUrlDecode($ler_categoria);
	$GLOBALS["ler_titulo_noticia"]="";	
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});
Route::add('/'.urlencode("Vídeos").'/',function($params){
	$GLOBALS["ler_menu"]=myUrlDecode("Vídeos");	
	$GLOBALS["ler_sub_menu"]="";	
	$GLOBALS["ler_categoria"]="";
	$GLOBALS["ler_titulo_noticia"]="";
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/site/read.php');
});



Route::add('/admin',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/index.php');
});

Route::add('/admin/',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/index.php');
});

Route::add('/admin/panel',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/panel.php');
});

Route::add('/admin/login',function(){
	require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
},'get');

Route::add('/admin/login',function(){
	require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
},'post');

Route::add('/admin/logout',function(){
	logout();
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
},"post");

Route::add('/admin/logout',function(){
	logout();
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
},"get");

Route::add('/admin/esqueceu_a_senha',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/esqueceu_a_senha.php');
},"get");

Route::add('/admin/esqueceu_a_senha',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/esqueceu_a_senha.php');
},"post");

Route::add('/admin/recuperar_senha',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/recuperar_senha.php');
},"get");

Route::add('/admin/recuperar_senha',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/recuperar_senha.php');
},"post");

Route::add('/admin/email_recuperar_senha',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/email_recuperar_senha.php');
});


Route::add('/admin/time_session',function(){
    echo sessionCount();
},'get');

Route::add('/admin/apps/explorer',function(){
     if(!isset($_SESSION)) session_start();
    if(isset($_SESSION["usuario"])){ 
        require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/apps/explorer.html');
    }
    else {
    //require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
        require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login_explorer.php');
    }
},'get');

Route::add('/admin/apps/explorer',function(){
     if(!isset($_SESSION)) session_start();
    if(isset($_SESSION["usuario"])){ 
        require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/apps/explorer.html');
    }
    else {
    //require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login.php');
        require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/login_explorer.php');
    }
},'post');

Route::add('/admin/explorer',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/controller/explorer.php');
},'get');

Route::add('/admin/explorer',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/controller/explorer.php');
},'post');
//###############################################################################




//###############################################################################
Route::add('/admin/usuarios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_usuarios.php');
},'get');

Route::add('/admin/menus',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_menus.php');
},'get');

Route::add('/admin/noticias',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_noticias.php');
},'get');

Route::add('/admin/noticiasAnexo',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_noticiasAnexo.php');
},'get');

Route::add('/admin/tiposAnuncios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_tiposAnuncios.php');
},'get');

Route::add('/admin/anuncios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_anuncios.php');
},'get');

Route::add('/admin/albumFotos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_albumFotos.php');
},'get');

Route::add('/admin/fotos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_fotos.php');
},'get');

Route::add('/admin/albumVideos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_albumVideos.php');
},'get');

Route::add('/admin/videos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_videos.php');
},'get');
//###############################################################################



//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerMenus.php');
Route::add('/server/site/mainMenus',function(){
   ((new controllerMenus())->findMainMenus());
},'get');

Route::add('/server/site/subMenus/([0-9]*)',function($idMenu){
   ((new controllerMenus())->findSubMenus($idMenu));
},'get');

Route::add('/server/menus',function(){
   if(controlAcess())(new controllerMenus())->find();
},'get');

Route::add('/server/menus/([0-9]*)',function($id){
   if(controlAcess())((new controllerMenus())->findById($id));
},'get');

Route::add('/server/menus',function(){
    if(controlAcess())((new controllerMenus())->save());
},'put');

Route::add('/server/menus',function(){
    if(controlAcess())((new controllerMenus())->del());
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAlbumFotos.php');
Route::add('/server/site/slideShowPhotos/',function(){
   ((new controllerAlbumFotos())->findSlideShow($menuSubMenu=''));
},'get');

Route::add('/server/site/slideShowPhotos/(.*)/',function($menuSubMenu){
   ((new controllerAlbumFotos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/slideShowPhotos/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerAlbumFotos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/slideShowPhotos/(.*)/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerAlbumFotos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/photos/(.*)/',function($menuSubMenu){
   ((new controllerAlbumFotos())->findMenuAlbum($menuSubMenu));
},'get');

Route::add('/server/albumFotos',function(){
   if(controlAcess())((new controllerAlbumFotos())->find());
},'get');

Route::add('/server/albumFotos/([0-9]*)',function($id){
   if(controlAcess())((new controllerAlbumFotos())->findById($id));
},'get');


Route::add('/server/albumFotos',function(){
    if(controlAcess())((new controllerAlbumFotos())->save());
},'put');

Route::add('/server/albumFotos',function(){
    if(controlAcess())((new controllerAlbumFotos())->del());
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerFotos.php');

Route::add('/server/fotos',function(){
    if(controlAcess())((new controllerFotos())->find());
},'get');

Route::add('/server/fotos/([0-9]*)',function($id){
   if(controlAcess())((new controllerFotos())->findById($id));
},'get');

Route::add('/server/fotos',function(){
    if(controlAcess())((new controllerFotos())->save());
},'post');

Route::add('/server/fotos',function(){
    if(controlAcess())((new controllerFotos())->del());
},'delete');
//###############################################################################


//###############################################################################
require($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAlbumVideos.php');
Route::add('/server/site/slideShowVideos/',function(){
   ((new controllerAlbumVideos())->findSlideShow($menuSubMenu=''));
},'get');

Route::add('/server/site/slideShowVideos/(.*)/',function($menuSubMenu){
   ((new controllerAlbumVideos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/slideShowVideos/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerAlbumVideos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/slideShowVideos/(.*)/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerAlbumVideos())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/videos/(.*)/',function($menuSubMenu){
   ((new controllerAlbumVideos())->findMenuAlbum($menuSubMenu));
},'get');

Route::add('/server/albumVideos',function(){
   if(controlAcess())((new controllerAlbumVideos())->find());
},'get');

Route::add('/server/albumVideos/([0-9]*)',function($id){
   if(controlAcess())((new controllerAlbumVideos())->findById($id));
},'get');

Route::add('/server/albumVideos',function(){
    if(controlAcess())((new controllerAlbumVideos())->save());
},'put');

Route::add('/server/albumVideos',function(){
    if(controlAcess())((new controllerAlbumVideos())->del());
},'delete');
//###############################################################################

//###############################################################################
require($GLOBALS["base_server_path_files"].'/mvc/controller/controllerVideos.php');
Route::add('/server/videos',function(){
    if(controlAcess())((new controllerVideos())->find());
},'get');

Route::add('/server/videos/([0-9]*)',function($id){
   if(controlAcess())((new controllerVideos())->findById($id));
},'get');

Route::add('/server/videos',function(){
    if(controlAcess())((new controllerVideos())->save());
},'post');

Route::add('/server/videos',function(){
    if(controlAcess())((new controllerVideos())->del());
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerNoticias.php');
Route::add('/server/site/slideShowNews/',function(){
   ((new controllerNoticias())->findSlideShow($menuSubMenu=""));
},'get');

Route::add('/server/site/slideShowNews/(.*)/',function($menuSubMenu){
   ((new controllerNoticias())->findSlideShow($menuSubMenu));
},'get');

Route::add('/server/site/slideShowNews/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerNoticias())->findSlideShow($menuSubMenu));
},'get');


Route::add('/server/site/homeNews/',function(){
   ((new controllerNoticias())->findHome());
},'get');


Route::add('/server/site/News/(.*)/',function($menuSubMenu){
   ((new controllerNoticias())->findMenu($menuSubMenu));
},'get');

Route::add('/server/site/News/(.*)/(.*)/',function($menuSubMenu){
   ((new controllerNoticias())->findMenu($menuSubMenu));
},'get');



Route::add('/server/noticias',function(){
   if(controlAcess())((new controllerNoticias())->find());
},'get');

Route::add('/server/noticias/([0-9]*)',function($id){
   if(controlAcess())((new controllerNoticias())->findById($id));
},'get');

Route::add('/server/noticias',function(){
    if(controlAcess())((new controllerNoticias())->save());
},'put');

Route::add('/server/noticias',function(){
    if(controlAcess())((new controllerNoticias())->del());
},'delete');
//###############################################################################


//###############################################################################
Route::add('/server/noticiasAnexo',function(){
   if(controlAcess())((new controllerNoticiasAnexo())->find());
},'get');

Route::add('/server/noticiasAnexo/([0-9]*)',function($id){
   if(controlAcess())((new controllerNoticiasAnexo())->findById($id));
},'get');

Route::add('/server/noticiasAnexo',function(){
    if(controlAcess())((new controllerNoticiasAnexo())->save());
},'put');

Route::add('/server/noticiasAnexo',function(){
    if(controlAcess())((new controllerNoticiasAnexo())->del());
},'delete');
//###############################################################################


//###############################################################################
Route::add('/server/noticiasFotos',function(){
   if(controlAcess())((new controllerNoticiasFotos())->find());
},'get');

Route::add('/server/noticiasFotos/([0-9]*)',function($id){
   if(controlAcess())((new controllerNoticiasFotos())->findById($id));
},'get');

Route::add('/server/noticiasFotos',function(){
    if(controlAcess())((new controllerNoticiasFotos())->save());
},'put');

Route::add('/server/noticiasFotos',function(){
    if(controlAcess())((new controllerNoticiasFotos())->del());
},'delete');
//###############################################################################


//###############################################################################
Route::add('/server/tiposAnuncios',function(){
   if(controlAcess())((new controllerTiposAnuncios())->find());
},'get');

Route::add('/server/tiposAnuncios/([0-9]*)',function($id){
   if(controlAcess())((new controllerTiposAnuncios())->findById($id));
},'get');

Route::add('/server/tiposAnuncios',function(){
    if(controlAcess())((new controllerTiposAnuncios())->save());
},'put');

Route::add('/server/tiposAnuncios',function(){
    if(controlAcess())((new controllerTiposAnuncios())->del());
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAnuncios.php');
Route::add('/server/site/banners/(.*)',function($nameType){
   ((new controllerAnuncios())->findbyType(myUrlDecode($nameType)));
},'get');

Route::add('/server/anuncios',function(){
   if(controlAcess())((new controllerAnuncios())->find());
},'get');

Route::add('/server/anuncios/([0-9]*)',function($id){
   if(controlAcess())((new controllerAnuncios())->findById($id));
},'get');

Route::add('/server/anuncios',function(){
    if(controlAcess())((new controllerAnuncios())->save());
},'put');

Route::add('/server/anuncios',function(){
    if(controlAcess())((new controllerAnuncios())->del());
},'delete');
//###############################################################################


//###############################################################################
Route::add('/server/usuarios',function(){
   if(controlAcess())((new controllerUsuarios())->find());
},'get');

Route::add('/server/usuarios/([0-9]*)',function($id){
   if(controlAcess())((new controllerUsuarios())->findById($id));
},'get');

Route::add('/server/usuarios',function(){
    if(controlAcess())((new controllerUsuarios())->save());
},'put');

Route::add('/server/usuarios',function(){
    if(controlAcess())((new controllerUsuarios())->del());
},'delete');
//###############################################################################

Route::add('/admin/(.*)',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/404.php');
},'get');
Route::run('/');
?>