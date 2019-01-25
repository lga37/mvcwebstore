<?php 

namespace MVC\View;

class View {
	public $data=[];
	protected $template;
	protected $_header;
	protected $_footer;
	private $msg=null;

	public function __construct(){
		$this->_header='_header.php';
		$this->_footer='_footer.php';
		$this->setTitulo('Stone');
	}

	public function setTitulo($titulo){
		$this->data['titulo']=$titulo;	
	}

	public function setTemplate($template){
		$this->template = $template;
		return $this;
	}

	function __set($key,$value){
		$this->data[$key]=$value;
	}



	function msg($premsg,$msg,$tipo='danger'){
		$msg = <<<MSG
		<br>
		<div class="alert alert-$tipo alert-dismissible fade show" role="alert">
		  <strong>$premsg</strong> $msg
		  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		  </button>
		</div>
MSG;
		$this->msg =$msg;	
	}  




	private function render(){
		if(!headers_sent()){
			ob_start();
			extract($this->data);
			require '_funcoes.php';
			include $this->_header;
			if($this->msg)
				echo $this->msg;
			include $this->template;
			include $this->_footer;

			$html = ob_get_clean();
			return $html;
		}
	}

	public function show(){
		echo $this->render();
		die;
	}
}


