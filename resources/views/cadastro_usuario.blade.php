<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/cadastro_usuario.js'])
    <title>Cadastro de Usuário</title>
</head>

<body>
    <header class="header-sesi">
        <div class="header-sesi-content">
            <div class="header-logo">SESI</div>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
        </div>
    </header>

    <main class="container formulario">
        <h2 class="titulo">Cadastro de Usuário</h2>

        <div class="row g-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email" required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control form-control-sa" id="senha" name="senha"
                    placeholder="Digite sua senha" required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF"
                    inputmode="numeric" maxlength="11" required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="escola" class="form-label">Escola</label>
                <input type="text" class="form-control" id="escola" name="escola" placeholder="Digite sua escola" required>
            </div>

            <div class="col-12 text-center">
                <button id="cadastro_usuario" type="button" class="btn btn-salvar">CADASTRAR USUÁRIO</button>
                <p class="mt-3 mb-0">
                    Já possui cadastro?
                    <a href="{{ route('login') }}" class="link-primary fw-semibold">Ir para o login</a>
                </p>
            </div>
        </div>
    </main>
</body>

</html>
