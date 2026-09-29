<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
    <title>Registro não encontrado</title>
</head>

<body class="page-shell">
    <header class="header-sesi">
        <div class="header-sesi-content">
            <span class="header-logo">SESI</span>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
        </div>
    </header>

    <main class="container py-5">
        <div class="dashboard-surface p-4 p-md-5">
            <p class="dashboard-label mb-2">ERRO 404</p>
            <h1 class="page-title">Registro não encontrado</h1>
            <p class="mb-4">{{ $mensagem }}</p>
            <a href="{{ route('desperdicios.index') }}" class="btn btn-primary">Voltar para os registros</a>
        </div>
    </main>
</body>

</html>