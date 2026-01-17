<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');

$base_server_path_files=$_SERVER['DOCUMENT_ROOT'];
$base_url="https://$_SERVER[HTTP_HOST]";  	
require_once($GLOBALS["base_server_path_files"].'/route.php');
require_once($GLOBALS["base_server_path_files"].'/library/functions.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/mvc/model/usuariosDAO.php');

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

Route::add('/admin/apps/explorer',function(){
     // Shell only, auth via Client Side
     require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/apps/explorer.html');
},'get');

Route::add('/admin/apps/explorer',function(){
     require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/apps/explorer.html');
},'post');

Route::add('/admin/explorer',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/controller/explorer.php');
},'get');

Route::add('/admin/explorer',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/controller/explorer.php');
},'post');
//###############################################################################




//###############################################################################
Route::add('/admin/config',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_config.php');
},'get');

Route::add('/admin/usuarios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_usuarios.php');
},'get');

Route::add('/admin/menus',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_menus.php');
},'get');

Route::add('/admin/noticias',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_noticias.php');
},'get');

Route::add('/admin/noticiasAnexos/([0-9]+)',function($idNoticia){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_noticias_anexos.php');
},'get');

Route::add('/admin/noticiasFotos/([0-9]+)',function($idNoticia){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_noticias_fotos.php');
},'get');

Route::add('/admin/tiposAnuncios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_tipos_anuncios.php');
},'get');

Route::add('/admin/anuncios',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_anuncios.php');
},'get');

Route::add('/admin/anunciosFotos/([0-9]+)',function($idAnuncio){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_anuncios_fotos.php');
},'get');

Route::add('/admin/anunciosAnexos/([0-9]+)',function($idAnuncio){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_anuncios_anexos.php');
},'get');

Route::add('/admin/albumFotos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_album_fotos.php');
},'get');

Route::add('/admin/fotos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_fotos.php');
},'get');

Route::add('/admin/albumVideos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_album_videos.php');
},'get');

Route::add('/admin/videos',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/system/cadastro_videos.php');
},'get');
//###############################################################################



//###############################################################################

//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerUsuarios.php');
Route::add('/server/usuarios',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->find());
},'get');

Route::add('/server/usuarios/(.*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->findById($id));
},'get');

Route::add('/server/usuarios',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->create());
},'post');

Route::add('/server/usuarios/login',function(){
    ((new controllerUsuarios())->login());
},'post');

Route::add('/server/usuarios/logout',function(){
    ((new controllerUsuarios())->logout());
},'post');

Route::add('/server/usuarios/trocarSenha',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->trocarSenha());
},'post');

Route::add('/server/usuarios/(.*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->update($id));
},'put');

Route::add('/server/usuarios/(.*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->del($id));
},'delete');
//###############################################################################

Route::add('/server/usuarios/recovery',function(){
    $email = getParameter("e_mail");
    echo json_encode(recovery($email));
},'post');

Route::add('/server/usuarios/resetPassword',function(){
    ((new controllerUsuarios())->resetPassword());
},'post');

Route::add('/server/time_session',function(){
    if(new usuariosDAO([])->controlAcess())echo functionsJWT::sessionCount();
},'get');

Route::add('/server/userActive',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerUsuarios())->userActive());
},'get');

require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerMenus.php');
Route::add('/server/site/mainMenus',function(){
   ((new controllerMenus())->findMainMenus());
},'get');

Route::add('/server/site/subMenus/([0-9]*)',function($idMenu){
   ((new controllerMenus())->findSubMenus($idMenu));
},'get');

Route::add('/server/site/menusHierarchy',function(){
   ((new controllerMenus())->findMenusHierarchy());
},'get');

Route::add('/server/menus',function(){
   if(new usuariosDAO([])->controlAcess())(new controllerMenus())->find();
},'get');

Route::add('/server/menus/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerMenus())->findById($id));
},'get');

Route::add('/server/menus',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerMenus())->create());
},'post');

Route::add('/server/menus/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerMenus())->update($id));
},'put');

Route::add('/server/menus/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerMenus())->del($id));
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
   if(new usuariosDAO([])->controlAcess())((new controllerAlbumFotos())->find());
},'get');

Route::add('/server/albumFotos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerAlbumFotos())->findById($id));
},'get');

Route::add('/server/albumFotos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumFotos())->create());
},'post');

Route::add('/server/albumFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumFotos())->update($id));
},'put');

Route::add('/server/albumFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumFotos())->del($id));
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerFotos.php');

Route::add('/server/fotos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerFotos())->find());
},'get');

Route::add('/server/fotos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerFotos())->findById($id));
},'get');

Route::add('/server/fotos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerFotos())->create());
},'post');

Route::add('/server/fotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerFotos())->update($id));
},'put');

Route::add('/server/fotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerFotos())->del($id));
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
   if(new usuariosDAO([])->controlAcess())((new controllerAlbumVideos())->find());
},'get');

Route::add('/server/albumVideos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerAlbumVideos())->findById($id));
},'get');

Route::add('/server/albumVideos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumVideos())->create());
},'post');

Route::add('/server/albumVideos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumVideos())->update($id));
},'put');

Route::add('/server/albumVideos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAlbumVideos())->del($id));
},'delete');
//###############################################################################

//###############################################################################
require($GLOBALS["base_server_path_files"].'/mvc/controller/controllerVideos.php');
Route::add('/server/videos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerVideos())->find());
},'get');

Route::add('/server/videos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerVideos())->findById($id));
},'get');

Route::add('/server/videos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerVideos())->create());
},'post');

Route::add('/server/videos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerVideos())->update($id));
},'put');

Route::add('/server/videos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerVideos())->del($id));
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
   if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->find());
},'get');

Route::add('/server/noticias/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->findById($id));
},'get');

Route::add('/server/noticias/quillUpload',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->quillUpload());
},'post');

Route::add('/server/noticias',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->create());
},'post');

Route::add('/server/noticias/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->update($id));
},'put');

Route::add('/server/noticias/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticias())->del($id));
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerNoticiasAnexos.php');
Route::add('/server/noticiasAnexos',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerNoticiasAnexos())->find());
},'get');

Route::add('/server/noticiasAnexos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerNoticiasAnexos())->findById($id));
},'get');

Route::add('/server/noticiasAnexos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasAnexos())->create());
},'post');

Route::add('/server/noticiasAnexos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasAnexos())->update($id));
},'put');

Route::add('/server/noticiasAnexos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasAnexos())->del($id));
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerNoticiasFotos.php');
Route::add('/server/noticiasFotos',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerNoticiasFotos())->find());
},'get');

Route::add('/server/noticiasFotos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerNoticiasFotos())->findById($id));
},'get');

Route::add('/server/noticiasFotos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasFotos())->create());
},'post');

Route::add('/server/noticiasFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasFotos())->update($id));
},'put');

Route::add('/server/noticiasFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerNoticiasFotos())->del($id));
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerTiposAnuncios.php');
Route::add('/server/tiposAnuncios',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerTiposAnuncios())->find());
},'get');

Route::add('/server/tiposAnuncios/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerTiposAnuncios())->findById($id));
},'get');

Route::add('/server/tiposAnuncios',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerTiposAnuncios())->create());
},'post');

Route::add('/server/tiposAnuncios/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerTiposAnuncios())->update($id));
},'put');

Route::add('/server/tiposAnuncios/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerTiposAnuncios())->del($id));
},'delete');
//###############################################################################


//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAnuncios.php');
Route::add('/server/site/banners/(.*)',function($nameType){
   ((new controllerAnuncios())->findbyType(myUrlDecode($nameType)));
},'get');

Route::add('/server/anuncios',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerAnuncios())->find());
},'get');

Route::add('/server/anuncios/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerAnuncios())->findById($id));
},'get');

Route::add('/server/anuncios',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAnuncios())->create());
},'post');

Route::add('/server/anuncios/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnuncios())->update($id));
},'put');

Route::add('/server/anuncios/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnuncios())->del($id));
},'delete');
//###############################################################################



//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAnunciosFotos.php');
Route::add('/server/anunciosFotos',function(){
   if(new usuariosDAO([])->controlAcess())((new controllerAnunciosFotos())->find());
},'get');

Route::add('/server/anunciosFotos/([0-9]*)',function($id){
   if(new usuariosDAO([])->controlAcess())((new controllerAnunciosFotos())->findById($id));
},'get');

Route::add('/server/anunciosFotos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosFotos())->create());
},'post');

Route::add('/server/anunciosFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosFotos())->update($id));
},'put');

Route::add('/server/anunciosFotos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosFotos())->del($id));  
},'delete');
//###############################################################################



//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerAnunciosAnexos.php');
Route::add('/server/anunciosAnexos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosAnexos())->find());
},'get');

Route::add('/server/anunciosAnexos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosAnexos())->findById($id));
},'get');

Route::add('/server/anunciosAnexos',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosAnexos())->create());
},'post');

Route::add('/server/anunciosAnexos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosAnexos())->update($id));
},'put');

Route::add('/server/anunciosAnexos/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerAnunciosAnexos())->del($id));
},'delete');
//###############################################################################



//###############################################################################
require_once($GLOBALS["base_server_path_files"].'/mvc/controller/controllerConfig.php');
Route::add('/server/configs',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerConfig())->find());
},'get');

Route::add('/server/configs/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerConfig())->findById($id));
},'get');

Route::add('/server/configs',function(){
    if(new usuariosDAO([])->controlAcess())((new controllerConfig())->create());
},'post');

Route::add('/server/configs/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerConfig())->update($id));
},'put');

Route::add('/server/configs/([0-9]*)',function($id){
    if(new usuariosDAO([])->controlAcess())((new controllerConfig())->del($id));
},'delete');
//###############################################################################



Route::add('/admin/(.*)',function(){
    require_once($GLOBALS["base_server_path_files"].'/mvc/view/admin/404.php');
},'get');
Route::run('/');
?>