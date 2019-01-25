<?php 

namespace MVC\Controller;

class Admin extends Controller{
	
	public function __construct(){
		parent::__construct('admin_login.php');

	}
	

	public function get_index(){
		#$this->redirect(URL.'?c=admin&a=home');

		if(isset($_SESSION['logado_adm']) && $_SESSION['logado_adm']==="sim"){
			$this->get_home();
		}
		$this->view->show();
	}

	public function post_index($post){
		extract($post['post']);
		if($login==="adm" && $senha==="123"){
			#qq coisa que se faz aqui antes p limpar a session da pau
			$_SESSION["logado_adm"]="sim";session_regenerate_id();
			$this->redirect(URL.'?c=admin&a=home');
		} else {
			$this->view->msg('Erro','Login ou Senha Invalidos.');
			$this->view->show();
		}	

	}

	public function get_logout(){
		unset($_SESSION);session_regenerate_id();
		$this->get_index();
	}


	public function get_home(){
		$this->view->setTemplate('admin_home.php');
		$this->view->show();
	}

	############################################################## CATEGS

	public function get_categs(){
		$this->view->categorias = $this->model->setTable('categorias')->getAll();
		$this->view->setTemplate('admin_crud_categs.php');
		$this->view->show();
	}

	public function get_del_categ(array $get){
		$id = (int) $get['get']['id'];
		if($this->model->setTable('categorias')->delOneBy($id)){
			$this->view->msg("OK","Categ #$id excluida","success");
		} else {
			$this->view->msg("ERRO","Erro ao excluir Categ","danger");
		}
		$this->get_categs();
	}


	public function post_addcateg(array $post){
		$nome = (string) $post['post']['nome'];
		$categoria = compact('nome');
		$model = new \MVC\Model\Categoria();
		$res = $model->add($categoria);
		#var_dump($res);die;
		if(is_string($res) && substr($res,0,5)==="ERROR"){
			$this->view->msg("ERRO","Categ nao inserido<br>$res","danger");
		} elseif(is_array($res)) {
			$this->view->msg("ERRO","Categ invalido :<hr>". implode("<br>",$res),"danger");
		} else {	
			$this->view->msg("OK","Categ ($res) inserido","success");
		}
		$this->get_categs();
	}


	public function post_upd_categs(array $post){
		$id = (int) $_GET['id']; #juntar get e post depois
		$nome = (string) $post['post']['nome'];
		$categoria = compact('id','nome');
		$model = new \MVC\Model\Categoria();
		$res = $model->upd($categoria);
		#var_dump($res);die;
		if(is_string($res) && substr($res,0,5)==="ERROR"){
			$this->view->msg("ERRO","Categ nao atualizada<br>$res","danger");
		} elseif(is_array($res)) {
			$this->view->msg("ERRO","Erro BD - Categ invalido :<hr>". implode("<br>",$res),"danger");
		} else {	
			$this->view->msg("OK","Categ #$id atualizada","success");
		}
		$this->get_categs();
	}


	############################################################## CLIENTES

	public function get_clientes(){

		extract($this->parsePaginacaoOrdenacao()); #order sens where pag modo
		$this->view->setTemplate('admin_crud_clientes.php');

		$where = $order .' '. $sens;

		$model = new \MVC\Model\Cliente();
		$total = $model->count();

		$this->view->total=$total;
		$this->view->clientes= $model->getAllPaginate('*',$where,$pag); 
		$this->view->show();
	}

	public function post_busca_cliente($params){
		extract($params['post']);
		#var_dump($busca);die;

		$model = new \MVC \Model\Cliente();
		if($clientes=$model->getclientesByNomeDataNasc($busca)){


			$this->view->setfull=true;
			$this->injeta_arrays();

			$this->view->clientes = $clientes; 
			$this->view->total = (int) count($clientes);

			$this->view->setTemplate('admin_crud_clientes.php');
			$this->view->msg("OK","Encontrados ".count($clientes)." clientes para '$busca'","success");
			$this->view->show();
		} else {
			$this->view->msg("ERRO","cliente nao encontrado","danger");
			$this->get_clientes();
		}

	}

	public function get_upd_cliente(array $params){

		#colocar CSRF depois
		$id = (int) $params['get']['id'];

		$this->view->setTemplate('cliente_cadastro.php');
		$cliente = (new \MVC\Model\cliente())->getOne($id);
		if(!empty($cliente)){
			$this->view->cliente = $cliente[0];
			$this->view->show();
		} else {
			$this->view->msg("ERRO","Cliente nao cadastrado","danger");
			$this->get_clientes();
		}
	}

	public function get_del_cliente(array $params){
		#colocar CSRF depois
		$id = (int) $params['get']['id'];
		if($affected_rows=(new \MVC\Model\Cliente())->delOneBy($id)){
			$this->view->msg("OK","cliente #$id deletado com sucesso ($affected_rows excluido(s)","success");
		} else {
			$this->view->msg("ERRO","Cliente nao cadastrado","danger");
		}
		#$this->redirect('?c=admin&a=clientes');# tem q usar o redirect senao ele mantem o action upd/del e o id | porem perde a msg flash
		$this->get_clientes();

	}



	############################################################## PRODUTOS

	public function get_produtos(){

		$this->view->setTemplate('admin_crud_produtos.php');
		extract($this->parsePaginacaoOrdenacao()); #order sens where pag modo
		$where = $order .' '. $sens;

		$model = new \MVC\Model\Produto();
		$total = (int) $model->count();

		$this->view->total=$total;
		$this->view->produtos= $model->getAllPaginate('*',$where,$pag); 
		$this->view->categorias= $model->setTable('categorias')->getAll(); 
		
		$this->view->show();
	}

	public function post_busca_produto(array $post){
		$nome = $post['post']['nome'];
		$model = new \MVC\Model\Plano();
		if($planos=$model->getPlanosByNome($nome)){
			$this->view->planos=$planos;
			
			$this->view->total=(int) count($planos);

			extract($this->parsePaginacaoOrdenacao()); #order sens where pag modo
			$where = $order .' '. $sens;

			$this->view->setTemplate('admin_crud_planos.php');
			$this->view->msg("OK","Encontrados ".count($planos)." planos para '$nome'","success");
			$this->view->show();
		} else {
			$this->view->msg("ERRO","Plano nao encontrado","danger");
			$this->get_produtos();
		}
	}

	# se tiver i id e um update caso contrario e um insert
	public function post_save_produto(array $params){
		#echo "<pre>";print_r($params);die;
		extract($params['files']['foto']);
		#se vier vazio - name,type,tmp_name vem vazio e error=4 size=0
		if(!empty($name)){
			$upl = $this->upload($name,$type,$tmp_name,$error,$size);		
			$path = UPLOADS;
			$pattern = "#".$path."#"; ########## Atencao acho que no PHP7.2 mudou
			$foto = is_array($upl)? "" : preg_replace($pattern, "", $upl);
		}

		#var_dump($foto);die;
		extract($params['post']);

		$produto = compact('nome','categ_id','preco','prazo','peso','estoque');
		if(isset($id) && is_numeric($id)){
			$produto['id'] = $id;
		}
		if(!empty($name)){
			$produto['foto'] = $foto;
		}

		#echo "<pre>";print_r($produto);die;


		$model = new \MVC\Model\Produto();
		$res = $model->save($produto);

		if(is_string($res) && substr($res,0,5)==="ERROR"){
			$this->view->msg("ERRO","Produto nao atualizado<br>$res","danger");
		} elseif(is_array($res)) {
			$this->view->msg("ERRO","Produto invalido :<hr>". implode("<br>",$res),"danger");
		} else {	
			$this->view->msg("OK","Produto atualizado","success");
		}
		
		$this->get_produtos();
		#$this->redirect('?c=admin&a=planos');# tem q usar o redirect senao ele mantem o action upd/del e o id || porem perco a msg flash 
	}


	public function get_del_produto(array $params){
		$id = (int) $params['get']['id'];
		if($affected_rows=(new \MVC\Model\Produto())->delOneBy($id)){
			$this->view->msg("OK","Produto #$id deletado com sucesso ($affected_rows excluido(s))","success");
		} else {
			$this->view->msg("ERRO","Produto nao inserido","danger");
		}
		$this->get_produtos();
		#$this->redirect('?c=admin&a=planos');# tem q usar o redirect senao ele mantem o action upd/del e o id
	}



	############################################################## PEDIDOS

	public function get_pedidos(){

		extract($this->parsePaginacaoOrdenacao()); #order sens where pag modo
		$this->view->setTemplate('admin_crud_pedidos.php');

		$where = $order .' '. $sens;

		$model = new \MVC\Model\Pedido();
		$total = $model->count();

		$this->view->total=$total;
		$this->view->pedidos= $model->getAllPaginate('*',$where,$pag); 
		$this->view->show();
	}

	public function get_del_pedido(array $params){
		#colocar CSRF depois
		$id = (int) $params['get']['id'];
		$del_itens = $this->model->setTable('itens')->delOneBy($id,'ped_id');
		if($affected_rows=(new \MVC\Model\Pedido())->delOneBy($id)){
			$this->view->msg("OK","Pedido #$id deletado com sucesso ($affected_rows excluido(s)","success");
		} else {
			$this->view->msg("ERRO","Pedido nao cadastrado","danger");
		}
		$this->get_pedidos();
	}









}
