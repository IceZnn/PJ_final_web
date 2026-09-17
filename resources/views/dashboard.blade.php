<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    @vite(['resources/css/app.css'])
    <title>Dashboard</title>
</head>

<body class="bg-body-tertiary dashboard-page-shell">
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <div class="page-header-actions">
                <a class="nav-link" href="{{ route('inicio') }}" data-user-route>Início</a>
                <a class="nav-link active" href="{{ route('dashboard') }}" data-user-route>Dashboard</a>
                <a class="nav-link" href="{{ route('controle.desperdicio') }}" data-user-route>Registrar refeição</a>
                <a class="nav-link" href="{{ route('desperdicios.index') }}" data-user-route>Registros</a>
                <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
            </div>
        </div>
    </header>

    <main class="container dashboard-main">
        <div class="dashboard-hero mb-4">
            <div>
                <span class="hero-chip">Painel operacional</span>
                <h1 class="dashboard-title mb-0">Controle de desperdício</h1>
            </div>
            <div class="hero-actions">
                <span class="hero-tag">Semana atual</span>
                <button class="btn btn-primary btn-sm px-3 py-2 rounded-pill dashboard-filter">Filtrar</button>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-12 col-xl-8">
                <div class="card dashboard-card shadow-sm border-0 rounded-4 dashboard-surface">
                    <div class="card-body p-4 p-xl-5">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="metric-card metric-card-primary">
                                    <span class="metric-label">Aproveitamento médio</span>
                                    <h3>{{ $aproveitamentoMedio }}%</h3>
                                    <small>Perda média: {{ $percentualPerdaMedia }}%</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metric-card metric-card-emerald">
                                    <span class="metric-label">Produção total</span>
                                    <h3>{{ $totalPreparado }} kg</h3>
                                    <small>Peso real perdido: {{ $pesoRealTotal }} kg</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metric-card metric-card-warning">
                                    <span class="metric-label">Alertas</span>
                                    <h3>{{ str_pad((string) $alertas, 2, '0', STR_PAD_LEFT) }}</h3>
                                    <small>{{ $alertas > 0 ? 'em revisão' : 'sem alertas' }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="card chart-panel border-0 rounded-4 mt-2">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h2 class="chart-title mb-0">Média de desperdício</h2>
                                    <span class="badge text-bg-light text-dark rounded-pill px-3">Últimos 5 meses</span>
                                </div>

                                <div class="chart-bars" aria-label="Média de desperdício por mês">
                                    @foreach ($monthlyData as $item)
                                        <div class="chart-column">
                                            <div class="chart-bar @if ($loop->index % 5 === 0) chart-blue @elseif ($loop->index % 5 === 1) chart-cyan @elseif ($loop->index % 5 === 2) chart-gold @elseif ($loop->index % 5 === 3) chart-pink @else chart-orange @endif" style="height: {{ $item['height'] }}%;"></div>
                                            <span>{{ $item['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card dashboard-card shadow-sm border-0 rounded-4 h-100 dashboard-surface sidebar-panel">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <p class="text-uppercase dashboard-label mb-2">Período</p>
                            <h2 class="chart-title mb-0">Desperdício por período</h2>
                        </div>

                        <div class="period-bars" aria-label="Desperdício por período">
                            @foreach ($periodData as $item)
                                <div class="period-item">
                                    <div class="period-bar @if ($loop->index === 0) period-blue @elseif ($loop->index === 1) period-cyan @else period-orange @endif" style="height: {{ $item['height'] }}%;"></div>
                                    <span>{{ $item['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert-card mt-4">
                            <p class="dashboard-label text-uppercase mb-3">Situação atual</p>
                            <div class="alert-circle">
                                <span>!</span>
                            </div>
                            <p class="alert-text mb-0">
                                @if ($turnoCritico && $turnoCritico['atual'] > $turnoCritico['meta'])
                                    Atenção no turno de {{ strtolower($turnoCritico['turno']) }}: {{ number_format($turnoCritico['atual'], 1, ',', '.') }}% de desperdício.
                                @else
                                    O desempenho está estável e dentro da meta esperada.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-12">
                <div class="card dashboard-card shadow-sm border-0 rounded-4 dashboard-surface">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="section-subtitle mb-0">Comparativo por turno</h3>
                            <span class="badge text-bg-light text-dark rounded-pill px-3">Esta semana</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Meta</th>
                                        <th>Atual</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($turnos as $turno)
                                        <tr>
                                            <td>{{ $turno['turno'] }}</td>
                                            <td>{{ $turno['meta'] }}%</td>
                                            <td>{{ number_format($turno['atual'], 1, ',', '.') }}%</td>
                                            <td>
                                                @if ($turno['statusClass'] === 'success')
                                                    <span class="badge rounded-pill bg-success-subtle text-success">{{ $turno['status'] }}</span>
                                                @else
                                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $turno['status'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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

        document.getElementById('botao-logout')?.addEventListener('click', async function () {
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
