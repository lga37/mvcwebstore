<?php 
    $endereco="";
    if(isset($_SESSION['cep'])){

        $cep = preg_replace("#[^\d]#", "", $_SESSION["cep"]);

        $cep = substr($cep, 0, 8);

        $url = "https://viacep.com.br/ws/".$cep."/json/";
        #echo $url;
        $res = file_get_contents($url);
        #var_dump($res);
        $end = json_decode($res,true);
        if(isset($end['logradouro'],$end['localidade'],$end['uf'])){
            $endereco = $end['logradouro'].' - '.$end['localidade'].' - '.$end['uf'];
        }
    }

?>

<form method="POST" action="<?=URL.'?c=cliente&a=login_index'?>">
    <fieldset>
        <legend>Clientes Ja Cadastrados (a@a.com 123)</legend>

        <div class="md-form">
            <i class="fa fa-at prefix"></i>
            <input type="text" id="email" name="email" class="form-control validate">
            <label for="email" data-error="wrong" data-success="ok">Email</label>
        </div>

        <div class="md-form">
            <i class="fa fa-lock prefix"></i>
            <input type="password" id="senha" name="senha" class="form-control validate">
            <label for="senha" data-error="wrong" data-success="ok">Senha</label>
        </div>

       <button class="btn btn-lg btn-block btn-outline-success">LOGIN</button>
       <br>
    </fieldset>    
</form>
<hr>
<form method="POST" action="<?=URL.'?c=cliente&a=cadastro_index'?>">
    <fieldset>
        <legend>Clientes Nao Cadastrados</legend>

        <div class="md-form">
            <i class="fa fa-user prefix"></i>
            <input type="text" id="inputNome" name="nome" class="form-control validate">
            <label for="inputNome" data-error="wrong" data-success="ok">Nome</label>
        </div>

        <div class="md-form">
            <i class="fa fa-at prefix"></i>
            <input type="text" id="inputEmail" name="email" class="form-control validate">
            <label for="inputEmail" data-error="wrong" data-success="ok">Email</label>
        </div>

        <div class="md-form">
            <i class="fa fa-lock prefix"></i>
            <input type="password" id="inputSenha" name="senha" class="form-control validate">
            <label for="inputSenha" data-error="wrong" data-success="ok">Senha</label>
        </div>

        <div class="md-form">
            <i class="fa fa-map-marker prefix"></i>
            <input type="text" id="inputEnd" value="<?=$endereco?>" name="endereco" class="form-control validate">
            <label for="inputEnd" data-error="wrong" data-success="ok">Endereco</label>
        </div>


       <button class="btn btn-lg btn-block btn-outline-info">CADASTRAR</button>
       <br>
    </fieldset>    
</form>