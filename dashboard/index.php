<?php

require_once "../config/session.php";
require_once "../config/database.php";

$nome = $_SESSION['nome'];
$tipo = $_SESSION['tipo'];

$totalMedicos = $pdo->query("
    SELECT COUNT(*)
    FROM medicos
")->fetchColumn();

$totalPacientes = $pdo->query("
    SELECT COUNT(*)
    FROM pacientes
")->fetchColumn();

$totalConsultas = $pdo->query("
    SELECT COUNT(*)
    FROM consultas
")->fetchColumn();

/*
    Se ainda não criou as tabelas,
    deixe em 0 temporariamente
*/
$totalHoje = 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

</head>

<body>

<div class="sidebar">

    <h3>Consultas</h3>

    <a href="#">Dashboard</a>

    <?php if($tipo === 'admin'): ?>

        <a href="../auth/registrar.php">
            Usuários
        </a>

    <?php endif; ?>

    <a href="../medicos/">
        Médicos
    </a>

    <a href="../pacientes/">
        Pacientes
    </a>

    <a href="../consultas/">
        Consultas
    </a>

    <a href="../auth/logout.php">
        Sair
    </a>

</div>

<div class="content">

    <h2>
        Bem-vindo, <?= htmlspecialchars($nome) ?>
    </h2>

    <p>
        Perfil: <?= strtoupper($tipo) ?>
    </p>

    <div class="row mt-4">

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Médicos</h5>

                    <h2><?= $totalMedicos ?></h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Pacientes</h5>

                    <h2><?= $totalPacientes ?></h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Consultas</h5>

                    <h2><?= $totalConsultas ?></h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card dashboard-card">

                <div class="card-body">

                    <h5>Hoje</h5>

                    <h2><?= $totalHoje ?></h2>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>