<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])
    <title>Registrar peso do desperdício</title>
</head>

<body class="page-shell">
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <div class="page-header-actions">
                <a href="{{ route('inicio') }}" class="nav-link" data-user-route>Início</a>
                <a href="{{ route('dashboard') }}" class="nav-link" data-user-route>Dashboard</a>
                <a href="{{ route('desperdicios.index') }}" class="nav-link" data-user-route>Registros</a>
                <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
            </div>
        </div>
    </header>

    <main class="container formulario">
        <div class="formulario-cabecalho page-header">
            <div>
                <p class="boas-vindas-etiqueta">ETAPA 02</p>
                <h1 class="titulo">Registrar peso do desperdício</h1>
                <p class="boas-vindas-texto">Primeiro você cadastrou a refeição e a meta. Agora informe o peso real perdido para calcular o percentual final.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('controle.desperdicio') }}" class="link-voltar">Voltar ao cadastro da refeição</a>
                <a href="{{ route('desperdicios.index') }}" class="btn btn-ghost-primary">Ver registros</a>
            </div>
        </div>

        <form id="formulario-peso-desperdicio" class="registro-formulario">
            @csrf
            <div class="mb-4">
                <label for="refeicao_id" class="form-label">Refeição cadastrada</label>
                <select class="form-select" id="refeicao_id" name="refeicao_id" required>
                    <option value="">Selecione</option>
                    @foreach ($refeicoes as $refeicao)
                        <option value="{{ $refeicao->id }}" {{ request('refeicao_id') == $refeicao->id ? 'selected' : '' }}>
                            {{ $refeicao->cardapio }} · {{ $refeicao->periodo }} · {{ $refeicao->created_at->format('d/m/Y H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="peso_desperdicio" class="form-label">Peso do desperdício (kg)</label>
                <input type="number" class="form-control" id="peso_desperdicio" name="peso_desperdicio" min="0" step="0.01" placeholder="Ex.: 3,25" required>
            </div>

            <div class="formulario-rodape">
                <span>Depois disso você pode seguir para o dashboard.</span>
                <button type="submit" class="btn btn-salvar" id="btn_peso_desperdicio">SALVAR PESO</button>
            </div>
        </form>
    </main>

    <footer class="site-footer">
        <div class="container">
            <small>© 2026 SESI · Sistema de Controle de Desperdício</small>
            <a href="{{ route('dashboard') }}">Ir para o dashboard</a>
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

        document.getElementById('formulario-peso-desperdicio')?.addEventListener('submit', async function (event) {
            event.preventDefault();

            const form = event.currentTarget;
            const body = {
                refeicao_id: document.getElementById('refeicao_id').value,
                peso_desperdicio: document.getElementById('peso_desperdicio').value,
            };

            const botao = document.getElementById('btn_peso_desperdicio');
            botao.disabled = true;
            botao.textContent = 'SALVANDO...';

            try {
                const resposta = await fetch('{{ route('api.desperdicios.peso') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token_usuario')}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(body),
                });

                const dados = await resposta.json();
                if (!resposta.ok) {
                    throw new Error(dados.message || 'Não foi possível salvar o peso do desperdício.');
                }

                await Swal.fire({
                    title: 'Sucesso',
                    text: dados.mensagem,
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });

                window.location.href = '{{ route('dashboard') }}';
            } catch (erro) {
                Swal.fire('Erro', erro.message, 'error');
            } finally {
                botao.disabled = false;
                botao.textContent = 'SALVAR PESO';
            }
        });
    </script>
</body>

</html>
