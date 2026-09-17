<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])
    <title>Registros de desperdício</title>
</head>

<body class="page-shell">
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <div class="page-header-actions">
                <a href="{{ route('inicio') }}" class="nav-link" data-user-route>Início</a>
                <a href="{{ route('dashboard') }}" class="nav-link" data-user-route>Dashboard</a>
                <a href="{{ route('desperdicio.registro') }}" class="nav-link" data-user-route>Refeição</a>
                <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <p class="dashboard-label mb-2">REGISTROS</p>
                <h1 class="page-title">Todos os registros</h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('desperdicio.registro') }}" class="btn btn-primary dashboard-filter">Novo registro</a>
                <a href="{{ route('dashboard') }}" class="btn btn-ghost-primary">Dashboard</a>
            </div>
        </div>

        @if (session('sucesso'))
            <div class="alert alert-success rounded-4 border-0 shadow-sm">
                {{ session('sucesso') }}
            </div>
        @endif

        <div class="card dashboard-surface border-0 rounded-4 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 registro-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Cardápio</th>
                                <th>Período</th>
                                <th>Salas</th>
                                <th>Preparado</th>
                                <th>Meta</th>
                                <th>Peso real</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($registros as $registro)
                                <tr>
                                    <td>{{ $registro->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ Str::limit($registro->cardapio, 36) }}</td>
                                    <td>{{ strtoupper($registro->periodo) }}</td>
                                    <td>{{ implode(', ', $registro->salas ?? []) }}</td>
                                    <td>{{ number_format((float) $registro->quantidade_preparada, 2, ',', '.') }} kg</td>
                                    <td>{{ $registro->maximo_desperdicio }}%</td>
                                    <td>{{ $registro->peso_desperdicio ? number_format((float) $registro->peso_desperdicio, 2, ',', '.') . ' kg' : '—' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="{{ route('desperdicios.show', $registro->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">Detalhes</a>
                                            <form action="{{ route('desperdicios.destroy', $registro->id) }}" method="POST" onsubmit="return confirm('Deseja remover este registro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">Nenhum registro encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <small>© 2026 SESI · Sistema de Controle de Desperdício</small>
            <a href="{{ route('inicio') }}">Voltar ao início</a>
        </div>
    </footer>

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

        document.getElementById('botao-logout')?.addEventListener('click', async function () {
            const token = localStorage.getItem('token_usuario');
            try {
                await fetch('{{ route('api.logout') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
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
