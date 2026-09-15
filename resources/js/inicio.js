import Swal from 'sweetalert2';

const formulario = document.getElementById('formulario-desperdicio');
const faixaDesperdicio = document.getElementById('desperdicio');
const valorDesperdicio = document.getElementById('valor_desperdicio');

faixaDesperdicio.addEventListener('input', function () {
    valorDesperdicio.textContent = faixaDesperdicio.value;
});

formulario.addEventListener('submit', async function (evento) {
    evento.preventDefault();

    const salas = [...document.querySelectorAll('.sala:checked')].map((sala) => sala.value);
    if (salas.length === 0) {
        Swal.fire('Atenção', 'Selecione pelo menos uma sala.', 'warning');
        return;
    }

    const botao = document.getElementById('btn_salvar');
    botao.disabled = true;
    botao.textContent = 'SALVANDO...';

    try {
        const resposta = await fetch('/api/desperdicios', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token_usuario')}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                cardapio: document.getElementById('cardapio').value,
                periodo: document.querySelector('input[name="periodo"]:checked')?.value,
                salas,
                quantidade: document.getElementById('quantidade').value,
                desperdicio: faixaDesperdicio.value,
                observacoes: document.getElementById('observacoes').value,
            }),
        });

        const dados = await resposta.json();
        if (!resposta.ok) {
            throw new Error(dados.message || 'Não foi possível salvar o controle.');
        }

        await Swal.fire({
            title: 'Sucesso',
            text: dados.mensagem,
            icon: 'success',
            timer: 1800,
            showConfirmButton: false,
            timerProgressBar: true,
        });
        window.location.href = '/inicio';
    } catch (erro) {
        Swal.fire('Erro', erro.message, 'error');
    } finally {
        botao.disabled = false;
        botao.textContent = 'SALVAR CONTROLE';
    }
});