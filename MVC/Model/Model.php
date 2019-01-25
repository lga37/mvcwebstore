<?php 

namespace MVC\Model;

/**

*/


class Model extends BD{

	private $table;
	private $pk='id';

	/**

	*/
	public function setTable($t){
		$this->table=$t;
		return $this;
	}

	public function getTable(){
		return $this->table;
	}

	/**

	*/
	public function setPK($pk){
		$this->pk=$pk;
	}

	public function getPK(){
		return $this->pk;
	}


	/**

	*/
	function getOneBy($valor,string $campos='*',string $where='id') {
	    $PK=$where."=?";
	    $tabela = $this->getTable();
	    $sql = sprintf("SELECT %s FROM %s WHERE %s LIMIT 1;",$campos,$tabela,$PK);

	    $valores=[$valor];
	    $res = $this->query($sql, $valores);

	    return array_key_exists(0, $res)?$res[0]:$res; 
	}


	/**

	*/
	function getOne($valor,string $campos='*') {
	    $PK=$this->getPK()."=?";
	    $sql = sprintf("SELECT %s FROM %s WHERE %s LIMIT 1;",$campos,$this->getTable(),$PK);
	    $valores=[(int) $valor];

	    #echo $sql;
	
	    $res = $this->query($sql, $valores);

	    return array_key_exists(0, $res)?$res[0]:$res; 
	}


	#function getAllBySQL($sql)
	#manda a funca para a query direto formato : SELECT xxx FROM tabela WHERE a=? and b=? -- junto com ['a'=>123,'b'=>'abc']


	/**

	*/
	function getAll(string $campos='*') {
	    $sql = sprintf("SELECT $campos FROM %s;",$this->getTable());
	    $retorno = $this->query($sql);
	    #var_dump($retorno);die;
	    return $retorno;
	}

	/**

	*/
	function getAllPaginate(string $campos='*',string $order='id ASC',int $pag=1){
	    $sql = sprintf("SELECT $campos FROM %s ",$this->getTable());
		return $this->query($sql, [], $order, $pag);
	}

	/**

	*/
	function count(): int{
		$res = $this->getAll('count(1) as total');
		#var_dump($res);die;
		return $res[0]['total'];
	}


	/**

	*/
	function save(array $post){
		$pk = $this->getPK();
		return array_key_exists($pk, $post) && is_numeric($post[$pk])? $this->upd($post) : $this->add($post);
	}



	/**

	*/
	function upd(array $post){
	    $tabela = $this->getTable();
	    if(count($post) > 0){

	    	$pk = $this->getPK();
	        ########################## usar array_push para por o id no final
	        if(!array_key_exists($pk, $post)){
  	        	echo json_encode(['ERROR'=>'Nao tem chave primaria'],true);die;
	        }

	        $id=(int) $post[$pk];
	        unset($post[$pk]);
	        $where=$pk."=?";

	        $valores=array_values($post);#fazer antes de eliminar a pk
	        array_push($valores, $id); #colocar o id no final
	        $campos=array_keys($post);
	        $campos=implode("=?,",$campos);
	        $campos.="=?";
	        
	        $sql = "UPDATE $tabela SET %s WHERE %s;";
	        
	        $q = sprintf($sql, $campos, $where);

	        #$this->debugSQL($tabela, $sql,$post,$valores,'id='.$id);

	        return $this->query($q, $valores);

	    } else {
        	echo json_encode(['ERROR'=>'array vazio'],true);die;
	    }
		
	}



	/**

	*/
	function add(array $post,$incluirPK=false){
	    $tabela = $this->getTable();
	    if(count($post) > 0){
	        if($incluirPK && array_key_exists($this->getPK(), $post)){
	        	unset($post[$this->getPK()]);
	        }

	        $campos=implode(',',array_keys($post));
	        $valores=array_values($post);
	        $values = trim(str_repeat('?,',count($post)),',');

	        #$q = "INSERT INTO $tabela (nome) VALUES (?) ON CONFLICT (nome) DO UPDATE SET nome=?;";
	        $sql = "INSERT INTO $tabela (%s) VALUES (%s);";
	        
	        $q = sprintf($sql, $campos, $values);

			#$this->debugSQL($tabela, $sql, $campos, $valores,null);


	        return $this->query($q, $valores);
	    } else {
        	echo json_encode(['ERROR'=>'array vazio'],true);die;
	    }
	}

	#limit no psql e diferente
	/**

	*/
	function delOneBy($valor,$campo='id'){
	    $tabela = $this->getTable();
	    $PK=$campo."=?";
	    $sql = sprintf("DELETE FROM %s WHERE %s;",$tabela,$PK);
	    $valores=[$valor];

	    return (bool) $this->query($sql, $valores); # se repetir 2x da erro pois ele deleta oq ja foi deletado	
	}


}
