<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['idautor'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['hora'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['url'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['datado'])) { die($msg); } else {
            $filtro = 1;

            $dia = substr($_POST['datado'],0,2);
            $mes = substr($_POST['datado'],3,2);
            $ano = substr($_POST['datado'],6);
            $_POST['datado'] = $ano."-".$mes."-".$dia;
            unset($dia,$mes,$ano);
        }
        if(empty($_POST['titulo'])) { die($msg); } else {
            $filtro++;

            $_POST['titulo'] = str_replace("'","&#39;",$_POST['titulo']);
            $_POST['titulo'] = str_replace('"','&#34;',$_POST['titulo']);
            $_POST['titulo'] = str_replace('%','&#37;',$_POST['titulo']);
        }
        if(empty($_POST['texto'])) { die($msg); } else {
            $filtro++;

            $_POST['texto'] = str_replace("'","&#39;",$_POST['texto']);
            $_POST['texto'] = str_replace('"','&#34;',$_POST['texto']);
            $_POST['texto'] = str_replace('%','&#37;',$_POST['texto']);
        }
        switch($_POST['midia']) {
            case 'Imagem':
                if(empty($_FILES['imagem'])) { die($msg); } else {
                    $filtro++;

                        if($_FILES['imagem']['size'] > '3000000') {
                            die('Essa foto ultrapassa 3MB.');
                        }
                        elseif(($_FILES['imagem']['type'] == 'image/jpg') or ($_FILES['imagem']['type'] == 'image/jpeg') or ($_FILES['imagem']['type'] == 'image/png')) {
                            $dir = 'midiaPost/';
                            $remete  = $_FILES['imagem']['tmp_name'];
                            $destino  = $dir.$_FILES['imagem']['name'];
                            $move = move_uploaded_file($remete,$destino);

                                if(!$move) {
                                    die('Erro ao fazer o upload.');
                                }
                                else {
                                    $py = md5($_FILES['imagem']['tmp_name']);
                                    $ext = strrchr($_FILES['imagem']['name'],'.');
                                    $ren = rename($destino,$dir.$py.$ext);

                                        if(!$ren) {
                                            if (file_exists($destino)) {
                                                $del = unlink($destino);
                                                unset($del);
                                                die('A imagem '.$py.$ext.' j&aacute; existe.');
                                            }

                                            die('Erro ao renomear a imagem.');
                                        }
                                        else {
                                            $midia = $py.$ext;
                                            include_once('thumbnail.php');
                                            thumbnail($dir,$midia,1280,830);
                                        }

                                    unset($py,$ext,$ren);
                                }

                            unset($dir,$remete,$destino,$move);
                        }
                        else {
                            die('Esse tipo de arquivo n&atilde;o &eacute; suportado');
                        }
                }
            break;
            case 'Video':
                if(empty($_POST['video'])) { die($msg); } else {
                    $filtro++;

                        if(strstr($_POST['video'],'youtu.be')) {
                            $a = substr($_POST['video'], 17);
                            $midia = '<iframe class="embed-responsive-item" width="852" height="480" src="https://www.youtube.com/embed/'.$a.'" frameborder="0" allowfullscreen></iframe>';
                        }
                        elseif(strstr($_POST['video'],'youtube.com/watch')) {
                            $a = substr($_POST['video'], 32);
                            $midia = '<iframe class="embed-responsive-item" width="852" height="480" src="https://www.youtube.com/embed/'.$a.'" frameborder="0" allowfullscreen></iframe>';
                        }
                        elseif(strstr($_POST['video'],'/vimeo.com')) {
                            $a = substr($_POST['video'], 18);
                            $midia = '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$a.'" width="852" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                        }
                        elseif(strstr($_POST['video'],'<iframe')) {
                            if(strstr($_POST['video'],'youtube')) {
                                $a = explode(' ', $_POST['video']);
                                $b = substr($a[3], 35);
                                $b = substr($b, 0, -1);
                                $midia = '<iframe class="embed-responsive-item" width="852" height="480" src="https://www.youtube.com/embed/'.$b.'" frameborder="0" allowfullscreen></iframe>';
                            }
                            elseif(strstr($_POST['video'],'vimeo')) {
                                $a = explode(' ', $_POST['video']);
                                $b = substr($a[1], 36);
                                $b = substr($b, 0, -1);
                                $midia = '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.$b.'" width="852" height="480" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
                            }
                            else {
                                $midia = $_POST['video'];
                            }
                        }
                        else {
                            die('Link de v&iacute;deo inv&aacute;lido.');
                        }

                        unset($a,$b);

                        /*if(strstr($_POST['video'],'<iframe')) {
                            $midia = $_POST['video'];
                        }
                        else {
                            die('Link de v&iacute;deo inv&aacute;lido.');
                        }*/
                }
            break;
            case 'Galeria':
                if(empty($_POST['album'])) { die($msg); } else {
                    $filtro++;
                    $midia = $_POST['album'];
                }
            break;
            default:
                $filtro++;
                $midia = "";
            break;
        }

        if($filtro == 4) {
            try {
                include_once('conexao.php');

                /* TENTA INSERIR NO BANCO */

                $sql = $pdo->prepare("INSERT INTO post (autor_idautor,datado,hora,titulo,texto,midia,url) VALUES (:idautor,:datado,:hora,:titulo,:texto,:midia,:url)");
                $sql->bindParam(':idautor', $_POST['idautor'], PDO::PARAM_INT);
                $sql->bindParam(':datado', $_POST['datado'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['hora'], PDO::PARAM_STR);
                $sql->bindParam(':titulo', $_POST['titulo'], PDO::PARAM_STR);
                $sql->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR);
                $sql->bindParam(':midia', $midia, PDO::PARAM_STR);
                $sql->bindParam(':url', $_POST['url'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    }
                    else {
                        if(empty($_POST['tag'])) {
                            echo'true';
                        }
                        else {
                            $idpost = $pdo->lastInsertId();
                            $tags = explode(',', $_POST['tag']);
                            $count_tags = count($tags);
                            $count = 0;

                                foreach ($tags as $tag => $idtag) {
                                    //SE A TAG FOR INT É PORQUE FOI CADASTRADA NA TABELA TAG PREVIAMENTE, ENTÃO VINCULA.
                                    //SENÃO CADASTRA NA TABELA TAG E DEPOIS VINCULA

                                    if(is_numeric($idtag)) {
                                        $sql = $pdo->prepare("INSERT INTO post_has_tag (post_idpost,tag_idtag) VALUES (:idpost,:idtag)");
                                        $sql->bindParam(':idpost', $idpost, PDO::PARAM_INT);
                                        $sql->bindParam(':idtag', $idtag, PDO::PARAM_INT);
                                        $res = $sql->execute();

                                            if(!$res) {
                                                var_dump($sql->errorInfo());
                                                exit;
                                            }
                                            else {
                                                $count++;
                                            }
                                    }
                                    else {
                                        $sql = $pdo->prepare("INSERT INTO tag (descricao) VALUES (:descricao)");
                                        $sql->bindParam(':descricao', $idtag, PDO::PARAM_STR);
                                        $res = $sql->execute();

                                            if(!$res) {
                                                var_dump($sql->errorInfo());
                                                exit;
                                            }
                                            else {
                                                $idtag = $pdo->lastInsertId();
                                                $sql = $pdo->prepare("INSERT INTO post_has_tag (post_idpost,tag_idtag) VALUES (:idpost,:idtag)");
                                                $sql->bindParam(':idpost', $idpost, PDO::PARAM_INT);
                                                $sql->bindParam(':idtag', $idtag, PDO::PARAM_INT);
                                                $res = $sql->execute();

                                                    if(!$res) {
                                                        var_dump($sql->errorInfo());
                                                        exit;
                                                    }
                                                    else {
                                                        $count++;
                                                    }
                                            }
                                    }
                                }

                                if($count_tags == $count) {
                                    echo'true';
                                }

                            unset($idpost,$tags,$tag,$tag,$count,$count_tags,$idtag);
                        }
                    }

                unset($pdo,$sql,$res,$midia);
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro
        else {
            die('Par&acirc;metro incorreto');
        }

    unset($msg,$filtro);
?>
