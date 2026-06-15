<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];

$buscar = $pdo->prepare("
    SELECT *
    FROM pacientes
    WHERE id = ?
");

$buscar->execute([$id]);

$paciente = $buscar->fetch(PDO::FETCH_ASSOC);

if (!$paciente) {
    header("Location: index.php");
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
        AND id != ?
    ");

    $check->execute([$cpf, $id]);

    if ($check->rowCount() > 0) {

        $mensagem = "Já existe outro paciente com este CPF.";

    } else {

        $update = $pdo->prepare("
            UPDATE pacientes
            SET
                nome = ?,
                cpf = ?,
                data_nascimento = ?,
                telefone = ?,
                email = ?,
                endereco = ?
            WHERE id = ?
        ");

        $update->execute([
            $nome,
            $cpf,
            $data_nascimento,
            $telefone,
            $email,
            $endereco,
            $id
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

<title>Editar Paciente</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <div class="card shadow">

                <div class="card-header bg-warning">

                    <h3>Editar Paciente</h3>

                </div>

                <div class="card-body">

                    <?php if ($mensagem): ?>

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
                                value="<?= htmlspecialchars($paciente['nome']) ?>"
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
                                    value="<?= htmlspecialchars($paciente['cpf']) ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Data de Nascimento

                                </label>

                                <input
                                    type="date"
                                    name="data_nascimento"
                                    class="form-control"
                                    value="<?= $paciente['data_nascimento'] ?>">

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
                                    class="form-control"
                                    value="<?= htmlspecialchars($paciente['telefone']) ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    E-mail

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($paciente['email']) ?>">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Endereço

                            </label>

                            <textarea
                                name="endereco"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($paciente['endereco']) ?></textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-warning">

                            Salvar Alterações

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