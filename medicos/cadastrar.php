<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$mensagem = '';
$tipoMensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $crm = trim($_POST['crm'] ?? '');
    $especialidade = trim($_POST['especialidade'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (
        empty($nome) ||
        empty($crm) ||
        empty($especialidade)
    ) {

        $mensagem = "Preencha os campos obrigatórios.";
        $tipoMensagem = "danger";

    } else {

        $check = $pdo->prepare("
            SELECT id
            FROM medicos
            WHERE crm = ?
        ");

        $check->execute([$crm]);

        if ($check->rowCount() > 0) {

            $mensagem = "CRM já cadastrado.";
            $tipoMensagem = "warning";

        } else {

            $insert = $pdo->prepare("
                INSERT INTO medicos
                (
                    nome,
                    crm,
                    especialidade,
                    telefone,
                    email,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'ativo'
                )
            ");

            $insert->execute([
                $nome,
                $crm,
                $especialidade,
                $telefone,
                $email
            ]);

            header("Location: index.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<title>Cadastrar Médico</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h3 class="m-0">

                        Cadastrar Médico

                    </h3>

                </div>

                <div class="card-body">

                    <?php if($mensagem): ?>

                        <div class="alert alert-<?= $tipoMensagem ?>">

                            <?= $mensagem ?>

                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Nome

                            </label>

                            <input
                                type="text"
                                name="nome"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                CRM

                            </label>

                            <input
                                type="text"
                                name="crm"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Especialidade

                            </label>

                            <input
                                type="text"
                                name="especialidade"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Telefone

                            </label>

                            <input
                                type="text"
                                name="telefone"
                                class="form-control">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                E-mail

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control">

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Salvar

                        </button>

                        <a
                            href="index.php"
                            class="btn btn-secondary">

                            Voltar

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>