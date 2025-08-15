<?php
session_start();
session_destroy(); // Destrói a sessão para efetuar o logout
header('Location: login.php'); // Redireciona para a página de login
exit(); // Encerra o script
?>