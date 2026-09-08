<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css'])
    <title>Início</title>
</head>

<body>
    <header class="header-sesi">
        <div class="header-sesi-content">
            <div class="header-logo">SESI</div>
            <div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
        </div>
    </header>

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
</body>

</html>
