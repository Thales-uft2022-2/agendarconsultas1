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
    FROM pacientes
    WHERE id = ?
");

$sql->execute([$id]);

$paciente = $sql->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    header("Location: index.php");
    exit;
}

$novoStatus =
    $paciente['status'] === 'ativo'
    ? 'inativo'
    : 'ativo';

$update = $pdo->prepare("
    UPDATE pacientes
    SET status = ?
    WHERE id = ?
");

$update->execute([
    $novoStatus,
    $id
]);

header("Location: index.php");
exit;