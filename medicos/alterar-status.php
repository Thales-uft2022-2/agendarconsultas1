<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT status
    FROM medicos
    WHERE id = ?
");

$sql->execute([$id]);

$medico = $sql->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header("Location: index.php");
    exit;
}

$novoStatus =
    $medico['status'] === 'ativo'
    ? 'inativo'
    : 'ativo';

$update = $pdo->prepare("
    UPDATE medicos
    SET status = ?
    WHERE id = ?
");

$update->execute([
    $novoStatus,
    $id
]);

header("Location: index.php");
exit;