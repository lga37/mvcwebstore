<?php

namespace MVC\Controller;

use \MVC\View\View as View;
use \MVC\Model\Model as Model;

class Controller {
	protected $view;
	protected $model;

	public function __construct($template){
		$this->view = new View();
		$this->view->setTemplate($template);
		$this->model = new Model();
	}

	protected function redirect($pag){
		header('Location: '.$pag);die;
	}


	protected function clear(){
		unset($_SESSION);
	    #setcookie(session_name(), '', time() - 42000);
	    #session_regenerate_id();
		#session_destroy();
		return $this;
	}

	function parsePaginacaoOrdenacao(){
	    
	    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $url);    

	    $order = $url['order'] ?? 'id';
	    $sens = $url['sens'] ?? 'asc';
	    $where = $order .' '.$sens;
	    $pag = $url['pag'] ?? 1;
	    $modo = $url['modo'] ?? 't';

	    return compact('order','sens','where','pag','modo');
	}



	#se vier vazio - name,type,tmp_name vem vazio e error=4 size=0
	function upload($name,$type,$tmp_name,$error,$size){
	    $erroUpload=[];
	    
	    switch($error){
	        case 1:
	        case 2:
	            $erroUpload['error']="arq mto gde";
	            break;
	        case 3:
	            $erroUpload['error']="arq parcialmente enviado";
	            break;
	        case 6:
	        case 7:
	        case 8:
	            $erroUpload['error']="erro de sistema";
	            break;
	        case 4:
	        #default:
	            $erroUpload['error']="no file";
	            break;
	        #0 e OK 
	    }

	    if(!$erroUpload){
	        $name = preg_replace("/[^A-Z0-9.-_]/i","_",$name);
	        $i = 0;
	        $parts = pathinfo($name);
	        #nao esta funcionando, acho que o certo e ver pelo base64_encode
	        while (file_exists(UPLOADS . $name)) {
	            $i++;
	            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
	        }
	        #1  IMAGETYPE_GIF
	        #2  IMAGETYPE_JPEG
	        #3  IMAGETYPE_PNG       
	        $fileType = exif_imagetype($tmp_name); #volta um inteiro..........
	        #echo "aquiiii";
	        $permitidas2 = [IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG];
	        #var_dump($fileType);
	        #var_dump($permitidas2);
	        if (!in_array($fileType, $permitidas2)) {
	            $erroUpload['type']="nao e um tipo/ext permitida";
	            return $erroUpload; #melhor dar um break 
	        }
	        #extensoes 2
	        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
	        $filetype = finfo_file($fileinfo, $tmp_name);
	        finfo_close($fileinfo);
	        $mimetype = explode('/',$filetype);
	        $mimetype = '.'. $mimetype[1];
	        $mimetype = strtolower($mimetype);

	        $permitidas2 = ['.jpg','.jpeg','.gif','.png'];
	        #var_dump($mimetype);
	        #var_dump($filetype);
	        #var_dump($permitidas2);
	        if(!in_array($mimetype, $permitidas2)){
	            $erroUpload['type2']="extensao nao perm";
	        }
	        if(round($size/1024) > 5512){
	            $erroUpload['size']="Tamanho max nao perm";
	        }
	        #$mime = "application/img; charset=binary";
	        #exec("file -bi " . $tmp_name, $out);
	        #if ($out[0] != $mime) {
	        #   $erroUpload['mimetype']="erro no mimetype";
	        #}

	        $end = explode('.', $name);
	        $end2 = end($end); 
	        $extensao = ".".$end2;
	        $extensao = strtolower($extensao);

	        if(!in_array($extensao, $permitidas2)){
	            $erroUpload['type3']="extensao nao perm ... essa e a 1 checagem";
	        }
	        $novoNome = uniqid().$extensao;
	        #echo UPLOADS,'<br>';
	        $success = move_uploaded_file($tmp_name,UPLOADS . $novoNome);
	        if (!$success) { 
	            $erroUpload['upload']="erro , nao fez a move_uploaded_file";
	        }else{
	            @chmod(UPLOADS .'/'. $novoNome, 0644);
	            @unlink($tmp_name);
	        }
	    }

	    if(empty($erroUpload)){
	        return UPLOADS . $novoNome;
	    } else {
	        return $erroUpload;
	    }

	}



}
