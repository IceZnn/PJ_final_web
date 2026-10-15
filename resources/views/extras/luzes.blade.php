<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Luzes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/luzes.js'])

    <style>
        .luz {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background-color: #555;
            margin: 0 auto 20px auto;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .luz.verde.ligada {
            background-color: #198754;
            box-shadow: 0 0 30px 10px rgba(25, 135, 84, 0.7);
        }

        .luz.vermelha.ligada {
            background-color: #dc3545;
            box-shadow: 0 0 30px 10px rgba(220, 53, 69, 0.7);
        }

        .luz.amarela.ligada {
            background-color: #ffc107;
            box-shadow: 0 0 30px 10px rgba(255, 193, 7, 0.7);
        }
    </style>
</head>

<body>

    <h2 class="text-center my-4">Luzes</h2>

    <div class="container">
        <div class="row justify-content-center">

            <div class="col-3 text-center">
                <div id="luz_verde" class="luz verde"></div>
                <button id="ligar_verde" class="btn btn-success w-100 mb-2">Ligar Luz Verde</button>
                <button id="desligar_verde" class="btn btn-outline-success w-100">Desligar Luz Verde</button>
            </div>

            <div class="col-3 text-center">
                <div id="luz_vermelha" class="luz vermelha"></div>
                <button id="ligar_vermelha" class="btn btn-danger w-100 mb-2">Ligar Luz Vermelha</button>
                <button id="desligar_vermelha" class="btn btn-outline-danger w-100">Desligar Luz Vermelha</button>
            </div>

            <div class="col-3 text-center">
                <div id="luz_amarela" class="luz amarela"></div>
                <button id="ligar_amarela" class="btn btn-warning w-100 mb-2">Ligar Luz Amarela</button>
                <button id="desligar_amarela" class="btn btn-outline-warning w-100">Desligar Luz Amarela</button>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-3">
                    <button id="ligar_todas" class="btn btn-dark w-100">Ligar Todas</button>
                </div>
                <div class="col-3">
                    <button id="desligar_todas" class="btn btn-outline-dark w-100">Desligar Todas</button>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
