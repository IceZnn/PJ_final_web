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
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <div class="page-header-actions">
                <a class="nav-link active" aria-current="page" href="{{ route('inicio') }}" data-user-route>Início</a>
                <a class="nav-link" href="{{ route('dashboard') }}" data-user-route>Dashboard</a>
                <a class="nav-link" href="{{ route('controle.desperdicio') }}" data-user-route>Registrar refeição</a>
                <a class="nav-link" href="{{ route('desperdicios.index') }}" data-user-route>Registros</a>
                <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
            </div>
        </div>
    </header>

    <main class="container tela-boas-vindas">
        <section class="boas-vindas">
            <p class="boas-vindas-etiqueta">SESI | GESTÃO ALIMENTAR</p>
            <h1 class="titulo">Olá, seja bem-vindo!</h1>
            <p class="boas-vindas-texto">Organize o acompanhamento das refeições e ajude sua escola a reduzir o desperdício.</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('controle.desperdicio') }}" class="btn btn-salvar">CADASTRAR REFEIÇÃO</a>
                <a href="{{ route('desperdicios.index') }}" class="btn btn-ghost-primary">VER REGISTROS</a>
            </div>
        </section>

        <section class="boas-vindas-painel">
            <div class="boas-vindas-painel-numero">01</div>
            <div>
                <h2>Cadastre a refeição</h2>
                <p>Informe o cardápio, o período, as salas atendidas, a quantidade preparada e a meta de desperdício.</p>
            </div>
        </section>
    </main>

    <section class="feature-grid container">
        <article class="feature-card">
            <span class="tag">Meta</span>
            <h3>Monitoramento diário</h3>
            <p>Acompanhe facilmente as quantidades registradas e mantenha o controle do consumo em tempo real.</p>
        </article>

        <article class="feature-card">
            <span class="tag">Ações</span>
            <h3>Menos desperdício</h3>
            <p>Identifique padrões e ajuste os preparos para reduzir perdas e otimizar o uso dos alimentos.</p>
        </article>

        <article class="feature-card">
            <span class="tag">Relatório</span>
            <h3>Visão clara</h3>
            <p>Organize registros por período e tenha uma leitura simples dos dados de cada atendimento.</p>
        </article>
    </section>

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

    <footer class="site-footer">
        <div class="container">
            <small>© 2026 SESI · Sistema de Controle de Desperdício</small>
            <a href="{{ route('inicio') }}">Voltar ao início</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const usuarioId = localStorage.getItem('usuario_id');
            document.querySelectorAll('[data-user-route]').forEach(function (link) {
                if (!usuarioId) return;
                const url = new URL(link.href, window.location.origin);
                url.searchParams.set('usuario_id', usuarioId);
                link.href = url.toString();
            });
        });

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
                localStorage.removeItem('usuario_id');
                window.location.href = '{{ route('login') }}';
            }
        });
    </script>
</body>

</html>
