<?php 

function retorna_nome($nome,$array, $pk_id){
    foreach($array as $row){
      if ($row['id']==$pk_id) 
        return $row['nome'];
    }

    return null;
}


function msg($msg,$h4,$tipo='danger'){
  $div = <<<DIV
    <div class="container alert alert-$tipo alert-dismissible show" role="alert">
        <button class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
        <h4> $h4 </h4>
        <p> $msg </p>
    </div>
DIV;
  echo $div;
} 

function monta_combo($nome,$array, $pk_id){
    $sel = "<select class=\"mdb-select\" id=\"$nome\" name=\"$nome\">";
    foreach($array as $row){
      $selected = $row['id']==$pk_id? " selected " : "";
      $sel .= sprintf("<option $selected value='%d'>%s</option>",$row['id'],$row['nome']);
    }
    $sel .= "</select>";
    #echo $sel;
    return $sel;
}

#########################################################
function vitrine22222222222222($planos,$cols=3){

  $html = "<div class=\"row\">";
  foreach ($planos as $k => $plano) {
    $k++;
    extract($plano);
    $html .= "<div style=\"border: 2px solid red; border-radius:5px; width:400px;height:200px; margin:10px; padding:10px;\">
                  <h4 class=\"card-title\"># $id</h4>
                  <p class=\"card-text\">$nome</p>
                  
                  <a href='c=listagem&id=' class=\"btn btn-light-blue btn-md\">Some Action</a>
              </div>";

    $html .= ($k % $cols == 0)? "</div>\n<div class=\"row\">\n" : "";

  }
  $html .= "\n</div>";
  return $html;
}


function montaListagemTabela($id,$nome,$img,$preco,$prazo,$estoque){
    #$disponib = retornaDisponibProduto($estoque,$prazo);
    $tr = <<<TR
    <tr>
        <td>$id</td>
        <td><a href="detalhes.php?id=$id" title="detalhes">$nome</a></td>
        <td>$preco</td>
        <td>$disponib</td>
        <td><a href="carrinho.php?a=add&id=$id" role="button" title="add to cart"><i class="fa fa-shopping-cart fa-2x"></i></a></td>
    </tr>
TR;
    echo $tr;
}


#function montaItemVitrine($id,$nome,$img,$preco,$prazo,$estoque){

function vitrine(array $produtos,int $cols=4){

  $html = "<div class=\"row\">";
  foreach ($produtos as $k => $produto) {
    $k++;
    extract($produto);
    $url = URL;
    #$disponib = retornaDisponibProduto($estoque,$prazo);
    ##### tem que mexer tb no cols-md-X de acordo com o num de colunas
    $prazo=mt_rand(1,20);
    $img = $foto ?? 'produto-sem-imagem.gif'; 
    #echo $img;
    $card = <<<CARD
    <div class="col-md-3">
      <div class="card">
        <p class="card-title">$nome</p>
        <img src="assets/img/produtos/$img" width="200" height="200">    
        <div class="card-img-overlay">
          <h5 class="pull-right card-subtitle">R$ $preco.00</h5>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <a href="#" class="card-link"><i class="fa fa-heart"></i></a>
            <a href="$url?c=carrinho&a=add&id=$id" class="card-link"><i class="fa fa-shopping-cart"></i></a>
            <a href="$url?c=produtos&a=detalhe&id=$id" class="card-link"><i class="fa fa-external-link"></i></a>
            <a href="#" class="card-link"><i class="fa fa-share-alt"></i></a>
          </li>
        </ul>
        <div class="card-footer">$prazo dias</div>
      </div>
    </div>
CARD;

    $html .= $card;

    $html .= ($k % $cols == 0)? "</div>\n<br><div class=\"row\">\n" : "";

  }
  $html .= "\n</div>";


  echo $html;
}



function ordenate(array $cols,$upd='UPD',$del='DEL'){
    $sens = extraiParametroURL('sens');
    $order = extraiParametroURL('order');

    $sens_oposto = $sens=='asc'?'desc':'asc';
    $tr = "<tr>";
    foreach ($cols as $k => $col) {
      if($col==$order){
        $link = replaceLink(['order'=>$col,'sens'=>$sens_oposto]);
        $tr .= "<th>$col<a href='$link'><i class='fa green-text fa-sort-amount-$sens'></i></a></th>";
      } else {
        $link = replaceLink(['order'=>$col,'sens'=>'asc','pag'=>1]);
        $tr .= "<th>$col<a href='$link'><i class='fa green-text fa-sort-amount-asc'></i></a></th>";
      }      
    }

    if($upd) $tr .= "<th>$upd</th>";
    if($del) $tr .= "<th>$del</th>";
    $tr .= "</tr>";
    return $tr;
}

function extraiParametroURL($param){
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);
    return $params[$param]?? false;
}

################ acho q essa aqui nao to usando
function inicializaParametrosURL3333333(){
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);

    $default = [
        'pag'=>1,
        'modo'=>'t',   
        'order'=>'id',
        'sens'=>'asc',
    ];

    return array_merge($default,array_intersect_key($params, $default));
}

    function parsePaginacaoOrdenacao4444444(){ ##### essa funcao tb esta
        
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $url);    

        $order = $url['order'] ?? 'id';
        $sens = $url['sens'] ?? 'asc';
        $where = $order .' '.$sens;
        $pag = $url['pag'] ?? 1;
        $modo = $url['modo'] ?? 't';

        return compact('order','sens','where','pag','modo');
    }


function replaceLink(array $params){
    parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $url);    
    $uniao = array_merge($url,$params);
    $res = URL.'?'.http_build_query($uniao);
    return $res;
}

/**
Funcao responsavel por fazer a paginacao, inclusive com paginas adjacentes para dar uma experiencia mais amigavel de navegacao
*/

function paginate(int $total,int $pag=1,int $per_page=5){
  
    $totalPaginas = ceil($total / $per_page);

    $result = range(1, $totalPaginas);
    $adjacents = 4;

    if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1){
        $result = array_slice($result, max(0, min(count($result) - $adjacents, intval($pag) - ceil($adjacents / 2))), $adjacents);
    }

    $link_primeira = replaceLink(['pag'=>1]);
    $link_ultima = replaceLink(['pag'=>$totalPaginas]);
    
    $link_anterior = replaceLink(['pag'=>$pag-1]);
    $link_proxima = replaceLink(['pag'=>$pag+1]);

    if($pag>3){
        $paginas[] = sprintf('<li class="page-item"><a class="page-link" href="%s">1</a></li>',$link_primeira);
        $paginas[] = sprintf('<li class="page-item">...</li>');
    }
    if($pag>1){
        $paginas[] = sprintf('<li class="page-item"><a class="page-link" href="%s"><i class="fa red-text fa-arrow-left"></i></a></li>',$link_anterior);
    }

    foreach ($result as $k => $i) {
        $link = replaceLink(['pag'=>$i]);
        if($i==$pag){
            $active="active";
            $paginas[] = sprintf('<li class="page-item %s"><a class="page-link" href="%s">%d</a></li>',$active,$link,$i);
        }else{
            $active="";
            $paginas[] = sprintf('<li class="page-item %s"><a class="page-link" href="%s">%d</a></li>',$active,$link,$i);
        }
    }

    if($pag!=$totalPaginas && $totalPaginas >4){
        $paginas[] = sprintf('<li class="page-item"><a class="page-link" href="%s"><i class="fa red-text fa-arrow-right"></i></a></li>',$link_proxima);
    }

    if($pag<$totalPaginas && $totalPaginas >4){
        $paginas[] = sprintf('<li class="page-item">...</li>');
        $paginas[] = sprintf('<li class="page-item"><a class="page-link" href="%s">%d</i></a></li>',$link_ultima,$totalPaginas);
    }

    return sprintf("<nav><ul class=\"pagination pg-teal\">\n%s\n</ul></nav>",implode("\n",$paginas));
}



function enviaEmail($emailDeOrigem,$nomeDeOrigem,$senha,$emailDeDestino,$nomeDeDestino,$assunto,$msg){
    $mail = new PHPMailer();
    // Define os dados do servidor e tipo de conexão
    //$mail->SMTPDebug  = 2;
    //$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
    //$mail->Username = 'seumail@dominio.net'; // Usuário do servidor SMTP
    //$mail->Password = 'senha'; // Senha do servidor SMTP

    // Config Gmail
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
    $mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
    $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
    $mail->Username   = $emailDeOrigem;         // GMAIL username
    $mail->Password   = $senha;                 // GMAIL password

    // Define o remetente
    $mail->SetFrom($emailDeOrigem, $nomeDeOrigem);
    $mail->AddReplyTo($emailDeOrigem, $nomeDeOrigem);

    // Define os destinatário(s)
    $mail->AddAddress($emailDeDestino, $nomeDeOrigem);
    //$mail->AddAddress('ciclano@site.net');
    //$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
    //$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta

    // Define os dados técnicos da Mensagem
    $mail->ContentType = 'text/plain';
    #$mail->IsHTML(true);
    $mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

    // Define a mensagem (Texto e Assunto)
    $mail->Subject  = $assunto; // Assunto da mensagem
    $mail->Body = $msg;
    $mail->AltBody = $msg; #texto PURO

    // Define os anexos (opcional)
    //$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
    $emailEnviado = $mail->Send();
    // Limpa os destinatários e os anexos
    $mail->ClearAllRecipients();
    #$mail->ClearAttachments();
  if (!$emailEnviado) {
      $m= "Informações do erro: <pre>" . print_r($mail->ErrorInfo) ."</pre>";
        msg("Não foi possível enviar o e-mail",$m,"danger");
        return false;
  }
    return true; #booleano
}
