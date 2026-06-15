<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: ../dashboard/");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agendar Consultas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="text-center mb-4">

            <h1 class="titulo">
                Agendar Consultas
            </h1>

            <p class="subtitulo">
                Faça login para acessar o sistema
            </p>

        </div>

        <?php if(isset($_GET['erro'])): ?>

            <div class="alert alert-danger">

                <?php

                switch($_GET['erro']){

                    case 'campos':
                        echo "Preencha todos os campos.";
                        break;

                    case 'usuario':
                        echo "Usuário não encontrado.";
                        break;

                    case 'senha':
                        echo "Senha incorreta.";
                        break;

                    case 'inativo':
                        echo "Usuário inativo.";
                        break;

                    default:
                        echo "Erro ao realizar login.";
                }

                ?>

            </div>

        <?php endif; ?>

        <form action="verificar-login.php" method="POST">

            <div class="mb-3">

                <label class="form-label">
                    E-mail
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Digite seu e-mail"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Senha
                </label>

                <input
                    type="password"
                    name="senha"
                    class="form-control"
                    placeholder="Digite sua senha"
                    required>

            </div>

            <button
                type="submit"
                class="btn btn-primary w-100">

                Entrar

            </button>

        </form>

        <div class="text-center mt-4">

            <a href="../index.html" class="voltar">
                ← Voltar para página inicial
            </a>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>