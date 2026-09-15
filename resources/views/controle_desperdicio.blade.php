<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/inicio.js'])
    <title>Controle de Desperdício</title>
</head>

<body>
    <header class="header-sesi">
        <div class="header-sesi-content">
            <div class="header-logo">SESI</div>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
            <a href="{{ route('inicio') }}" class="btn btn-outline-light">Início</a>
            <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
        </div>
    </header>

    <main class="container formulario">
        <div class="formulario-cabecalho">
            <div>
                <p class="boas-vindas-etiqueta">NOVO REGISTRO</p>
                <h1 class="titulo">Controle de desperdício</h1>
                <p class="boas-vindas-texto">Preencha os dados da refeição preparada.</p>
            </div>
            <a href="{{ route('inicio') }}" class="link-voltar">Voltar para boas-vindas</a>
        </div>

        <form id="formulario-desperdicio">
            @csrf
            <div class="mb-4">
                <label for="cardapio" class="form-label">Cardápio</label>
                <textarea class="form-control" id="cardapio" rows="4" placeholder="Digite o cardápio do dia" required></textarea>
            </div>

            <div class="formulario-grid mb-4">
                <div>
                    <h4>Período</h4>
                    @foreach (['cafe' => 'Café', 'almoco' => 'Almoço', 'cafe_tarde' => 'Café (tarde)'] as $valor => $rotulo)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="periodo" id="{{ $valor }}" value="{{ $valor }}" required>
                            <label class="form-check-label" for="{{ $valor }}">{{ $rotulo }}</label>
                        </div>
                    @endforeach
                </div>

                <div>
                    <h4>Salas atendidas</h4>
                    @foreach (['fund1' => 'Fund 1', 'fund2' => 'Fund 2', 'em' => 'E.M', 'senai' => 'SENAI'] as $valor => $rotulo)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input sala" type="checkbox" id="{{ $valor }}" value="{{ $valor }}">
                            <label class="form-check-label" for="{{ $valor }}">{{ $rotulo }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="formulario-grid mb-4">
                <div>
                    <label for="quantidade" class="form-label">Quantidade preparada (kg)</label>
                    <input type="number" class="form-control" id="quantidade" placeholder="Ex.: 25,50" min="0" step="0.01" required>
                </div>
                <div>
                    <label for="desperdicio" class="form-label">Máximo de desperdício: <span id="valor_desperdicio">10</span>%</label>
                    <input type="range" class="form-range" min="0" max="100" value="10" id="desperdicio">
                </div>
            </div>

            <div class="mb-4">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea class="form-control" id="observacoes" rows="4" placeholder="Digite observações adicionais"></textarea>
            </div>

            <div class="formulario-rodape">
                <span>Confira os dados antes de salvar.</span>
                <button type="submit" class="btn btn-salvar" id="btn_salvar">SALVAR CONTROLE</button>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('botao-logout').addEventListener('click', async function () {
            const token = localStorage.getItem('token_usuario');
            try {
                await fetch('{{ route('api.logout') }}', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                });
            } finally {
                localStorage.removeItem('token_usuario');
                window.location.href = '{{ route('login') }}';
            }
        });
    </script>
</body>

</html>