<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])
    <title>Bem-vindo</title>
</head>

<body>
    <nav class="navbar navbar-expand navbar-dark header-sesi">
        <div class="container">
            <a class="navbar-brand logo-sesi" href="{{ route('inicio') }}">SESI</a>

            <div class="menu-principal" id="menuPrincipal">
                <span class="navbar-text ms-lg-3">Sistema de Controle de Desperdício Alimentar</span>
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('inicio') }}">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('controle.desperdicio') }}">Registrar desperdício</a>
                    </li>
                    <li class="nav-item">
                        <button type="button" id="botao-logout" class="btn btn-outline-light mt-2 mt-lg-0">Sair</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container tela-boas-vindas">
        <section class="boas-vindas">
            <p class="boas-vindas-etiqueta">SESI | GESTÃO ALIMENTAR</p>
            <h1 class="titulo">Olá, seja bem-vindo!</h1>
            <p class="boas-vindas-texto">Organize o acompanhamento das refeições e ajude sua escola a reduzir o desperdício.</p>
            <a href="{{ route('controle.desperdicio') }}" class="btn btn-salvar">COMEÇAR UM REGISTRO</a>
        </section>

        <section class="boas-vindas-painel">
            <div class="boas-vindas-painel-numero">01</div>
            <div>
                <h2>Registre todos os detalhes</h2>
                <p>Informe o cardápio, o período, as salas atendidas e a quantidade preparada.</p>
            </div>
        </section>
    </main>

    @if (session('sucesso'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Tudo certo!',
                text: @json(session('sucesso')),
                confirmButtonColor: '#005baa'
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        console.log('Token do usuário:', localStorage.getItem('token_usuario'));

        document.getElementById('botao-logout').addEventListener('click', async function () {
            const token = localStorage.getItem('token_usuario');

            try {
                await fetch('{{ route('api.logout') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
            } finally {
                localStorage.removeItem('token_usuario');
                window.location.href = '{{ route('login') }}';
            }
        });
    </script>
</body>

</html>
