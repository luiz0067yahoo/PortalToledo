<?php
    require_once ($_SERVER['DOCUMENT_ROOT'].'/library/functions.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/model/usuarioDAO.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/mvc/controller/controller.php');
	class controllerUsuario
	extends controller
	{
		public function __construct(){
			parent::__construct(new usuarioDAO(
		        [
		        "id"=>getParameter("id"),
		        "nome"=>getParameter("nome"),
		        "login"=>getParameter("login"),
		        "senha"=>getParameter("senha"),
		        "e_mail"=>getParameter("e_mail")
                ]
		    ));
		}
	}
?>