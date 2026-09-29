import Swal from 'sweetalert2';

function carregarJQuery() {
    if (window.jQuery) {
        return Promise.resolve(window.jQuery);
    }

    return new Promise(function (resolve, reject) {
        const script = document.createElement('script');
        script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js';
        script.onload = function () {
            resolve(window.jQuery);
        };
        script.onerror = function () {
            reject(new Error('Não foi possível carregar a biblioteca do formulário.'));
        };
        document.head.appendChild(script);
    });
}

carregarJQuery().then(function ($) {
    $(function () {
        const formulario = $('#formulario-desperdicio');
        const faixaDesperdicio = $('#desperdicio');
        const valorDesperdicio = $('#valor_desperdicio');
        const quantidadePreparada = $('#quantidade');
        const valorPesoMeta = $('#valor_peso_meta');

        if (!formulario.length) {
            return;
        }

        function atualizarMetaDesperdicio() {
            const percentual = Number(faixaDesperdicio.val() || 0);
            const quantidade = Number(quantidadePreparada.val() || 0);
            const pesoMaximo = quantidade > 0 ? (quantidade * percentual) / 100 : 0;

            valorDesperdicio.text(percentual);
            valorPesoMeta.text(`até ${pesoMaximo.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })} kg`);
        }

        faixaDesperdicio.on('input', atualizarMetaDesperdicio);
        quantidadePreparada.on('input', atualizarMetaDesperdicio);
        atualizarMetaDesperdicio();

        formulario.on('submit', function (evento) {
            evento.preventDefault();

            const salas = $('.sala:checked').map(function () {
                return this.value;
            }).get();

            if (salas.length === 0) {
                Swal.fire('Atenção', 'Selecione pelo menos uma sala.', 'warning');
                return;
            }

            const botao = $('#btn_salvar');
            botao.prop('disabled', true).text('SALVANDO...');

            $.ajax({
                url: '/api/desperdicios',
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${localStorage.getItem('token_usuario')}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify({
                    cardapio: $('#cardapio').val(),
                    periodo: $('input[name="periodo"]:checked').val(),
                    salas: salas,
                    quantidade: quantidadePreparada.val(),
                    desperdicio: faixaDesperdicio.val(),
                    observacoes: $('#observacoes').val(),
                }),
                success: function (dados) {
                    Swal.fire({
                        title: 'Sucesso',
                        text: dados.mensagem,
                        icon: 'success',
                        timer: 1800,
                        showConfirmButton: false,
                        timerProgressBar: true,
                    }).then(function () {
                        window.location.href = `/registrar-desperdicio?refeicao_id=${dados.refeicao_id || ''}`;
                    });
                },
                error: function (xhr) {
                    const errosValidacao = xhr.responseJSON?.errors;
                    const primeiroErro = errosValidacao
                        ? Object.values(errosValidacao).flat()[0]
                        : null;
                    const mensagem = primeiroErro
                        || xhr.responseJSON?.mensagem
                        || xhr.responseJSON?.message
                        || 'Não foi possível salvar o controle.';

                    Swal.fire('Erro', mensagem, 'error');
                },
                complete: function () {
                    botao.prop('disabled', false).text('SALVAR CONTROLE');
                },
            });
        });
    });
}).catch(function (erro) {
    Swal.fire('Erro', erro.message, 'error');
});