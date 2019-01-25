<?php 

namespace MVC\Model;

class Pedido extends Model {
	private $pedidos;

	public function __construct(){
		$this->setTable('pedidos');
		$this->pedidos = $this->selectAllBySQL();
	}

	private function selectAllBySQL(){
		#montar aqui uma SQL completa com JOINs

		return [];
	}
}