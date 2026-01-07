<?php
	define('JWT_SECRET_KEY', '407bec231a91b510ccdc909587acccd801f2f26e'); // Mude para algo forte e único
	define('JWT_SECRET_KEY_2', '8ffa387904406f75044bc247930d1161a34f9658'); // Segunda chave para criptografar user_id e session_id
	define('JWT_TIME', 3600);
	class conect {
		public static $instance;
		public static $servername_		=	"localhost";
		public static $username_		=	"u455891610_portal_toledo";
		public static $password_	=	"moa;V15+M";
		public static $database_	=	"u455891610_portal_toledo";
		private function __construct() {
		}
		public static function getInstance() {
			if (!isset(self::$instance)) {
				self::$instance = new PDO("mysql:host=".self::$servername_.";dbname=".self::$database_.";", self::$username_, self::$password_,
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
				);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE,
				PDO::ERRMODE_EXCEPTION);
				self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS,
				PDO::NULL_EMPTY_STRING);
			}
			return self::$instance;
		}
		public static function close() {
			self::$instance=null;
		}
	}





?>
