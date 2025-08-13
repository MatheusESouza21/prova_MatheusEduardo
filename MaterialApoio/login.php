<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        //LOGIN BEM SUCEDIDO, DEFINE VARIAVEIS DE SESSÃO
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['id_perfil'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        //VERIFICA SE A SENHA É TEMPORARIA
        if ($usuario['senha_temporaria']) {
            //REDIRECIONA PARA A TROCA DA SENHA TEMPORARIA
            header('Location: alterar_senha.php');
            exit();
        } else {
            //REDIRECIONA PARA A PÁGINA PRINCIPAL
            header('Location: principal.php');
            exit();
        }
    } else {
        //LOGIN INVÁLIDO
        echo "<script>alert('E-mail ou senha incorretos.'); window.location.href='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label>E-mail</label>
        <input type="email" id="email" name="email" required>

        <label>Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>
    <p><a href="recuperar_senha.php">Esqueci minha senha</a></p>
</body>
</html>