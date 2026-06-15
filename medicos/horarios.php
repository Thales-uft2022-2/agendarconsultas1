<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$medico_id = (int) ($_GET['id'] ?? 0);

if ($medico_id <= 0) {
    header("Location: index.php");
    exit;
}

$sql = $pdo->prepare("
    SELECT *
    FROM medicos
    WHERE id = ?
");

$sql->execute([$medico_id]);

$medico = $sql->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dia_semana = $_POST['dia_semana'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];

    $insert = $pdo->prepare("
        INSERT INTO horarios_medicos
        (
            medico_id,
            dia_semana,
            hora_inicio,
            hora_fim
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ");

    $insert->execute([
        $medico_id,
        $dia_semana,
        $hora_inicio,
        $hora_fim
    ]);

    header("Location: horarios.php?id=".$medico_id);
    exit;
}

$horarios = $pdo->prepare("
    SELECT *
    FROM horarios_medicos
    WHERE medico_id = ?
    ORDER BY id DESC
");

$horarios->execute([$medico_id]);

$lista = $horarios->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Horários do Médico</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>

Horários - <?= htmlspecialchars($medico['nome']) ?>

</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-4">

<label>Dia da Semana</label>

<select
name="dia_semana"
class="form-select"
required>

<option>Segunda</option>
<option>Terça</option>
<option>Quarta</option>
<option>Quinta</option>
<option>Sexta</option>
<option>Sábado</option>

</select>

</div>

<div class="col-md-3">

<label>Hora Início</label>

<input
type="time"
name="hora_inicio"
class="form-control"
required>

</div>

<div class="col-md-3">

<label>Hora Fim</label>

<input
type="time"
name="hora_fim"
class="form-control"
required>

</div>

<div class="col-md-2 d-flex align-items-end">

<button
type="submit"
class="btn btn-primary w-100">

Salvar

</button>

</div>

</div>

</form>

<hr>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Dia</th>
<th>Início</th>
<th>Fim</th>

</tr>

</thead>

<tbody>

<?php foreach($lista as $h): ?>

<tr>

<td><?= $h['id'] ?></td>

<td><?= $h['dia_semana'] ?></td>

<td><?= substr($h['hora_inicio'],0,5) ?></td>

<td><?= substr($h['hora_fim'],0,5) ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<a
href="index.php"
class="btn btn-secondary">

Voltar

</a>

</div>

</div>

</div>

</body>
</html>