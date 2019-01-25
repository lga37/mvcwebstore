<?php 

namespace MVC\Controller;
use MVC\Model\Produto;

class Home extends Controller{
	
	public function __construct(){
		parent::__construct('home.php');
	}
	
	public function get_index(){
		$this->view->msg='Ola Mundo';
		$this->view->produtos = (new Produto())->getProdutosByRand();
		$this->view->show();
	}

}
