<?php 

namespace MVC\Model;

class Categoria extends Model {
	private $categs;

	public function __construct(){
		$this->setTable('categorias');
		$this->categs = $this->getAll();
	}


	public function render(){
		#tirei o <li> pq nao quis alterar CSS
		$lis="";
		foreach ($this->categs as $categ) {
			$lis .= "<a href='".URL."?c=produtos&a=produtoscateg&id={$categ['id']}' class='list-group-item list-group-item-action waves-effect'>{$categ['nome']}</a>";
		}

		echo sprintf("<ul class='list-group'>%s</ul>",$lis);
	}
}