<?php 

namespace MVC\Model;

class Produto extends Model {

	public function __construct(){
		$this->setTable('produtos');
	}

	public function addProduto(array $produto){
		$tem_erros = $this->invalid($produto);
		if(!$tem_erros){
			$rr= $this->add($produto); #ele vai retornar 0 pq o id nao e serial
				#echo $rr;die;
			return $rr;
		} 
		return (array) $tem_erros;
	}

	/** 
	Para a vitrine da home
	*/
	function getProdutosByRand(int $qtd=20): array {
	    $tabela = $this->getTable();
	    $sql = sprintf("SELECT * FROM `$tabela` ORDER BY rand() LIMIT $qtd;");

	    return $this->query($sql);
	}

	/** 

	*/
	function getProdutosByCategId($categ_id,string $campos='*') {
	    $where="categ_id=?";
	    $tabela = $this->getTable();
	    $sql = sprintf("SELECT %s FROM %s WHERE categ_id=?;",$campos,$tabela,$where);

	    $valores=[$categ_id];
	    return $this->query($sql, $valores);
	}



	/** 
	Como a instrucao LIKE segue formatacao diferente, criei este metodo so para isso.
	As demais buscas vao direto para o BD.
	*/
	function getProdutosByNome($nome,string $campos='*') {
	    $where="nome=?";
	    $tabela = $this->getTable();
	    $sql = sprintf("SELECT %s FROM %s WHERE lower(nome) LIKE ?;",$campos,$tabela,$where);

	    $valores=['%'.strtolower($nome).'%'];
	    return $this->query($sql, $valores);
	}


	/** 
	Essa e a funcao que faz a checagem antes de mandar para o BD
	*/
	function invalid(array $produto){
		extract($produto);
		$errors=[];
		if(!$this->valida__nome($nome)){
			$errors['nome']="nome invalido";
		}
		return empty($errors)? false : $errors;

	}

	/** 
	Aqui vem todas as checagens possiveis
	*/
	private function valida__nome(string $nome) : bool {
		return strlen($nome) > 5 && strlen($nome) < 80;

	}

	function retornaDisponibProduto($estoque,$prazo){
	    if($estoque < 1){
	        $disponib = ($prazo == 'E')? "esgotado" : $prazo." dia(s)";
	    } else {
	        $disponib = "Em stok ($estoque)";
	    }
	    return $disponib;
	}


}