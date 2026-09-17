import Swal from 'sweetalert2';

const formulario = document.getElementById('formulario-desperdicio');
const faixaDesperdicio = document.getElementById('desperdicio');
const valorDesperdicio = document.getElementById('valor_desperdicio');
const quantidadePreparada = document.getElementById('quantidade');
const valorPesoMeta = document.getElementById('valor_peso_meta');

function atualizarMetaDesperdicio() {
    if (!faixaDesperdicio || !valorDesperdicio) {
        return;
    }

    const percentual = Number(faixaDesperdicio.value || 0);
    const quantidade = Number(quantidadePreparada?.value || 0);
    const pesoMaximo = quantidade > 0 ? (quantidade * percentual) / 100 : 0;

    valorDesperdicio.textContent = String(percentual);

    if (valorPesoMeta) {
        valorPesoMeta.textContent = `até ${pesoMaximo.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })} kg`;
    }
}

if (faixaDesperdicio) {
    faixaDesperdicio.addEventListener('input', atualizarMetaDesperdicio);
}

if (quantidadePreparada) {
    quantidadePreparada.addEventListener('input', atualizarMetaDesperdicio);
}

atualizarMetaDesperdicio();

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
        window.location.href = `/registrar-desperdicio?refeicao_id=${dados.refeicao_id || ''}`;
    } catch (erro) {
        Swal.fire('Erro', erro.message, 'error');
    } finally {
        botao.disabled = false;
        botao.textContent = 'SALVAR CONTROLE';
    }
});