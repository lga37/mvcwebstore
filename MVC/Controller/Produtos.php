<?php

namespace MVC\Controller;


class Produtos extends Controller{

    public $produtos=[];
    public $produtoModel;

    public function __construct(){

        #var_dump($this->model);
        parent::__construct('listagem.php');
        $this->produtoModel = new \MVC\Model\Produto();
    }

    public function get_listagem(){
        #busca palavra chave / categ_id / se nehum dos 2 -> redireciona home
            #neste caso nao veio nem pelo GET e nem pelo POST. Redireciono de volta p index.
        
    }


    public function get_produtoscateg(array $get){
        #$query['id'] = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $id = (int) $get['get']['id'];
        $this->view->produtos = $this->produtoModel->getProdutosByCategId($id);
        $this->view->show();

    }


    public function post_produtosnome(array $post){
        $q = (string) filter_var($post['post']['q'],FILTER_SANITIZE_STRING);

        $this->view->produtos = $this->produtoModel->getProdutosByNome($q);
        $this->view->show();

    }


    public function get_detalhe(array $get){
        $this->view->setTemplate('detalhes.php');
        #var_dump($get);
        extract($get['get']);#id
        if(isset($id) && is_numeric($id)){
            #$query['id'] = (int) filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            $id = (int) $id;
            $this->view->produto = $this->produtoModel->getOne($id);
            #print_r($produtos);die;
        } else {
            #neste caso nao veio nem pelo GET e nem pelo POST. Redireciono de volta p index.
            #header("index.php");die;
            $this->view->msg = "erroooooooo";
        }
        $this->view->show();
    }


}
