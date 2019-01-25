<?php

namespace MVC\Controller;
use MVC\Model\Produto;

class Carrinho extends Controller{

    public $cart;

    public function __construct(){
        $this->cart = new \MVC\Model\Carrinho();
        parent::__construct('carrinho.php');
    }

    public function get_index(){
        $this->view->show();
    }
    
    public function get_add(array $get){
        $id = (int) $get['get']["id"];
        $this->cart->add($id);
        $this->view->show();

    }

    public function get_del(array $get){
        $id = (int) $get['get']["id"];
        $this->cart->del($id);
        $this->view->show();

    }

    public function post_upd(array $post){
        $id = (int) $_GET['id']; #gambi
        $qtd = (int) $post['post']['qtd'];
        $this->cart->upd($id,$qtd);
        $this->view->show();
    }


    public function post_frete(array $post){
        $cep = (int) $post['post']['cep'];
        $this->cart->setCep($cep)->recalcFrete();
        $this->view->show();
    }


    public function get_clear(): void{
        $this->cart->clear();
        $this->view->show();

    }

    public function get_end(): void{
        $this->redirect(URL.'?c=cliente');
    }

}