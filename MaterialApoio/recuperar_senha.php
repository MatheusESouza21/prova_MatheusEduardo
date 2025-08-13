<?php
session_start();
require_once 'conexao.php';
require_once 'funcoes_email.php'; //ARQUIVO COM AS FUNÇÕES QUE GERAM A SENHA E SIMULAM O ENVIO

if ($_SERVER['REQUEST_METHOD'] == 'post') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco de dados
    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        //GERA UMA SENHA TEMPORÁRIA ALEATORIA
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        //ATUALIZA A SENHA TEMPORÁRIA NO BANCO
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();


        //SIMULA O ENVIO DO E-MAIL (grava em txt)
        simularEnvioEmail($email, $senha_temporaria);
        echo "<script>alert('Uma nova senha foi enviada para o seu e-mail.(simulação). Verifique o arquivo e-mails_simulads.txt'); window.location.href='login.php';</script>";

    } else {
        echo "<script>alert('E-mail não encontrado.'); window.location.href='recuperar_senha.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Recuperar Senha</h2>
    <form action="recuperar_senha.php" method="post">
        <label for="email">Digite seu e-mail cadastrado</label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Enviar nova senha</button>    
</body>
</html>