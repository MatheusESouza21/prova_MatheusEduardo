<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário tem permissão para acessar a página
// Supondo que o perfil 1 seja o administrador
if ($_SESSION['perfil'] = 2) {
    //echo "<script>alert('Acesso negado. Você não tem permissão para acessar esta página.');</script>";
    echo "Acesso Negado";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_cliente = $_POST['nome_cliente'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    $sql = "INSERT INTO cliente (nome_cliente, email, endereco, telefone) VALUES (:nome_cliente, :email, :endereco, :telefone)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_cliente', $nome_cliente);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':telefone', $telefone);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar cliente. Tente novamente.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Cadastro Cliente</title>
</head>

<body>
    <nav>
        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= $categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <h2>Cadastrar Cliente</h2>
    <form action="cadastro_cliente.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome_cliente" name="nome_cliente" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="endereco">Senha:</label>
        <input type="text" id="endereco" name="endereco" required><br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required><br><br>
      

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <p><a href="principal.php">Voltar</a></p>


</body>

</html>