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
            <button type="button" id="botao-logout" class="btn btn-outline-light">Sair</button>
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
