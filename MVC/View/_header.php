<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?= $titulo ?></title>
        <link href="assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/css/mdb.min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">

    </head>
    <body class="container-fluid">
    <header>

        <nav class="navbar navbar-expand-lg navbar-light default-color">
            <a class="navbar-brand" href="<?=URL?>"><?= $titulo ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link waves-effect waves-light" href="<?=URL?>">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>

                        <?php if(isset($_SESSION['logado_adm']) && $_SESSION['logado_adm']==="sim"): ?>    
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-light" id="dropdown_adm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Admin
                                </a>
                                <div class="dropdown-menu dropdown-default" aria-labelledby="dropdown_adm">
                                
                                <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=admin&a=categs">Categorias</a>
                                <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=admin&a=produtos">Produtos</a>
                                <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=admin&a=clientes">Clientes</a>
                                <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=admin&a=pedidos">Pedidos</a>
                                
                                <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=admin&a=logout">LogOut</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light" href="<?=URL?>?c=admin">Admin</a>
                            </li>
                        <?php endif; ?>

                    
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="#">Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="#">Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="<?=URL?>?c=carrinho">Carrinho (<?=isset($_SESSION['cart'])?count($_SESSION['cart']):0?>)</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Clientes
                        </a>
                        <div class="dropdown-menu dropdown-default" aria-labelledby="navbarDropdownMenuLink">
                        <?php if(isset($_SESSION['logado_cli']) && $_SESSION['logado_cli']==="sim"): ?>    
                            <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=cliente&a=logout">LogOut</a>
                        <?php else: ?>
                            <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=cliente">LogIn</a>
                        <?php endif; ?>
                            <a class="dropdown-item waves-effect waves-light" href="<?=URL?>?c=cliente&a=cadastro">Cadastro</a>
                        </div>
                    </li>
                
                </ul>

                <form class="form-inline" method="POST" action="<?=URL?>?c=produtos&a=produtosnome">
                    <div class="md-form my-0">
                        <input class="form-control mr-sm-2" name="q" type="text" placeholder="Search" aria-label="Search">
                    </div>
                </form>
                
            </div>
        </nav>

    </header>
    
      <div class="row">

        <?php if(isset($setfull)): ?>
            <div class="col-md-12">

        <?php else: ?>
            <div class="col-md-3">
              <div id="menu-lateral">
                  <br>
                  <?php (new \MVC\Model\Categoria())->render(); ?>
                  <br><br>
              </div><!-- menu-lateral -->
            </div><!-- col-md-3 -->

            <div class="col-md-9">

        <?php endif; ?>
