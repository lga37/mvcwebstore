<table class="table table-sm table-hover table-striped">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Nome</th>
      <th scope="col">Email</th>
      <th scope="col">Senha</th>
      <th scope="col">Endereco</th>
      <th scope="col">Upd</th>
      <th scope="col">Del</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    if(isset($clientes) && is_array($clientes)):
      foreach($clientes as $key=>$cliente):
          extract($cliente);
          $action_upd = URL."?c=admin&a=upd_cliente&id=$id";
          $action_del = URL."?c=admin&a=del_cliente&id=$id";
          $senha = substr($senha, 0, 10);
          $tr = <<<TR
          <tr>
            <td>$id</td>
            <td>$nome</td>
            <td>$email</td>
            <td>$senha...</td>
            <td>$endereco</td>
            <td>UPD</td>
            <td><a href="$action_del"><i class='fa red-text fa-trash'></i></a></td>
          </tr>
TR;
          echo $tr;
      endforeach;
    else:  
      echo "<h1>Nao existem registros</h1>";
    endif;
    ?>

  </tbody>
</table>

<br>
<?php 
$pag = extraiParametroURL('pag')? extraiParametroURL('pag') : 1;
echo paginate($total,$pag);