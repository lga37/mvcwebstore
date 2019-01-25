<br>
<?php 

$button="ATUALIZAR";
if(is_array($cliente)){
  extract($cliente);
}
if(is_array($cart)){
  $button="FINALIZAR";
  $nomes = array_column($cart, 'nome');
  msg("Carrinho em Andamento",implode("<br>",$nomes),"info");

}


?>
<form method="POST" name="cadastro" action="<?= URL.'?c=cliente&a=finaliza' ?>"> 
    <fieldset>
        <legend>Cadastro</legend>
        <input type="hidden" name="id" value="<?=$id?>">
        <div class="">
          <label for="nome">Nome</label>
          <div class="input-group">
            <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome" value="<?=$nome?>" required>
          </div>
        </div>

        <div class="">
          <label for="email">Email</label>
          <div class="input-group">
            <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?=$email?>" required>
          </div>
        </div>

        <div class="">
          <label for="nome">Senha</label>
          <div class="input-group">
            <input type="password" class="form-control" name="senha" id="senha" placeholder="Senha">
          </div>
        </div>


        <div class="">
          <label for="endereco">Endereco</label>
          <div class="input-group">
            <input type="text" class="form-control" name="endereco" id="endereco" placeholder="Endereco" value="<?=$endereco?>" required>
          </div>
        </div>


        <br>
        <button class="btn btn-lg btn-block btn-outline-info"><?=$button?></button>

    </fieldset>    

    <br><br>
</form>
