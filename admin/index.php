<?php
    require_once('config.php');
    if(isset($_SESSION['key'])) { header('location:inicio'); }
    if(file_exists('installAplicativo.php')) { header('location:instalacao'); }

    try {
        //verifica se a tabela vendedor está vazia
        include_once('conexao.php');
    
        $sql = $pdo->prepare("SELECT idautor FROM autor");
        $sql->execute();
        $ret = $sql->rowCount();
        
            if($ret == 0) {
                rename('installAplicativoDone.php','installAplicativo.php');
                header('location:instalacao');
            }
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                Corre que <strong>fudeu</strong>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Entre para gerenciar o blog</p>
                <form class="form-login">
                    <div class="form-group has-feedback">
                        <input type="text" id="usuario" class="form-control" title="Digite o seu usu&aacute;rio" placeholder="Usu&aacute;rio" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="senha" class="form-control" title="Digite a sua senha" placeholder="Senha" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <a data-toggle="modal" data-target="#recover-pass" href="#" title="Eu esqueci a minha senha"><i class="fa fa-frown-o"></i> Eu esqueci a minha senha</a><br>
                            <a href="../" title="Ir para o site"><i class="fa fa-link"></i> Ir para o site</a><br>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat btn-submit-login">Entrar</button>
                        </div><!-- /.col -->
                    </div>
                </form>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- Modal -->
        <div class="modal fade" id="recover-pass" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-recupera-senha">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Recuperar a senha <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email"><i class="fa fa-asterisk"></i> Email</label>
                                <input type="email" id="email" class="form-control" maxlength="100" title="Digite o seu email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-flat btn-submit-recover">Recuperar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script defer src="js/bootstrap.min.js"></script>
        <script async src="js/smoke.min.js"></script>
        <script async src="js/apart.min.js"></script>
    </body>
</html>
