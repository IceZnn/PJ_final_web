<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <title>Detalhes do registro</title>
</head>

<body class="page-shell">
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <div class="page-header-actions">
                <a href="{{ route('inicio') }}" class="nav-link" data-user-route>Início</a>
                <a href="{{ route('desperdicios.index') }}" class="nav-link" data-user-route>Registros</a>
                <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <p class="dashboard-label mb-2">DETALHES</p>
                <h1 class="page-title">Registro de alimentação</h1>
            </div>
            <a href="{{ route('desperdicios.index') }}" class="btn btn-ghost-primary">Voltar para lista</a>
        </div>

        <div class="card dashboard-surface border-0 rounded-4 shadow-sm">
            <div class="card-body p-4 p-xl-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <p class="text-muted text-uppercase fw-bold small mb-2">Cardápio</p>
                        <h3 class="mb-3">{{ $registro->cardapio }}</h3>

                        <div class="detail-grid">
                            <div>
                                <span>Período</span>
                                <strong>{{ strtoupper($registro->periodo) }}</strong>
                            </div>
                            <div>
                                <span>Salas</span>
                                <strong>{{ implode(', ', $registro->salas ?? []) }}</strong>
                            </div>
                            <div>
                                <span>Quantidade preparada</span>
                                <strong>{{ number_format((float) $registro->quantidade_preparada, 2, ',', '.') }} kg</strong>
                            </div>
                            <div>
                                <span>Meta de desperdício</span>
                                <strong>{{ $registro->maximo_desperdicio }}%</strong>
                            </div>
                            <div>
                                <span>Peso real</span>
                                <strong>{{ $registro->peso_desperdicio ? number_format((float) $registro->peso_desperdicio, 2, ',', '.') . ' kg' : 'Sem peso registrado' }}</strong>
                            </div>
                            <div>
                                <span>Data</span>
                                <strong>{{ $registro->created_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <p class="text-muted text-uppercase fw-bold small mb-2">Observações</p>
                        <div class="detail-box">
                            {{ $registro->observacoes ?: 'Nenhuma observação inserida.' }}
                        </div>
                    </div>
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
