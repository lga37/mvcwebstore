<?php 
namespace MVC;

use MVC\View\View as View;

class Bootstrap {

	public static function run(){
		self::init();
		self::autoload();
		#self::errorHandlers();
		self::dispatch();
	}

	private static function errorHandlers(){

		set_error_handler(function($code, $message, $file, $line){
		    if (0 == error_reporting()){ 
		        return; 
		    } 
		    throw new \ErrorException($message, 0, $code, $file, $line); 

		}); 
		set_exception_handler(function($e){
			self::error($e->getMessage());
		}); 		

	}


	private static function init(){
		setlocale(LC_ALL,'pt_BR');

		$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);            
		$_COOKIE  = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING);            
		$_SERVER = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);            

		if(getenv('APPLICATION_ENV')==="DEVELOP"){
			error_reporting(E_ALL);
			ini_set('display_startup_errors',TRUE);
			ini_set('display_errors',TRUE);
		}	

		@include 'config/config.dev.php';
		define('SITE','stone');
		define('UPLOADS','assets/img/produtos/');
        #@chmod(UPLOADS, 0777);


		$url = sprintf("http://%s%s",$_SERVER["SERVER_NAME"],$_SERVER['SCRIPT_NAME']);
		define('URL',$url);

		if(!session_id()){
			session_start();
		}
	}

	private static function autoload(){
		spl_autoload_extensions(".php");
		spl_autoload_register(function($class){
			$class = ucfirst($class);
		    $root = dirname(__DIR__);
		    
		    $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
		    if (is_readable($file)) {
		        require $file;
		    }

		});

	}

	private static function dispatch(){
		$controller = $_GET['c'] ?? 'Home';
		$action = $_GET['a'] ?? 'index';

		$controller = 'MVC\\Controller\\'.ucfirst($controller);

		$verb = strtolower($_SERVER['REQUEST_METHOD']);
		$action = sprintf("%s_%s",$verb,$action);

		if(isset($_GET['c'])) unset($_GET['c']);
		if(isset($_GET['a'])) unset($_GET['a']);
		
		$params=[];
		#var_dump($_REQUEST);die; ######### REQUEST junta os 2
		if($_POST) $params['post']=$_POST;
		if($_GET) $params['get']=$_GET;
		if($_FILES) $params['files']=$_FILES;

		if(class_exists($controller)){
			$class = new $controller();
			if(method_exists($class, $action)){
				call_user_func([$class,$action], $params); #cuidado com a analoga call_user_func_array que come o array bidimensional
			} else {
				self::error("Metodo ($action) nao Encontrado no controller ($controller)");
			}
		} else {
			self::error("Classe ($controller) Nao Encontrada");
		}
	}


	private static function error($msg){
		$v = new View();
		$v->setTemplate('erro.php');
		$v->msg=$msg;
		$v->show();
		die;
	}




}