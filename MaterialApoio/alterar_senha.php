<?php
session_start();
require_once 'conexao.php';

//GARANTE QUE O USUÁRIO ESTEJA LOGADO
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso negado'); window.location.href='login.php';</script>";
    exit();
}