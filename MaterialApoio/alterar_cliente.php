<?php

session_start();
require_once 'conexao.php';

// VERFICIA SE O USUARIO DE ERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

// INICIALIZA As VARIAVEIS
$cliente = null;

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO NOME OU ID
if ($_SERVER["REQUEST_METHOD"] == "POST")
    if (!empty($_POST['busca_cliente'])) {
        $busca = trim($_POST['busca_cliente']);

        // VERIFICA SE É UM NÚMERO (ID) OU UM NOME
        if (is_numeric($busca)) {
            $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
            $busca = "$busca%";
        }

        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O USUARIO FOR ENCONTRADO, EXIBE UM ALERTA
        if (!$cliente) {
            echo "<script>alert('Cliente não encontrado.');window.location.href='buscar_cliente.php';</script>";
        }
    }

// Obtendo o nome do perfil do usuario logado
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// Definição das permissões por perfil
$permissoes = [
    1 => [
        "Cadastrar" => ["cadastro_usuario.php", "cadastro_perfil.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php", "cadastro_funcionario.php"],
        "Buscar" => ["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar" => ["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir" => ["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"]
    ],

    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php", "alterar_fornecedor.php"]
    ],

    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php"]
    ],

    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"]
    ],
];

// Obtendo as opções disponíveis para o perfil do usuário logado
$opcoes_menu = $permissoes[$id_perfil];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="styles.css">

    <!-- CERTIFIQUE-SE DE QUE O JAVASCRIPT ESTA SENDO CARREGADO CORRETAMENTE -->
    <script src="scripts.js"></script>
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
    <h2>Alterar Cliente</h2>

    <!-- FORMULARIO PARA BUSCAR USUARIOS -->
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Buscar por ID ou Nome do cliente:</label>
        <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($cliente): ?>
        <form action="processa_alteracao_cliente.php" method="POST">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

            <label for="nome_cliente">Nome:</label>
            <input type="text" id="nome_cliente" name="nome_cliente"
                value="<?= htmlspecialchars($cliente['nome_cliente']) ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>"
                required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>"
                required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($cliente['email']) ?>" required>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif; ?>
    <a href="principal.php">Voltar</a>
</body>

</html>