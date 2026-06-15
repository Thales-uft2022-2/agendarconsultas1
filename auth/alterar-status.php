<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo'] !== 'admin') {
    header("Location: ../dashboard/");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: registrar.php");
    exit;
}

$usuario = $pdo->prepare("
    SELECT *
    FROM usuarios
    WHERE id = ?
");

$usuario->execute([$id]);

$dados = $usuario->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    header("Location: registrar.php");
    exit;
}

/*
    Protege administradores
*/
if ($dados['tipo'] === 'admin') {
    header("Location: registrar.php");
    exit;
}

$novoStatus =
    $dados['status'] === 'ativo'
    ? 'inativo'
    : 'ativo';

$update = $pdo->prepare("
    UPDATE usuarios
    SET status = ?
    WHERE id = ?
");

$update->execute([
    $novoStatus,
    $id
]);

header("Location: registrar.php");
exit;