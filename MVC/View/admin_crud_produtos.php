<h2>Adicionando Produtos</h2>
<?php #var_dump(URL) ?>
<form method="post" enctype="multipart/form-data" action="<?=URL.'?c=admin&a=save_produto'?>">

    <div class="md-form form-group">
        <i class="fa fa-tasks prefix"></i>
        <input type="text" name="nome" id="nome" class="form-control validate">
        <label for="nome" data-error="wrong" data-success="right">Nome</label>
    </div>


    <div class="md-form form-group">
        <select class="custom-select browser-default" name="categ_id" id="categ_id" required>
            <option value="0" selected disabled>Selecione</option> 
            <?php 
            foreach ($categorias as $k => $categoria) {
                extract($categoria);#id e nome
                echo sprintf("<option value=\"%d\">%s</option>",$id,$nome);
            }
            ?>
        </select>
    </div>

    <div class="form-row">
      <div class="col">
        <div class="md-form">
          <input type="text" class="form-control" name="preco" placeholder="Preco">
        </div>
      </div>
      <div class="col">
        <div class="md-form">
          <input type="text" class="form-control" name="prazo" placeholder="Prazo">
        </div>
      </div>
      <div class="col">
        <div class="md-form">
          <input type="text" class="form-control" name="peso" placeholder="Peso">
        </div>
      </div>
      <div class="col">
        <div class="md-form">
          <input type="text" class="form-control" name="estoque" placeholder="Estoque">
        </div>
      </div>
    </div>


    <div class="md-form form-group file-field">
        <div class="btn btn-primary btn-sm">
            <span>Foto</span>
            <input type="file" name="foto">
        </div>
        <div class="file-path-wrapper">
           <input class="file-path validate" name="foto2" type="text" placeholder="Imagem">
        </div>
    </div>

    <div class="md-form form-group">
        <button class="btn btn-block btn-success btn-lg">OK</button>
    </div>


</form>








<div class="row">

  <div class="col-md-4">
    <div class="md-form">
      <a href="<?=replaceLink(['modo'=>'v']) ?>"<i class="fa blue-text fa-th fa-2x"></i></a>
      <a href="<?=replaceLink(['modo'=>'t']) ?>"<i class="fa blue-text fa-table fa-2x ml-4"></i></a>  
    
    </div>
  </div>

</div>

<?php 
  if(extraiParametroURL('modo')==='v'): 
    echo vitrine($produtos);
  else: 
    ?>
    <table class="table table-sm table-hover table-striped">
      <thead>
        <?php echo ordenate(['id','nome','categ_id','preco','prazo','peso','estoque','foto'],'UPD','DEL'); ?>
      </thead>
      <tbody>
        <?php 

        if(isset($produtos) && is_array($produtos)):
          foreach($produtos as $key=>$produto):
              extract($produto);#id nome preco prazo etc - vai dar confusao com categs
              $action_upd = URL."?c=admin&a=save_produto";
              if($foto=="") $foto="produto-sem-imagem.gif";

              $bt_del = sprintf("<a href='%s'><i class='fa red-text fa-trash'></i></a>",URL."?c=admin&a=del_produto&id=$id");

              $options = "";

              foreach ($categorias as $k => $categoria) {
                  $nome_categ = $categoria['nome'];
                  $id_categ = $categoria['id'];
                  $selected = ($id_categ==$categ_id)? "selected" : "";
                  $options .="<option $selected value=$id_categ>$nome_categ</option>";
              }

              $tr = <<<TR

                <form class="form-inline" method="post" enctype="multipart/form-data" name="form_$id" action="$action_upd">
                  <tr>
                    <td>
                      <input type="hidden" name="id" value="$id">
                      <input type="checkbox" class="form-check-input" id="c_$key"><label class="form-check-label" for="c_$key">$id</label></td>

                    <td>
                      <div class="md-form my-0">
                        <input class="form-control mr-sm-2" name="nome" value="$nome">
                      </div>
                    </td>

                    <td>
                      <div class="form-group">
                          <select class="custom-select browser-default" name="categ_id" id="categ_id" required>
                            $options;
                          </select>
                      </div>   
                    </td>

                    <td>
                      <div class="md-form my-0">
                        <input class="form-control mr-sm-2" style="width:50px;" name="preco" value="$preco">
                      </div>
                    </td>
                    <td>
                      <div class="md-form my-0">
                        <input class="form-control mr-sm-2" style="width:50px;" name="prazo" value="$prazo">
                      </div>
                    </td>
                    <td>
                      <div class="md-form my-0">
                        <input class="form-control mr-sm-2" style="width:50px;" name="peso" value="$peso">
                      </div>
                    </td>
                    <td>
                      <div class="md-form my-0">
                        <input class="form-control mr-sm-2" style="width:50px;" name="estoque" value="$estoque">
                      </div>
                    </td>
                    <td>
                      <div class="md-form form-group file-field">
                          <div class="btn btn-sm">
                              <img src="assets/img/produtos/$foto" width="50" height="50">
                              <input type="file" name="foto">
                          </div>
                          <div class="file-path-wrapper">
                             <input class="file-path validate" name="foto2" type="text" placeholder="Imagem">
                          </div>
                      </div>
                    </td>

                    <td><button><i class='fa blue-text fa-refresh'></button></td>
                    <td>$bt_del</td>
                  </tr>
                </form>                

TR;
                echo $tr;

          endforeach;
        else:  
          echo "<h1>Nao existem registros</h1>";
        endif;

        ?>
      </tbody>
    </table>

<?php 
  endif;
?>


<br>

<?php 
$pag = extraiParametroURL('pag')? extraiParametroURL('pag') : 1;

echo paginate($total,$pag);


