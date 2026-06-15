<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $endereco = trim($_POST['endereco']);

    $check = $pdo->prepare("
        SELECT id
        FROM pacientes
        WHERE cpf = ?
    ");

    $check->execute([$cpf]);

    if ($check->rowCount() > 0) {

        $mensagem = "CPF já cadastrado.";

    } else {

        $insert = $pdo->prepare("
            INSERT INTO pacientes
            (
                nome,
                cpf,
                data_nascimento,
                telefone,
                email,
                endereco,
                status
            )
            VALUES
            (
                ?,
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
            $cpf,
            $data_nascimento,
            $telefone,
            $email,
            $endereco
        ]);

        header("Location: index.php");
        exit;
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

    <title>Cadastrar Paciente</title>

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

                    <h3>Cadastrar Paciente</h3>

                </div>

                <div class="card-body">

                    <?php if($mensagem): ?>

                        <div class="alert alert-warning">

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

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    CPF
                                </label>

                                <input
                                    type="text"
                                    name="cpf"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Data de Nascimento
                                </label>

                                <input
                                    type="date"
                                    name="data_nascimento"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Telefone
                                </label>

                                <input
                                    type="text"
                                    name="telefone"
                                    class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    E-mail
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Endereço
                            </label>

                            <textarea
                                name="endereco"
                                class="form-control"
                                rows="3"></textarea>

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