import Swal from 'sweetalert2';

$(document).ready(function() {

    $("#cpf").on("input", function() {
        this.value = this.value.replace(/\D/g, "").slice(0, 11);
    });

    $("#cadastro_usuario").click(function() {
        const botao = $(this);
        const nome = $("#nome").val().trim();
        const email = $("#email").val().trim();
        const senha = $("#senha").val();
        const cpf = $("#cpf").val().replace(/\D/g, "");
        const escola = $("#escola").val().trim();

        if (!nome || !email || !senha || !cpf || !escola) {
            Swal.fire("Atenção", "Preencha todos os campos obrigatórios.", "warning");
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            Swal.fire("Atenção", "Digite um email válido.", "warning");
            return;
        }

        if (cpf.length !== 11) {
            Swal.fire("Atenção", "Digite um CPF válido com 11 dígitos.", "warning");
            return;
        }
        
        botao.prop("disabled", true).text("CADASTRANDO...");

        $.ajax({
            url: "/api/cadastro_usuario",
            type: "POST",
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            data: {
                nome: $("#nome").val(),
                email: $("#email").val(),
                senha: $("#senha").val(),
                cpf: $("#cpf").val(),
                escola: $("#escola").val()
            },
            success: function(response) {
                if (response.erro === "s") {
                    Swal.fire("Atenção", response.mensagem, "warning");
                    botao.prop("disabled", false).text("CADASTRAR USUÁRIO");
                    return;
                }

                Swal.fire({
                    title: "Sucesso",
                    text: response.mensagem,
                    icon: "success",
                    timer: 1800,
                    showConfirmButton: false,
                    timerProgressBar: true
                }).then(function() {
                    window.location.href = "/login";
                });
            },
            error: function(xhr) {
                const errosValidacao = xhr.responseJSON?.errors;
                const primeiroErro = errosValidacao
                    ? Object.values(errosValidacao).flat()[0]
                    : null;
                const mensagem = primeiroErro
                    || xhr.responseJSON?.mensagem
                    || xhr.responseJSON?.message
                    || "Não foi possível cadastrar o usuário.";

                Swal.fire("Erro", mensagem, "error");
                botao.prop("disabled", false).text("CADASTRAR USUÁRIO");
            }
        });        

    });

});