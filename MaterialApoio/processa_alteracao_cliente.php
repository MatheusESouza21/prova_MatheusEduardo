<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $nome_cliente = $_POST['nome_cliente'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "UPDATE cliente SET nome_cliente = :nome_cliente, endereco = :endereco, telefone = :telefone, email = :email WHERE id_cliente = :id_cliente";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_cliente', $nome_cliente);
    $stmt->bindParam('endereco', $endereco);
    $stmt->bindParam('telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_cliente', $id_cliente);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente atualizado com sucesso!');window.location.href='buscar_cliente.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o cliente!');window.location.href='alterar_cliente.php?id=$cliente';</script>";
    }
}


?>