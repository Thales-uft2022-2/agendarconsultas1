<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$medicos = $pdo->query("
    SELECT id, nome
    FROM medicos
    WHERE status = 'ativo'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$pacientes = $pdo->query("
    SELECT id, nome
    FROM pacientes
    WHERE status = 'ativo'
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $medico_id = (int) $_POST['medico_id'];
    $paciente_id = (int) $_POST['paciente_id'];
    $data_consulta = $_POST['data_consulta'];
    $hora_consulta = $_POST['hora_consulta'];
    $observacoes = trim($_POST['observacoes']);

    $verifica = $pdo->prepare("
        SELECT id
        FROM consultas
        WHERE medico_id = ?
        AND data_consulta = ?
        AND hora_consulta = ?
        AND status != 'cancelada'
    ");

    $verifica->execute([
        $medico_id,
        $data_consulta,
        $hora_consulta
    ]);

    if ($verifica->rowCount() > 0) {

        $mensagem = "Já existe uma consulta agendada para este médico neste horário.";

    } else {

        $insert = $pdo->prepare("
            INSERT INTO consultas
            (
                medico_id,
                paciente_id,
                data_consulta,
                hora_consulta,
                observacoes
            )
            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ");

        $insert->execute([
            $medico_id,
            $paciente_id,
            $data_consulta,
            $hora_consulta,
            $observacoes
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

<title>Agendar Consulta</title>

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

                    <h3>Agendar Consulta</h3>

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
                                Paciente
                            </label>

                            <select
                                name="paciente_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Selecione
                                </option>

                                <?php foreach($pacientes as $p): ?>

                                    <option value="<?= $p['id'] ?>">

                                        <?= htmlspecialchars($p['nome']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Médico
                            </label>

                            <select
                                name="medico_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Selecione
                                </option>

                                <?php foreach($medicos as $m): ?>

                                    <option value="<?= $m['id'] ?>">

                                        <?= htmlspecialchars($m['nome']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Data
                                </label>

                                <input
                                    type="date"
                                    name="data_consulta"
                                    class="form-control"
                                    min="<?= date('Y-m-d') ?>"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Hora
                                </label>

                                <input
                                    type="time"
                                    name="hora_consulta"
                                    class="form-control"
                                    required>

                            </div>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Observações
                            </label>

                            <textarea
                                name="observacoes"
                                class="form-control"
                                rows="4"></textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            Agendar

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