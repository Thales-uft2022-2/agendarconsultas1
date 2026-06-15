# Sistema de Agendamento de Consultas

Sistema web desenvolvido em PHP e MySQL para gerenciamento de consultas médicas.

## Funcionalidades

- Autenticação de usuários
- Controle de acesso por perfil
- Cadastro de médicos
- Cadastro de pacientes
- Agendamento de consultas
- Edição e cancelamento de consultas
- Dashboard com indicadores
- Gerenciamento de usuários
- Controle de status (ativo/inativo)

## Tecnologias Utilizadas

- PHP 8
- MySQL
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- XAMPP

## Estrutura do Projeto

```text
agendarconsultas1/

├── admin/
├── assets/
├── auth/
├── config/
├── consultas/
├── dashboard/
├── medicos/
├── pacientes/
├── database/
├── index.html
└── README.md
```

## Requisitos

- PHP 8 ou superior
- MySQL 8 ou superior
- XAMPP

## Instalação

1. Clone o repositório:

```bash
git clone https://github.com/SEU-USUARIO/agendarconsultas1.git
```

2. Copie a pasta para o diretório do XAMPP:

```text
C:\xampp\htdocs\
```

3. Inicie o Apache e o MySQL.

4. Crie um banco de dados chamado:

```text
agendarconsultas
```

5. Importe o arquivo:

```text
agendarconsultas.sql
```

6. Acesse:

```text
http://localhost/agendarconsultas1
```

## Usuário Administrador

Crie um usuário administrador pela tela de cadastro ou diretamente no banco de dados.

## Melhorias Futuras

- Recuperação de senha
- Upload de fotos
- Relatórios em PDF
- Agenda inteligente
- Notificações por e-mail
- Prontuário eletrônico

## Autor

Thales Marques Rodrigues