<?php 

namespace MVC\Controller;

class Cliente extends Controller{

	public function __construct(){
		parent::__construct('cliente_login.php');
	}

	public function get_index(){
		$this->view->show();
	}

	public function get_login_index(){
		$this->view->show();
	}


	public function post_login_index(array $post){
		#echo "<pre>";print_r($post);	
		extract($post['post']);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha)){
			$this->view->msg('Erro',"Email/Senha invalidos");

		} else {
			$cliente = $this->model->setTable('clientes')->getOneBy($email,'*','email');
			#echo "<pre>";print_r($cliente);	
			if(!$cliente){
				$this->view->msg('Erro','Email nao cadastrado no BD');
			} else {
				$hash=$cliente['senha'];
				if(!password_verify($senha,$hash)){
					#echo "ggg";
					$this->view->msg('Erro',"Senha invalida para o email $email");
				} else {
					#echo "hhhh";
					$_SESSION["logado_cli"]=$cliente;session_regenerate_id();
					$this->get_cadastro();
				}
			}
			
		}
		
		$this->view->show();

	}

	public function post_cadastro_index(array $post){
		if($this->invalid($post['post'])){
			$errors = $this->invalid($post['post']);
			$this->view->msg('Erro',"Erros encontrados:.<hr>".implode('<br>',$errors));
		} else {
			extract($post['post']);

			$cliente = $this->model->setTable('clientes')->getOneBy($email,'*','email');
			if($cliente){
				$this->view->msg('Erro','Email ja cadastrado no BD');
			} else {
				$senha = password_hash($senha,PASSWORD_BCRYPT);
				$cliente = compact('nome','email','senha','endereco');
				if($id=$this->model->setTable('clientes')->add($cliente)){
					$cliente=$this->model->setTable('clientes')->getOneBy($id);
					$_SESSION["logado_cli"]=$cliente;session_regenerate_id();
					$this->get_cadastro();
				} else {
					$this->view->msg('Erro','Erro ao inserir no BD');
				}
			}
		}		
		$this->view->show();

	}

	public function get_logout(){
		$this->clear()->get_index();
	}


	public function get_cadastro(){
		$this->view->setTemplate('cliente_cadastro.php');
		$this->view->cart = $_SESSION['cart']??false;;
		$this->view->cliente = $_SESSION['logado_cli']??false;

		$this->view->show();
	}

	
	private function auth(){
		if(!isset($_SESSION["logado_cli"])){
			$this->get_index();
		}
	}


	public function post_finaliza(array $post){

        #Aqui deveria usar transaction pois temos 2 transacoes envolvidas
        #$cn->beginTransaction();      
        #var_dump($_SESSION);

        $cliente = $post['post'];
        $cliente['id']=(int) $_SESSION['logado_cli']['id'];
        if(!empty($senha)){
        	$cliente['senha'] = password_hash($senha,PASSWORD_BCRYPT);	
        }
       	$res = $this->model->setTable('clientes')->upd($cliente);
       	#echo "<pre>";print_r($res);
      	
        if(isset($_SESSION['cart'])){
	        try{
		        $pedido['ip']= (string) $_SERVER['REMOTE_ADDR'];
		        $pedido['frete']= (float) $_SESSION['frete'];
		        $cli_id=$_SESSION['logado_cli']['id'];
		        $pedido['cli_id']=(int) $cli_id;
	            $ped_id = $this->model->setTable('pedidos')->add($pedido);
	            if($ped_id){
	                $res = $this->model->setTable('itens')->delOneBy($ped_id,'ped_id');
	            	#var_dump($res);die;
	                
	                foreach($_SESSION['cart'] as $k=>$produto){
	                    #print_r($produto);
	                    extract($produto);

	                    $item['ped_id']= (int) $ped_id;
	                    $item['prod_id']= (int) $id;
	                    $item['qtd']=(int) $qtd;
	                    $this->model->setTable('itens')->add($item);

	                }
	            }else{
	                throw new \Exception("Erro ao salvar pedidos");
	            }    
	        } catch(\Exception $e){
				$this->view->msg("ERRO",$e->getMessage(),"danger");
	            #$cn->rollback();
	        }
	        #$cn->commit();      
			$assunto = "Pedido #$ped_id finalizado com sucesso";
			#$msg=print_r($_SESSION['cart']);
			$this->view->msg("OK",$assunto,"success");
			#enviaEmail(GMAIL,SITE,SENHA_GMAIL,$email,$nome,$assunto,$msg)
	        unset($_SESSION['frete']);
	        unset($_SESSION['cart']);session_regenerate_id();
			#(new \MVC\Model\Carrinho())->clear();			
			#se quiser faz o controle de estoque aqui
			$this->view->show();
        	
        } else {
        	$this->get_index();
        }
	}



	private function invalid(array $cliente){
		$errors=[];
		extract($cliente);
		if(array_key_exists('nome', $cliente)){
			if(!(strlen($nome)>=8 && strlen($nome)<=80)){
				$errors['nome']="Nome deve ter entre 8 e 80";
			}
		}

		if(array_key_exists('email', $cliente)){
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors['email']="Email invalido";
			}
		}

		if(array_key_exists('senha', $cliente)){
			if(!(strlen($senha)>=3 && strlen($senha)<=20)){
				$errors['senha']="Senha deve ter entre 3 e 20";
			}
		}


		if(array_key_exists('endereco', $cliente)){
			if(!(strlen($endereco)>=8 && strlen($endereco)<=80)){
				$errors['endereco']="Endereco deve ter entre 8 e 80";
			}
		}


		return !empty($errors)? $errors : false;
	}
}
