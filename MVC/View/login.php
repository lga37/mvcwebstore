<?php


if(isset($_SESSION['cliente']) && is_array($_SESSION['cliente']) ){
    redirect('cadastro.php');
}

function getClienteByEmail($email){
    global $cn;
    #falha de sql injection
    $sql = "SELECT * FROM clientes WHERE email='".$email."' LIMIT 1;";
    $res = $cn->query($sql);
    return $res->fetch_array();
}

$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);           
    
    switch ($_REQUEST['a']) {
        case 'novo':
            $cep = preg_replace('/[^0-9]/', '', $_POST['cep']);
            $login = $_POST['login'];
            if(!preg_match('/^(\d{8})$/',$cep)){
                $errors['cep']="deve ter 8 digitos";
            }
            if(!filter_var($login,FILTER_VALIDATE_EMAIL)){
                $errors['login']="email invalido";
            }
            if(getClienteByEmail($login)){
                $errors['cliente']="Cliente ja existe para este email";
            }
            if(empty($errors)){
                $_SESSION['email']=$email;
                $_SESSION['cep']=$cep;
                redirect('cadastro.php');

            } else {
                $texto = implode('<br>',$errors);
                msg($texto,"danger");
            }
            break;
        
        case 'esqueceu':
            $login = $_POST['login'];
            $cliente = getClienteByEmail($login);
            if($cliente){
                $senhaNova = generateRandom(16);
                $hash = password_hash($senhaNova,PASSWORD_BCRYPT);
                $sql = "UPDATE clientes SET senha=? WHERE email=?;";

                $stmt = $cn->prepare($sql);
                if(!$stmt) {
                    trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $cn->errno . ' ' . $stmt->error, E_USER_ERROR);
                }

                $stmt->bind_param('ss', $email, $hash);
                $stmt->execute();
                $msg = "Nova senha : ". $senhaNova ." <br> ". date();
                enviaEmail($msg, $email);
                msg("nova senha enviada para seu email","success");
            } else {
                msg("email nao achado","danger");
            }    
            break;
        
        case 'login':
        default:
            $senha = $_POST['senha'];
            $login = $_POST['login'];
            if(!preg_match('/^(.{3,})$/',$senha)){
                $errors['senha']="senha deve ter min 3 digitos";
            }
            if(!filter_var($login,FILTER_VALIDATE_EMAIL)){
                $errors['login']="email invalido";
            }
            $cliente = getClienteByEmail($login);
            if(!$cliente){
                $errors['cliente']="Cliente nao existe";
            } else {
                if($cliente['senha']!=$senha){
                    $errors['senha_correta']="senha nao bate";
                }
            }

            if(empty($errors)){
                $_SESSION['cliente']=$cliente;
                redirect('cadastro.php');
            } else {
                $texto = implode('<br>',$errors);
                msg($texto,"danger");
            }

            break;
    }

}
?>

        <form method="POST" name="login" action="?a=login">
            <fieldset>
                <legend>Ja sou Cliente</legend>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input class="form-control" name="login" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input type="password" name="senha" class="form-control" placeholder="Senha">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-xs-12">
                        <button class="btn btn-lg btn-success-outline">Login</button>
                        
                    </div>
                </div>
            </fieldset>    
        </form>

        <form method="POST" name="novo" action="?a=novo">
            <fieldset>
                <legend>Não sou Cliente</legend>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input class="form-control" name="login" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                        <input name="cep" class="form-control" placeholder="CEP">
                    </div>
                </div>



               <button class="btn btn-lg btn-success-outline">Cadastrar</button>
            </fieldset>    
        </form>
