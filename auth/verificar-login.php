<?php

session_start();

require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    header("Location: login.php?erro=campos");
    exit;
}

try {

    $sql = $pdo->prepare("
        SELECT
            id,
            nome,
            email,
            senha,
            tipo,
            status
        FROM usuarios
        WHERE email = ?
        LIMIT 1
    ");

    $sql->execute([$email]);

    $usuario = $sql->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {

        header("Location: login.php?erro=usuario");
        exit;

    }

    if ($usuario['status'] !== 'ativo') {

        header("Location: login.php?erro=inativo");
        exit;

    }

    if (!password_verify($senha, $usuario['senha'])) {

        header("Location: login.php?erro=senha");
        exit;

    }

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['nome']       = $usuario['nome'];
    $_SESSION['email']      = $usuario['email'];
    $_SESSION['tipo']       = $usuario['tipo'];

    switch ($usuario['tipo']) {

        case 'admin':
            header("Location: ../dashboard/");
            break;

        case 'medico':
            header("Location: ../dashboard/");
            break;

        case 'paciente':
            header("Location: ../dashboard/");
            break;

        default:
            session_destroy();
            header("Location: login.php");
            break;
    }

    exit;

} catch (PDOException $e) {

    die("Erro no sistema: " . $e->getMessage());

}