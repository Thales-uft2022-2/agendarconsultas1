<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['tipo'] !== 'admin') {
    header("Location: ../dashboard/");
    exit;
}

$mensagem = '';
$tipoMensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    if (
        empty($nome) ||
        empty($email) ||
        empty($senha) ||
        empty($tipo)
    ) {

        $mensagem = "Preencha todos os campos.";
        $tipoMensagem = "danger";

    } else {

        $check = $pdo->prepare("
            SELECT id
            FROM usuarios
            WHERE email = ?
        ");

        $check->execute([$email]);

        if ($check->rowCount() > 0) {

            $mensagem = "E-mail já cadastrado.";
            $tipoMensagem = "warning";

        } else {

            $hash = password_hash(
                $senha,
                PASSWORD_DEFAULT
            );

            $insert = $pdo->prepare("
                INSERT INTO usuarios
                (
                    nome,
                    email,
                    senha,
                    tipo,
                    status
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    'ativo'
                )
            ");

            $insert->execute([
                $nome,
                $email,
                $hash,
                $tipo
            ]);

            $mensagem = "Usuário cadastrado com sucesso.";
            $tipoMensagem = "success";
        }
    }
}

$usuarios = $pdo->query("
    SELECT *
    FROM usuarios
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Gerenciar Usuários</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

<h2 class="m-0">

Gerenciamento de Usuários

</h2>

<a
href="../dashboard/"
class="btn btn-light">

Dashboard

</a>

</div>

<div class="card-body">

<?php if($mensagem): ?>

<div class="alert alert-<?= $tipoMensagem ?>">

<?= $mensagem ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="row">

<div class="col-md-4 mb-3">

<label class="form-label">

Nome

</label>

<input
type="text"
name="nome"
class="form-control"
required>

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

E-mail

</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label class="form-label">

Senha

</label>

<input
type="password"
name="senha"
class="form-control"
required>

</div>

<div class="col-md-2 mb-3">

<label class="form-label">

Perfil

</label>

<select
name="tipo"
class="form-select"
required>

<option value="">

Selecione

</option>

<option value="admin">

Admin

</option>

<option value="medico">

Médico

</option>

<option value="paciente">

Paciente

</option>

</select>

</div>

<div class="col-md-1 d-flex align-items-end mb-3">

<button
type="submit"
class="btn btn-primary w-100">

+

</button>

</div>

</div>

</form>

<hr>

<h3>

Usuários Cadastrados

</h3>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Nome</th>
<th>E-mail</th>
<th>Perfil</th>
<th>Status</th>
<th>Cadastro</th>
<th>Ações</th>

</tr>

</thead>

<tbody>

<?php if(count($usuarios) > 0): ?>

<?php foreach($usuarios as $u): ?>

<tr>

<td>

<?= $u['id'] ?>

</td>

<td>

<?= htmlspecialchars($u['nome']) ?>

</td>

<td>

<?= htmlspecialchars($u['email']) ?>

</td>

<td>

<?php if($u['tipo'] == 'admin'): ?>

<span class="badge bg-danger">

Admin

</span>

<?php elseif($u['tipo'] == 'medico'): ?>

<span class="badge bg-primary">

Médico

</span>

<?php else: ?>

<span class="badge bg-success">

Paciente

</span>

<?php endif; ?>

</td>

<td>

<?php if($u['status'] == 'ativo'): ?>

<span class="badge bg-success">

Ativo

</span>

<?php else: ?>

<span class="badge bg-secondary">

Inativo

</span>

<?php endif; ?>

</td>

<td>

<?= date('d/m/Y H:i', strtotime($u['criado_em'])) ?>

</td>

<td>

<a
href="editar-usuario.php?id=<?= $u['id'] ?>"
class="btn btn-warning btn-sm">

Editar

</a>

<?php if($u['tipo'] !== 'admin'): ?>

<a
href="alterar-status.php?id=<?= $u['id'] ?>"
class="btn btn-secondary btn-sm">

Status

</a>

<a
href="excluir-usuario.php?id=<?= $u['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Deseja excluir este usuário?')">

Excluir

</a>

<?php else: ?>

<span class="badge bg-danger">

Administrador Protegido

</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td
colspan="7"
class="text-center">

Nenhum usuário cadastrado.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>
</html>