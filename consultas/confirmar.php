<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {

    $update = $pdo->prepare("
        UPDATE consultas
        SET status = 'confirmada'
        WHERE id = ?
    ");

    $update->execute([$id]);
}

header("Location: index.php");
exit;