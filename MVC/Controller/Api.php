<?php 

namespace MVC\Controller;

class Api extends Controller{
	
	public function __construct(){

	}
	
	private function injeta_arrays(){
		$arr['sangues'] = (new \MVC\Model\Model())->setTable('tiposanguineo')->getAll();
		$arr['cidades'] = (new \MVC\Model\Model())->setTable('cidade')->getAll();
		$arr['estados'] = (new \MVC\Model\Model())->setTable('estado')->getAll();
		$arr['planos'] = (new \MVC\Model\Plano())->getAll();
		$arr['pacientes'] = (new \MVC\Model\Paciente())->getAll(); 
		return $arr;
	}

	public function get_index(){
		http_response_code(403);die('Forbidden');
	}

	private function responseError($txt){
		$array['ERROR']=$txt;
		$this->response($array,400);
	}
	private function responseOK($txt,int $code=200){
		$array['SUCCESS']=$txt;
		$this->response($array,$code);
	}

	private function response($array,int $code=200){
		http_response_code($code);
		header('Content-Type: application/json');
		echo json_encode($array,true);die;	
	}

	################################################################### PACIENTES #####################
	public function get_pacientes(){
		$this->response((new \MVC\Model\Paciente())->getAll());
	}

	public function post_buscapacientes($params){
		extract($params['post']);
		#var_dump($busca);die;

		$sql = "SELECT * FROM paciente WHERE ";
		if(preg_match("/^\d{2}\/\d{2}\/\d{4}$/",$busca)){
			$sql .= "datanascimento=? ";
			$param=['datanascimento'=>$busca];
		} else {
			$sql .= "lower(nome) LIKE '%?%' ";
			$param=['nome'=>$busca];
		}

		$order = $_GET['order']??null;
		$sens = $_GET['sens']??null;

		$order_by= " ORDER BY ". $order . " " . $sens;
		$pag = $_GET['pag']?? 1;

		$arr = (new \MVC\Model\BD())->query($sql,$param); 
		return json_encode($arr,true);
	

		$nome = (string) $post['get']['nome'];
		$sql = "SELECT * FROM planos WHERE lower(nome) LIKE ?;";
		$param=['%'.strtolower($nome).'%'];
		$arr = (new \MVC\Model\BD())->query($sql,$param); 
		return empty($arr)? $this->responseError("Nenhum paciente econtrado") : $this->response($arr);
	}


	public function post_patchpaciente(array $params){

		extract($params); #get e post
		extract($get); #id
		extract($post); #nome sobrenome email
		$paciente = compact('id','nome','sobrenome','email');

		#$plano = (array) $post['get'];

		$model = new \MVC\Model\Paciente();
		if(!$errors=$model->invalid($paciente)){
			if($model->upd($paciente)){
				$this->responseOK("Paciente atualizado com sucesso.",202);
			} else {
				$this->responseError("Erro interno do BD.");
			}
		} else {
			$this->responseError("Paciente invalido :.". implode("<br>",$errors));
		}


	}

	public function get_delpaciente(array $params){
		#colocar CSRF depois

		$id = (int) $params['get']['id'];
		$model = new \MVC\Model\Paciente();

		if($model->getOne($id)){
			#var_dump(delOneBy($id));die;
			if($model->delOneBy($id)){
				$this->responseOK("Registro #$id excluido com sucesso.");
			} else {
				$this->responseError("Erro interno do BD.");
			}
		} else {
			$this->responseError("Registro #$id inexistente.");
		}
	}


	################################################################### PLANOS #####################
	public function get_planos(){
		$this->response((new \MVC\Model\Plano())->getAll());
	}

	public function get_buscaplanos(array $post){
		$nome = (string) $post['get']['nome'];
		
		$sql = "SELECT * FROM planos WHERE lower(nome) LIKE ?;";
		$param=['%'.strtolower($nome).'%'];

		$arr = (new \MVC\Model\BD())->query($sql,$param); 
		return empty($arr)? $this->responseError("Nenhum plano econtrado") : $this->response($arr);
	}


	public function get_addplano(array $post){
		$plano = (array) $post['get'];

		$model = new \MVC\Model\Plano();
		if(!$errors=$model->invalid($plano)){
			if($model->add($plano)){
				$this->responseOK("Plano inserido com sucesso.",201);
			} else {
				$this->responseError("Erro interno do BD.");
			}
		} else {
			$this->responseError("Plano invalido :.". implode("<br>",$errors));
		}
	}

	public function get_updplano(array $post){
		$plano = (array) $post['get'];

		$model = new \MVC\Model\Plano();
		if(!$errors=$model->invalid($plano)){
			if($model->upd($plano)){
				$this->responseOK("Plano atualizado com sucesso.",202);
			} else {
				$this->responseError("Erro interno do BD.");
			}
		} else {
			$this->responseError("Plano invalido :.". implode("<br>",$errors));
		}
	}

	public function get_delplano(array $params){
		$id = (int) $params['get']['id'];
		$model = new \MVC\Model\Plano();

		if($model->getOne($id)){
			#var_dump(delOneBy($id));die;
			if($model->delOneBy($id)){
				$this->responseOK("Registro #$id excluido com sucesso.");
			} else {
				$this->responseError("Erro interno do BD.");
			}
		} else {
			$this->responseError("Registro #$id inexistente.");
		}

	}

	public function get_selsubtabela(array $params){
		$tabela=$params['get']['tabela'];
		return $this->response((new \MVC\Model\Model())->setTable($tabela)->getAll('nome as tag,id')); 
	}

	public function post_addsubtabela(array $params){
		$tabela=$params['get']['tabela'];
		$nome = (string) $params['post']['nome'];
		$array = ['nome'=>$nome];
		(new \MVC\Model\Model())->setTable($tabela)->add($array)? $this->responseOK("Registro incluido.") : $this->responseError("Erro interno do BD.");
	}

	public function post_delsubtabela(array $params){
		$tabela=$params['get']['tabela'];
		$id = (int) $params['post']['id'];

		(new \MVC\Model\Model())->setTable($tabela)->delOneBy($id)? $this->responseOK("Registro excluido.") : $this->responseError("Erro interno do BD.");
	}

}
