$(document).ready(function() {

    $("#cpf").on("input", function() {
        this.value = this.value.replace(/\D/g, "").slice(0, 11);
    });

    $("#cadastro_usuario").click(function() {
        const nome = $("#nome").val().trim();
        const email = $("#email").val().trim();
        const senha = $("#senha").val();
        const cpf = $("#cpf").val().replace(/\D/g, "");
        const escola = $("#escola").val().trim();

        //validação dos campos e tals
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
        
        $.ajax({
            url: "api/cadastro_usuario",
            type: "POST",
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
            error: function() {
                Swal.fire("Erro", "Não foi possível cadastrar o usuário.", "error");
            }
        });        

    });

});