<?php
session_start();
require_once 'conexao.php';

if (isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil,);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

$permissoes = [
    1 => [
        "Cadastrar" => ["cadastro_usuario.php", "cadastro_perfil", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_funcionario.php", "cadastro_produto"],
        "Buscar" => ["buscar_usuario.php", "buscar_perfil", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_funcionario.php", "buscar_produto.php"],
        "Alterar" => ["alterar_usuario.php", "alterar_perfil", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_funcionario.php", "alterar_produto"],
        "Excluir" => ["excluir_usuario.php", "alterar_perfil", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_funcionario.php", "excluir_produto.php"]
    ],

    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php", "alterar_fornecedor"]
    ],

    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto"],
        "Excluir" => ["excluir_produto.php"]
    ],

    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"]
    ],
];

// Verifica se o perfil do usuário está definido nas permissões
$opcoes_menu = $permissoes[$id_perfil];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Principal</title>
    <link rel="stylecheet" href="styles.css">
    <script src="scripts.js"></script>
</head>
<body>
   <header>
        <div class="saudacao">
            <h2>Bem vindo,<?php echo $_SESSION["usuario"]; ?> ! Perfil: <?php echo $nome_perfil; ?> </h2>
        </div>
        <div class="logout">
            <form action="logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
   </header>
   <nav>
    <ul class="menu">
        <?php foreach ($opcoes_menu as $categoria => $arquivos): ?>
            <li class="dropdown">
                <a href="#"><?php $categoria; ?></a>
                <ul class="dropdown-content">
                    <?php foreach ($arquivos as $arquivo): ?>
                        <li><a href="<?php $arquivo; ?>"><?php ucfirst(str_replace('_', ' ', basename($arquivo,".php"))); ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <li>

        </li>
        <?php endforeach; ?>
    </ul>
   </nav>
</body>
</html>