<table class="table table-sm table-hover table-striped">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Data</th>
      <th scope="col">Cliente</th>
      <th scope="col">Produtos</th>
      <th scope="col">Total</th>
      <th scope="col">Upd</th>
      <th scope="col">Del</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    if(isset($pedidos) && is_array($pedidos)):
      foreach($pedidos as $key=>$pedido):
          extract($pedido);
          $action_upd = URL."?c=admin&a=upd_pedido&id=$id";
          $action_del = URL."?c=admin&a=del_pedido&id=$id";

          $tr = <<<TR
          <tr>
            <td>$id</td>
            <td>$datahora</td>
            <td>$cli_id</td>
            <td>[]</td>
            <td>R$</td>
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