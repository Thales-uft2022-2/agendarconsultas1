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
    SELECT *
    FROM medicos
    WHERE id = ?
");

$sql->execute([$id]);

$medico = $sql->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header("Location: index.php");
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome']);
    $crm = trim($_POST['crm']);
    $especialidade = trim($_POST['especialidade']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);

    $update = $pdo->prepare("
        UPDATE medicos
        SET
            nome = ?,
            crm = ?,
            especialidade = ?,
            telefone = ?,
            email = ?
        WHERE id = ?
    ");

    $update->execute([
        $nome,
        $crm,
        $especialidade,
        $telefone,
        $email,
        $id
    ]);

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

<title>Editar Médico</title>

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

                    <h3 class="m-0">

                        Editar Médico

                    </h3>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Nome

                            </label>

                            <input
                                type="text"
                                name="nome"
                                class="form-control"
                                value="<?= htmlspecialchars($medico['nome']) ?>"
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
                                value="<?= htmlspecialchars($medico['crm']) ?>"
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
                                value="<?= htmlspecialchars($medico['especialidade']) ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Telefone

                            </label>

                            <input
                                type="text"
                                name="telefone"
                                class="form-control"
                                value="<?= htmlspecialchars($medico['telefone']) ?>">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                E-mail

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($medico['email']) ?>">

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