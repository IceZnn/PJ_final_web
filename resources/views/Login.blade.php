<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	@vite(['resources/css/app.css', 'resources/js/login.js'])
	<title>Login</title>
</head>

<body>
	<header class="header-sesi">
		<div class="header-sesi-content">
			<div class="header-logo">SESI</div>
			<div class="header-title">Sistema de Controle de Desperdício Alimentar</div>
		</div>
	</header>

	<main class="container formulario">
		<h2 class="titulo">Login</h2>

		<form id="formulario-login" method="POST" action="{{ route('api.login') }}">
			@csrf
			<div class="row g-4 justify-content-center">
				<div class="col-lg-8 col-md-10 col-sm-12">
					<label for="cpf" class="form-label">CPF</label>
					<input type="text" class="form-control" id="cpf" name="cpf" value="{{ old('cpf') }}"
						placeholder="Digite seu CPF" autocomplete="username" inputmode="numeric" maxlength="11" required autofocus>
				</div>
				<div class="col-lg-8 col-md-10 col-sm-12">
					<label for="senha" class="form-label">Senha</label>
					<input type="password" class="form-control" id="senha" name="senha"
						placeholder="Digite sua senha" autocomplete="current-password" required>
				</div>
				<div class="col-12 text-center">
					<button type="submit" class="btn btn-salvar">ENTRAR</button>
					<p class="mt-3 mb-0">
						Ainda não tem cadastro?
						<a href="{{ route('cadastro.usuario') }}" class="link-primary fw-semibold">Cadastre-se</a>
					</p>
				</div>
			</div>
		</form>
	</main>
</body>

</html>

